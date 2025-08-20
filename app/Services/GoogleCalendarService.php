<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Facades\Log;
use App\Models\Rental;

class GoogleCalendarService
{
    protected $client;
    protected $service;
    protected $calendarId;

    public function __construct()
    {
        try {
            $this->client = new Google_Client();
            $this->client->setApplicationName('Samui Rent Car');
            $this->client->setScopes(Google_Service_Calendar::CALENDAR);
            
            $credentialsPath = storage_path('app/google-calendar-credentials.json');
            Log::info('Loading credentials from: ' . $credentialsPath);
            
            if (!file_exists($credentialsPath)) {
                throw new \Exception('Credentials file not found: ' . $credentialsPath);
            }
            
            $this->client->setAuthConfig($credentialsPath);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');

            // ใช้ primary calendar เพื่อให้ user สามารถเข้าถึงได้
            // หากต้องการใช้ calendar เฉพาะ ให้ตั้งค่าใน .env
            $this->calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
            Log::info('Using calendar ID: ' . $this->calendarId);
            
            // จัดการ SSL issues สำหรับ Windows development
            $httpClient = new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 30,
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                ]
            ]);
            
            $this->client->setHttpClient($httpClient);
            
            $this->service = new Google_Service_Calendar($this->client);
            
            // ตรวจสอบว่า calendar ID ถูกต้อง
            try {
                $calendar = $this->service->calendars->get($this->calendarId);
                Log::info('Calendar found', [
                    'calendar_id' => $calendar->getId(),
                    'summary' => $calendar->getSummary(),
                    'timezone' => $calendar->getTimeZone()
                ]);
                
                // ตรวจสอบว่า calendar สามารถเข้าถึงได้
                if (!$calendar || !$calendar->getId()) {
                    throw new \Exception('Calendar not accessible');
                }
                
                // ตรวจสอบว่า calendar เป็น primary หรือ user calendar
                if (strpos($this->calendarId, 'iam.gserviceaccount.com') !== false) {
                    Log::warning('Using service account calendar, user may not have access', [
                        'calendar_id' => $this->calendarId
                    ]);
                }
                
            } catch (\Exception $e) {
                Log::error('Calendar not found or accessible', [
                    'calendar_id' => $this->calendarId,
                    'error' => $e->getMessage()
                ]);
                
                // ลองใช้ primary calendar แทน
                if ($this->calendarId !== 'primary') {
                    Log::info('Trying primary calendar as fallback');
                    $this->calendarId = 'primary';
                    $calendar = $this->service->calendars->get($this->calendarId);
                    Log::info('Primary calendar found', [
                        'calendar_id' => $calendar->getId(),
                        'summary' => $calendar->getSummary()
                    ]);
                } else {
                    throw new \Exception('Calendar not accessible: ' . $e->getMessage());
                }
            }
            
            Log::info('Google Calendar Service initialized successfully');
            
        } catch (\Exception $e) {
            Log::error('Google Calendar Service initialization failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * เพิ่มการเช่ารถลงใน Google Calendar
     */
    public function createRentalEvent(Rental $rental)
    {
        try {
            if (!$this->service) {
                throw new \Exception('Google Calendar service not initialized');
            }
            
            // ตรวจสอบว่า calendar สามารถเข้าถึงได้
            try {
                $calendar = $this->service->calendars->get($this->calendarId);
                Log::info('Calendar access check', [
                    'calendar_id' => $this->calendarId,
                    'summary' => $calendar->getSummary()
                ]);
                
                // ตรวจสอบว่า calendar สามารถเข้าถึงได้
                if (!$calendar || !$calendar->getId()) {
                    throw new \Exception('Calendar not accessible');
                }
                
                // ทดสอบการเขียนโดยการสร้าง test event ชั่วคราว
                $testEvent = new Google_Service_Calendar_Event();
                $testEvent->setSummary('Test Event - Checking Permissions');
                $testEvent->setStart(new Google_Service_Calendar_EventDateTime([
                    'dateTime' => now()->addHour()->toRfc3339String(),
                    'timeZone' => 'Asia/Bangkok'
                ]));
                $testEvent->setEnd(new Google_Service_Calendar_EventDateTime([
                    'dateTime' => now()->addHours(2)->toRfc3339String(),
                    'timeZone' => 'Asia/Bangkok'
                ]));
                
                $testEventCreated = $this->service->events->insert($this->calendarId, $testEvent);
                
                // ลบ test event ทันที
                $this->service->events->delete($this->calendarId, $testEventCreated->getId());
                
                Log::info('Calendar write permission confirmed', [
                    'calendar_id' => $this->calendarId,
                    'test_event_id' => $testEventCreated->getId()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Calendar permission check failed', [
                    'calendar_id' => $this->calendarId,
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ]);
                
                // หากเป็น permission error ให้ลองใช้ primary calendar
                if (strpos($e->getMessage(), 'Forbidden') !== false || $e->getCode() == 403) {
                    Log::warning('Permission denied, trying to continue with limited access', [
                        'calendar_id' => $this->calendarId
                    ]);
                    // ไม่ต้องหยุดการทำงาน แค่ log warning
                } else {
                    throw $e;
                }
            }

            $event = new Google_Service_Calendar_Event();
            
            // ตั้งชื่อ event แบบใหม่: สถานะ + ชื่อ-นามสกุลผู้จอง
            $statusText = $this->getStatusText($rental->status);
            $customerName = $rental->firstname . ' ' . $rental->lastname;
            $event->setSummary('🚗 ' . $statusText . ' ' . $customerName);
            
            // ตั้งคำอธิบาย
            $description = $this->buildEventDescription($rental);
            $event->setDescription($description);
            
            // ตั้งเวลาเริ่มต้น
            $startDateTime = new Google_Service_Calendar_EventDateTime();
            $startDateTime->setDateTime($rental->start_date->toRfc3339String());
            $startDateTime->setTimeZone('Asia/Bangkok');
            $event->setStart($startDateTime);
            
            // ตั้งเวลาสิ้นสุด
            $endDateTime = new Google_Service_Calendar_EventDateTime();
            $endDateTime->setDateTime($rental->end_date->toRfc3339String());
            $endDateTime->setTimeZone('Asia/Bangkok');
            $event->setEnd($endDateTime);
            
            // ตั้งสถานที่
            $event->setLocation($rental->start_location . ' → ' . $rental->end_location);
            
            // ตั้งสีของ event ตามสถานะ
            $colorId = $this->getEventColorByStatus($rental->status);
            $event->setColorId($colorId);
            
            // เพิ่มข้อมูลลูกค้า (ไม่ใช้ attendees เพื่อหลีกเลี่ยงปัญหา Service Account)
            // $attendees = [
            //     ['email' => $rental->phone . '@example.com', 'displayName' => $rental->full_name]
            // ];
            // $event->setAttendees($attendees);
            
            // เพิ่มการแจ้งเตือน
            $reminders = new \Google_Service_Calendar_EventReminders();
            $reminders->setUseDefault(false);
            $reminders->setOverrides([
                new \Google_Service_Calendar_EventReminder([
                    'method' => 'email',
                    'minutes' => 24 * 60 // แจ้งเตือน 1 วันก่อน
                ]),
                new \Google_Service_Calendar_EventReminder([
                    'method' => 'popup',
                    'minutes' => 60 // แจ้งเตือน 1 ชั่วโมงก่อน
                ])
            ]);
            $event->setReminders($reminders);
            
            // สร้าง event ใน Google Calendar
            $createdEvent = $this->service->events->insert($this->calendarId, $event);
            
            // อัปเดตข้อมูลในฐานข้อมูล
            $rental->update([
                'google_calendar_event_id' => $createdEvent->getId(),
                'calendar_synced_at' => now(),
            ]);
            
            Log::info('Rental event created in Google Calendar', [
                'rental_id' => $rental->id,
                'event_id' => $createdEvent->getId(),
                'customer' => $rental->full_name
            ]);
            
            return $createdEvent;
            
        } catch (\Exception $e) {
            Log::error('Failed to create rental event in Google Calendar', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * อัปเดต event ใน Google Calendar
     */
    public function updateRentalEvent(Rental $rental)
    {
        try {
            if (!$this->service || !$rental->google_calendar_event_id) {
                return $this->createRentalEvent($rental);
            }

            $event = $this->service->events->get($this->calendarId, $rental->google_calendar_event_id);
            
            // อัปเดตข้อมูล event
            $statusText = $this->getStatusText($rental->status);
            $customerName = $rental->firstname . ' ' . $rental->lastname;
            $event->setSummary('🚗 ' . $statusText . ' ' . $customerName);
            $event->setDescription($this->buildEventDescription($rental));
            
            $startDateTime = new Google_Service_Calendar_EventDateTime();
            $startDateTime->setDateTime($rental->start_date->toRfc3339String());
            $startDateTime->setTimeZone('Asia/Bangkok');
            $event->setStart($startDateTime);
            
            $endDateTime = new Google_Service_Calendar_EventDateTime();
            $endDateTime->setDateTime($rental->end_date->toRfc3339String());
            $endDateTime->setTimeZone('Asia/Bangkok');
            $event->setEnd($endDateTime);
            
            $event->setLocation($rental->start_location . ' → ' . $rental->end_location);
            
            // อัปเดตสีตามสถานะใหม่
            $colorId = $this->getEventColorByStatus($rental->status);
            $event->setColorId($colorId);
            
            // อัปเดต event
            $updatedEvent = $this->service->events->update($this->calendarId, $rental->google_calendar_event_id, $event);
            
            // อัปเดตข้อมูลในฐานข้อมูล
            $rental->update(['calendar_synced_at' => now()]);
            
            // หากสถานะเป็น 'cancel' ให้ลบ event ออกจาก calendar
            if ($rental->status === 'cancel') {
                try {
                    $this->deleteRentalEvent($rental);
                    Log::info('Rental event removed from calendar due to cancellation', [
                        'rental_id' => $rental->id,
                        'customer' => $rental->full_name
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to remove cancelled rental from calendar', [
                        'rental_id' => $rental->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            Log::info('Rental event updated in Google Calendar', [
                'rental_id' => $rental->id,
                'event_id' => $updatedEvent->getId(),
                'customer' => $rental->full_name
            ]);
            
            return $updatedEvent;
            
        } catch (\Exception $e) {
            Log::error('Failed to update rental event in Google Calendar', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * ลบ event จาก Google Calendar
     */
    public function deleteRentalEvent(Rental $rental)
    {
        try {
            if (!$this->service || !$rental->google_calendar_event_id) {
                return true;
            }

            $this->service->events->delete($this->calendarId, $rental->google_calendar_event_id);
            
            // อัปเดตข้อมูลในฐานข้อมูล
            $rental->update([
                'google_calendar_event_id' => null,
                'calendar_synced_at' => null,
            ]);
            
            Log::info('Rental event deleted from Google Calendar', [
                'rental_id' => $rental->id,
                'customer' => $rental->full_name
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to delete rental event from Google Calendar', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * สร้างคำอธิบายสำหรับ event
     */
    protected function buildEventDescription(Rental $rental)
    {
        $description = "📋 รายละเอียดการเช่ารถ\n\n";
        $description .= "👤 ลูกค้า: " . $rental->firstname . ' ' . $rental->lastname . "\n";
        $description .= "📱 เบอร์โทร: " . $rental->phone . "\n";
        $description .= "🏠 ที่อยู่: " . $rental->address . "\n";
        $description .= "🚗 รถยนต์: " . ($rental->car_full_name ?? $rental->car_brand) . "\n";
        $description .= "🔢 ทะเบียน: " . $rental->car_license_plate . "\n";
        $description .= "💰 ราคาเช่า: " . number_format($rental->rent_price, 2) . " บาท\n";
        
        if ($rental->insurance_price) {
            $description .= "🛡️ ราคาประกัน: " . number_format($rental->insurance_price, 2) . " บาท\n";
        }
        
        $description .= "📅 ระยะเวลา: " . $rental->rental_days . " วัน\n";
        $description .= "📍 จุดรับ: " . $rental->start_location . "\n";
        $description .= "📍 จุดส่ง: " . $rental->end_location . "\n";
        
        if ($rental->witness_firstname) {
            $description .= "👥 พยาน: " . $rental->witness_full_name . "\n";
        }
        
        $description .= "\n🔗 ระบบ: Samui Rent Car";
        
        return $description;
    }

    /**
     * แปลงสถานะเป็นภาษาไทย
     */
    protected function getStatusText($status)
    {
        switch ($status) {
            case 'pending':
                return '[รอ]';
            case 'using':
                return '[ใช้งาน]';
            case 'success':
            case 'returned':
                return '[เสร็จสิ้น]';
            case 'cancel':
                return '[ยกเลิก]';
            default:
                return '[ไม่ระบุ]';
        }
    }

    /**
     * กำหนดสีของ event ตามสถานะ
     */
    protected function getEventColorByStatus($status)
    {
        switch ($status) {
            case 'pending':
                return '5'; // สีเหลือง
            case 'using':
                return '2'; // สีเขียว
            case 'success':
            case 'returned':
                return '11'; // สีแดง
            case 'cancel':
                return '8'; // สีเทา
            default:
                return '4'; // สีฟ้า (ค่าเริ่มต้น)
        }
    }

    /**
     * ดึง Calendar ID ที่ใช้งานอยู่
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * ดึงข้อมูล event จาก Google Calendar
     */
    public function getEvent($eventId)
    {
        try {
            $event = $this->service->events->get($this->calendarId, $eventId);
            return $event;
        } catch (\Exception $e) {
            Log::error('Failed to get event from Google Calendar', [
                'event_id' => $eventId,
                'calendar_id' => $this->calendarId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * ตรวจสอบการเชื่อมต่อ Google Calendar
     */
    public function testConnection()
    {
        try {
            if (!$this->service) {
                Log::error('Google Calendar service not initialized');
                return false;
            }
            
            // ทดสอบการเชื่อมต่อโดยการดึงข้อมูล calendar
            $calendar = $this->service->calendars->get($this->calendarId);
            
            if ($calendar && $calendar->getId()) {
                Log::info('Google Calendar connection test successful', [
                    'calendar_id' => $calendar->getId(),
                    'summary' => $calendar->getSummary(),
                    'timezone' => $calendar->getTimeZone()
                ]);
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('Google Calendar connection test failed: ' . $e->getMessage());
            Log::error('Error details: ' . json_encode([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]));
            return false;
        }
    }
}
