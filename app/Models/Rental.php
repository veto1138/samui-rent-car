<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'phone',
        'address',
        'witness_firstname',
        'witness_lastname',
        'rent_price',
        'insurance_price',
        'start_date',
        'end_date',
        'start_location',
        'end_location',
        'selfie_image',
        'national_id_image',
        'driver_license_image',
        'car_brand',
        'car_license_plate',
        'status',
        'write_address',
        'owner_full_name',
        'owner_witness_full_name',
        'google_calendar_event_id',
        'calendar_synced_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'calendar_synced_at' => 'datetime',
    ];

    // Accessor methods
    public function getRentalDaysAttribute()
    {
        if ($this->start_date && $this->end_date) {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);
            return $startDate->diffInDays($endDate);
        }
        return 0;
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getWitnessFullNameAttribute()
    {
        return $this->witness_firstname . ' ' . $this->witness_lastname;
    }

    public function getOwnerFullNameAttribute()
    {
        return $this->owner_full_name;
    }

    public function getOwnerWitnessFullNameAttribute()
    {
        return $this->owner_witness_full_name;
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('d/m/Y H:i') : null;
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d/m/Y H:i') : null;
    }

    public function getThaiStartDateAttribute()
    {
        if (!$this->start_date) {
            return null;
        }
        
        $date = Carbon::parse($this->start_date);
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        return $date->day . ' ' . $thaiMonths[$date->month] . ' ' . ($date->year + 543) . ' เวลา ' . $date->format('H:i') . ' น.';
    }

    public function getThaiEndDateAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        
        $date = Carbon::parse($this->end_date);
        $thaiMonths = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        
        return $date->day . ' ' . $thaiMonths[$date->month] . ' ' . ($date->year + 543) . ' เวลา ' . $date->format('H:i') . ' น.';
    }

    public function getStartTimeAttribute()
    {
        return $this->start_date ? $this->start_date->format('H:i') : null;
    }

    public function getEndTimeAttribute()
    {
        return $this->end_date ? $this->end_date->format('H:i') : null;
    }

    public function getFormattedRentPriceAttribute()
    {
        return $this->rent_price ? '฿' . number_format($this->rent_price, 2) : null;
    }

    public function getFormattedInsurancePriceAttribute()
    {
        return $this->insurance_price ? '฿' . number_format($this->insurance_price, 2) : null;
    }

    public function getStatusTextAttribute()
    {
        $statusMap = [
            'pending' => 'รอจอง',
            'booked' => 'จองแล้ว',
            'using' => 'กำลังใช้งาน',
            'success' => 'เสร็จสิ้น',
            'cancel' => 'ยกเลิก'
        ];
        
        return $statusMap[$this->status] ?? 'ไม่ทราบสถานะ';
    }

    public function getRentPriceTextAttribute()
    {
        return $this->numberToThaiWords($this->rent_price);
    }

    public function getInsurancePriceTextAttribute()
    {
        return $this->numberToThaiWords($this->insurance_price);
    }

    private function numberToThaiWords($number)
    {
        $number = (int) $number;
        
        if ($number == 0) {
            return 'ศูนย์';
        }

        $units = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
        $digits = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        
        $result = '';
        $position = 0;
        
        while ($number > 0) {
            $digit = $number % 10;
            $number = (int) ($number / 10);
            
            if ($digit > 0) {
                if ($position == 1 && $digit == 1) {
                    $result = 'สิบ' . $result;
                } elseif ($position == 1 && $digit == 2) {
                    $result = 'ยี่สิบ' . $result;
                } elseif ($position == 1 && $digit > 2) {
                    $result = $digits[$digit] . 'สิบ' . $result;
                } elseif ($position == 0 && $digit == 1 && $number > 0) {
                    $result = 'เอ็ด' . $result;
                } else {
                    $result = $digits[$digit] . $units[$position] . $result;
                }
            }
            
            $position++;
        }
        
        return $result . 'บาทถ้วน';
    }

    // ตรวจสอบการเช่ารถซ้ำกันในวันเดียวกัน
    public static function checkDuplicateRental($carId, $startDate, $endDate, $excludeId = null)
    {
        $query = self::where('car_id', $carId)
            ->where('status', '!=', 'cancel') // ไม่รวมการเช่าที่ถูกยกเลิก
            ->where(function ($q) use ($startDate, $endDate) {
                // ตรวจสอบการทับซ้อนของช่วงเวลา
                $q->where(function ($subQ) use ($startDate, $endDate) {
                    // กรณีที่วันที่เริ่มใหม่อยู่ในช่วงวันที่เช่าเดิม
                    $subQ->where('start_date', '<=', $startDate)
                          ->where('end_date', '>', $startDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    // กรณีที่วันที่สิ้นสุดใหม่อยู่ในช่วงวันที่เช่าเดิม
                    $subQ->where('start_date', '<', $endDate)
                          ->where('end_date', '>=', $endDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    // กรณีที่ช่วงเวลาใหม่ครอบคลุมช่วงเวลาเดิม
                    $subQ->where('start_date', '>=', $startDate)
                          ->where('end_date', '<=', $endDate);
                });
            });

        // ไม่รวมการเช่าปัจจุบันที่กำลังแก้ไข
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // ตรวจสอบการเช่ารถซ้ำกันโดยใช้ทะเบียนรถ
    public static function checkDuplicateRentalByLicensePlate($licensePlate, $startDate, $endDate, $excludeId = null)
    {
        $query = self::where('car_license_plate', $licensePlate)
            ->where('status', '!=', 'cancel') // ไม่รวมการเช่าที่ถูกยกเลิก
            ->where(function ($q) use ($startDate, $endDate) {
                // ตรวจสอบการทับซ้อนของช่วงเวลา
                $q->where(function ($subQ) use ($startDate, $endDate) {
                    // กรณีที่วันที่เริ่มใหม่อยู่ในช่วงวันที่เช่าเดิม
                    $subQ->where('start_date', '<=', $startDate)
                          ->where('end_date', '>', $startDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    // กรณีที่วันที่สิ้นสุดใหม่อยู่ในช่วงวันที่เช่าเดิม
                    $subQ->where('start_date', '<', $endDate)
                          ->where('end_date', '>=', $endDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    // กรณีที่ช่วงเวลาใหม่ครอบคลุมช่วงเวลาเดิม
                    $subQ->where('start_date', '>=', $startDate)
                          ->where('end_date', '<=', $endDate);
                });
            });

        // ไม่รวมการเช่าปัจจุบันที่กำลังแก้ไข
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    // ตรวจสอบรถที่พร้อมใช้งานในช่วงเวลาที่กำหนด
    public static function getAvailableCars($startDate, $endDate)
    {
        $rentedCarIds = self::where('status', '!=', 'cancel')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '<=', $startDate)
                          ->where('end_date', '>', $startDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '<', $endDate)
                          ->where('end_date', '>=', $endDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '>=', $startDate)
                          ->where('end_date', '<=', $endDate);
                });
            })
            ->pluck('car_id')
            ->filter()
            ->unique();

        return \App\Models\Car::whereNotIn('id', $rentedCarIds)
            ->where('status', 'available')
            ->get();
    }

    // ตรวจสอบรถที่ถูกเช่าในช่วงเวลาที่กำหนด
    public static function getRentedCars($startDate, $endDate)
    {
        return self::where('status', '!=', 'cancel')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '<=', $startDate)
                          ->where('end_date', '>', $startDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '<', $endDate)
                          ->where('end_date', '>=', $endDate);
                })->orWhere(function ($subQ) use ($startDate, $endDate) {
                    $subQ->where('start_date', '>=', $startDate)
                          ->where('end_date', '<=', $endDate);
                });
            })
            ->with('car')
            ->get();
    }

    // Relationships
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Google Calendar Accessors
    public function getIsInGoogleCalendarAttribute()
    {
        return !empty($this->google_calendar_event_id);
    }

    public function getGoogleCalendarUrlAttribute()
    {
        if ($this->google_calendar_event_id) {
            return "https://calendar.google.com/calendar/event?eid=" . $this->google_calendar_event_id;
        }
        return null;
    }

    public function getCalendarSyncStatusAttribute()
    {
        if ($this->is_in_google_calendar) {
            return 'synced';
        }
        return 'not_synced';
    }

    public function getCalendarSyncStatusTextAttribute()
    {
        $statusMap = [
            'synced' => 'เชื่อมต่อแล้ว',
            'not_synced' => 'ยังไม่ได้เชื่อมต่อ'
        ];
        
        return $statusMap[$this->calendar_sync_status] ?? 'ไม่ทราบสถานะ';
    }
}
