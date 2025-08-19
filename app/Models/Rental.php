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
        'owner_firstname',
        'owner_lastname',
        'owner_witness_firstname',
        'owner_witness_lastname',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
        if ($this->owner_firstname && $this->owner_lastname) {
            return $this->owner_firstname . ' ' . $this->owner_lastname;
        }
        return null;
    }

    public function getOwnerWitnessFullNameAttribute()
    {
        if ($this->owner_witness_firstname && $this->owner_witness_lastname) {
            return $this->owner_witness_firstname . ' ' . $this->owner_witness_lastname;
        }
        return null;
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? $this->start_date->format('d/m/Y H:i') : null;
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? $this->end_date->format('d/m/Y H:i') : null;
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
            'pending' => 'รอดำเนินการ',
            'using' => 'กำลังใช้งาน',
            'success' => 'เสร็จสิ้น',
            'cancel' => 'ยกเลิก'
        ];
        
        return $statusMap[$this->status] ?? 'ไม่ทราบสถานะ';
    }
}
