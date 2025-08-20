<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleCalendarService;
use App\Models\Rental;

class GoogleCalendarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-calendar:sync {--all : Sync all rentals} {--rental-id= : Sync specific rental by ID} {--test : Test connection}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Google Calendar integration for car rentals';

    protected $googleCalendarService;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->googleCalendarService = new GoogleCalendarService();

        if ($this->option('test')) {
            return $this->testConnection();
        }

        if ($this->option('rental-id')) {
            return $this->syncSpecificRental();
        }

        if ($this->option('all')) {
            return $this->syncAllRentals();
        }

        $this->showHelp();
    }

    /**
     * ทดสอบการเชื่อมต่อ Google Calendar
     */
    protected function testConnection()
    {
        $this->info('🔌 ทดสอบการเชื่อมต่อ Google Calendar...');
        
        try {
            $isConnected = $this->googleCalendarService->testConnection();
            
            if ($isConnected) {
                $this->info('✅ เชื่อมต่อ Google Calendar สำเร็จ!');
                return 0;
            } else {
                $this->error('❌ ไม่สามารถเชื่อมต่อ Google Calendar ได้');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ เกิดข้อผิดพลาด: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Sync การเช่ารถเฉพาะรายการ
     */
    protected function syncSpecificRental()
    {
        $rentalId = $this->option('rental-id');
        
        $this->info("🔄 Sync การเช่ารถ ID: {$rentalId}");
        
        try {
            $rental = Rental::find($rentalId);
            
            if (!$rental) {
                $this->error("❌ ไม่พบการเช่ารถ ID: {$rentalId}");
                return 1;
            }

            if ($rental->google_calendar_event_id) {
                $this->info("📅 การเช่ารถนี้มีใน Google Calendar แล้ว (Event ID: {$rental->google_calendar_event_id})");
                
                if ($this->confirm('ต้องการอัปเดตข้อมูลใน Google Calendar หรือไม่?')) {
                    $this->googleCalendarService->updateRentalEvent($rental);
                    $this->info('✅ อัปเดตข้อมูลใน Google Calendar เรียบร้อยแล้ว');
                }
            } else {
                $this->info("➕ เพิ่มการเช่ารถลงใน Google Calendar...");
                $event = $this->googleCalendarService->createRentalEvent($rental);
                $this->info("✅ เพิ่มข้อมูลลงใน Google Calendar เรียบร้อยแล้ว (Event ID: {$event->getId()})");
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ เกิดข้อผิดพลาด: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Sync การเช่ารถทั้งหมด
     */
    protected function syncAllRentals()
    {
        $this->info('🔄 Sync การเช่ารถทั้งหมดลงใน Google Calendar...');
        
        try {
            $rentals = Rental::whereNull('google_calendar_event_id')
                ->where('status', '!=', 'cancel')
                ->get();
            
            if ($rentals->isEmpty()) {
                $this->info('ℹ️ ไม่มีการเช่ารถที่ต้อง sync');
                return 0;
            }
            
            $this->info("📊 พบการเช่ารถที่ต้อง sync: {$rentals->count()} รายการ");
            
            $bar = $this->output->createProgressBar($rentals->count());
            $bar->start();
            
            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            
            foreach ($rentals as $rental) {
                try {
                    $this->googleCalendarService->createRentalEvent($rental);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = [
                        'rental_id' => $rental->id,
                        'customer' => $rental->full_name,
                        'error' => $e->getMessage()
                    ];
                }
                
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            
            $this->info("✅ Sync เสร็จสิ้น!");
            $this->info("📈 สำเร็จ: {$successCount} รายการ");
            $this->info("❌ ผิดพลาด: {$errorCount} รายการ");
            
            if ($errorCount > 0) {
                $this->warn("⚠️ รายการที่มีปัญหา:");
                foreach ($errors as $error) {
                    $this->warn("  - ID: {$error['rental_id']}, ลูกค้า: {$error['customer']}, ข้อผิดพลาด: {$error['error']}");
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ เกิดข้อผิดพลาด: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * แสดงความช่วยเหลือ
     */
    protected function showHelp()
    {
        $this->info('🚗 Google Calendar Management Command');
        $this->newLine();
        $this->info('การใช้งาน:');
        $this->line('  php artisan google-calendar:sync --test                    # ทดสอบการเชื่อมต่อ');
        $this->line('  php artisan google-calendar:sync --rental-id=1            # Sync การเช่ารถเฉพาะรายการ');
        $this->line('  php artisan google-calendar:sync --all                    # Sync การเช่ารถทั้งหมด');
        $this->newLine();
        $this->info('ตัวอย่าง:');
        $this->line('  php artisan google-calendar:sync --test');
        $this->line('  php artisan google-calendar:sync --rental-id=5');
        $this->line('  php artisan google-calendar:sync --all');
    }
}
