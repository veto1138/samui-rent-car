<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class GoogleCalendarController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    /**
     * เพิ่มการเช่ารถลงใน Google Calendar
     */
    public function addToCalendar(Rental $rental)
    {
        try {
            $event = $this->googleCalendarService->createRentalEvent($rental);
            
            return response()->json([
                'success' => true,
                'message' => 'เพิ่มข้อมูลการเช่ารถลงใน Google Calendar เรียบร้อยแล้ว',
                'event_id' => $event->getId(),
                'calendar_url' => $event->getHtmlLink()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to add rental to Google Calendar', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถเพิ่มข้อมูลลงใน Google Calendar ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * อัปเดตข้อมูลการเช่ารถใน Google Calendar
     */
    public function updateInCalendar(Rental $rental)
    {
        try {
            $event = $this->googleCalendarService->updateRentalEvent($rental);
            
            return response()->json([
                'success' => true,
                'message' => 'อัปเดตข้อมูลการเช่ารถใน Google Calendar เรียบร้อยแล้ว',
                'event_id' => $event->getId(),
                'calendar_url' => $event->getHtmlLink()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to update rental in Google Calendar', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถอัปเดตข้อมูลใน Google Calendar ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ลบข้อมูลการเช่ารถจาก Google Calendar
     */
    public function removeFromCalendar(Rental $rental)
    {
        try {
            $this->googleCalendarService->deleteRentalEvent($rental);
            
            return response()->json([
                'success' => true,
                'message' => 'ลบข้อมูลการเช่ารถจาก Google Calendar เรียบร้อยแล้ว'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to remove rental from Google Calendar', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถลบข้อมูลจาก Google Calendar ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ตรวจสอบสถานะการเชื่อมต่อ Google Calendar
     */
    public function testConnection()
    {
        try {
            $isConnected = $this->googleCalendarService->testConnection();
            
            if ($isConnected) {
                return response()->json([
                    'success' => true,
                    'message' => 'เชื่อมต่อ Google Calendar สำเร็จ'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ไม่สามารถเชื่อมต่อ Google Calendar ได้'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Google Calendar connection test failed', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการทดสอบการเชื่อมต่อ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ดูข้อมูล event ใน Google Calendar
     */
    public function viewInCalendar(Rental $rental)
    {
        try {
            Log::info('ViewInCalendar called', [
                'rental_id' => $rental->id,
                'google_calendar_event_id' => $rental->google_calendar_event_id,
                'customer' => $rental->full_name
            ]);
            
            if (!$rental->google_calendar_event_id) {
                Log::warning('Rental has no Google Calendar event ID', [
                    'rental_id' => $rental->id,
                    'customer' => $rental->full_name
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'ยังไม่ได้เพิ่มข้อมูลลงใน Google Calendar'
                ], 404);
            }
            
            // สร้าง URL สำหรับดู event ใน Google Calendar
            $calendarId = $this->googleCalendarService->getCalendarId();
            
            // สร้าง URL สำหรับดู event ใน Google Calendar
            try {
                // ดึงข้อมูล event จาก Google Calendar เพื่อได้ htmlLink
                $event = $this->googleCalendarService->getEvent($rental->google_calendar_event_id);
                $calendarUrl = $event->getHtmlLink();
                
                Log::info('Event htmlLink retrieved', [
                    'rental_id' => $rental->id,
                    'event_id' => $rental->google_calendar_event_id,
                    'html_link' => $calendarUrl
                ]);
                
                // ตรวจสอบว่า URL ไปยัง calendar ที่ถูกต้อง
                if (strpos($calendarUrl, 'iam.gserviceaccount.com') !== false) {
                    // หาก URL ไปยัง service account ให้ใช้ fallback
                    throw new \Exception('URL points to service account calendar');
                }
                
            } catch (\Exception $e) {
                Log::error('Failed to get event htmlLink, using fallback URL', [
                    'rental_id' => $rental->id,
                    'event_id' => $rental->google_calendar_event_id,
                    'error' => $e->getMessage()
                ]);
                
                // หาก event ไม่พบ ให้ลบ event ID ออกจากฐานข้อมูล
                if (strpos($e->getMessage(), 'Not Found') !== false || strpos($e->getMessage(), '404') !== false) {
                    Log::warning('Event not found, clearing invalid event ID', [
                        'rental_id' => $rental->id,
                        'event_id' => $rental->google_calendar_event_id
                    ]);
                    
                    // ลบ event ID ที่ไม่ถูกต้อง
                    $rental->update([
                        'google_calendar_event_id' => null,
                        'calendar_synced_at' => null
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Event ไม่พบใน Google Calendar กรุณา sync ใหม่'
                    ], 404);
                }
                
                // Fallback: ใช้ URL ที่ไปยัง user calendar
                $calendarUrl = $this->createUserCalendarUrl($rental);
            }
            
            Log::info('Calendar URL generated', [
                'rental_id' => $rental->id,
                'event_id' => $rental->google_calendar_event_id,
                'calendar_url' => $calendarUrl,
                'calendar_id' => $calendarId
            ]);
            
            return response()->json([
                'success' => true,
                'event_id' => $rental->google_calendar_event_id,
                'calendar_url' => $calendarUrl,
                'synced_at' => $rental->calendar_synced_at
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get calendar event info', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'ไม่สามารถดึงข้อมูล event ได้: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * สร้าง URL ที่ไปยัง user calendar
     */
    private function createUserCalendarUrl(Rental $rental)
    {
        $eventId = $rental->google_calendar_event_id;
        $startDate = $rental->start_date;
        
        // สร้าง URL ที่ไปยัง user calendar โดยตรง
        $urls = [
            // URL หลักสำหรับดู event
            "https://calendar.google.com/calendar/u/0/r/eventedit/" . $eventId,
            
            // URL สำรองสำหรับดู event
            "https://calendar.google.com/calendar/event?eid=" . $eventId,
            
            // URL ไปยังสัปดาห์ที่มี event
            "https://calendar.google.com/calendar/u/0/r/week/" . date('Y/n/j', strtotime($startDate)),
            
            // URL ไปยังเดือนที่มี event
            "https://calendar.google.com/calendar/u/0/r/month/" . date('Y/n', strtotime($startDate))
        ];
        
        Log::info('User calendar URLs created', [
            'rental_id' => $rental->id,
            'event_id' => $eventId,
            'urls' => $urls
        ]);
        
        // ใช้ URL แรก (eventedit) เป็นหลัก
        return $urls[0];
    }

    /**
     * Sync การเช่ารถทั้งหมดลงใน Google Calendar
     */
    public function syncAllRentals()
    {
        try {
            $rentals = Rental::whereNull('google_calendar_event_id')
                ->where('status', '!=', 'cancel')
                ->get();
            
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
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sync ข้อมูลการเช่ารถลง Google Calendar เรียบร้อยแล้ว",
                'total_rentals' => $rentals->count(),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to sync all rentals to Google Calendar', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการ sync ข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ลบ Event ID ที่ไม่ถูกต้อง
     */
    public function clearInvalidEventIds()
    {
        try {
            $rentals = Rental::whereNotNull('google_calendar_event_id')->get();
            $clearedCount = 0;
            $errors = [];
            
            foreach ($rentals as $rental) {
                try {
                    // ทดสอบว่า event ยังมีอยู่จริง
                    $event = $this->googleCalendarService->getEvent($rental->google_calendar_event_id);
                    if (!$event || !$event->getId()) {
                        throw new \Exception('Event not found');
                    }
                } catch (\Exception $e) {
                    // ลบ event ID ที่ไม่ถูกต้อง
                    $rental->update([
                        'google_calendar_event_id' => null,
                        'calendar_synced_at' => null
                    ]);
                    $clearedCount++;
                    
                    Log::warning('Cleared invalid event ID', [
                        'rental_id' => $rental->id,
                        'event_id' => $rental->google_calendar_event_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "ลบ Event ID ที่ไม่ถูกต้องเรียบร้อยแล้ว",
                'cleared_count' => $clearedCount,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear invalid event IDs', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการลบ Event ID: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ทดสอบ URL สำหรับดู event
     */
    public function testEventUrl(Rental $rental)
    {
        try {
            if (!$rental->google_calendar_event_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ยังไม่ได้เพิ่มข้อมูลลงใน Google Calendar'
                ], 404);
            }

            // ทดสอบ URL หลายแบบ
            $calendarId = $this->googleCalendarService->getCalendarId();
            $eventId = $rental->google_calendar_event_id;

            $urls = [
                'htmlLink' => null,
                'primaryEdit' => "https://calendar.google.com/calendar/u/0/r/eventedit/" . $eventId,
                'primaryEvent' => "https://calendar.google.com/calendar/event?eid=" . $eventId,
                'embed' => "https://calendar.google.com/calendar/embed?src=" . urlencode($calendarId) . "&ctz=Asia/Bangkok",
                'direct' => "https://calendar.google.com/calendar/b/0/r/week/" . date('Y/n/j', strtotime($rental->start_date))
            ];

            // พยายามดึง htmlLink จาก Google API
            try {
                $event = $this->googleCalendarService->getEvent($eventId);
                $urls['htmlLink'] = $event->getHtmlLink();
            } catch (\Exception $e) {
                $urls['htmlLink'] = 'Error: ' . $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'rental_id' => $rental->id,
                'event_id' => $eventId,
                'calendar_id' => $calendarId,
                'urls' => $urls
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to test event URLs', [
                'rental_id' => $rental->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการทดสอบ URL: ' . $e->getMessage()
            ], 500);
        }
    }
}
