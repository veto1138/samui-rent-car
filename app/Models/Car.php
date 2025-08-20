<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'brand_name',
        'license_plate',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Accessor สำหรับรูปภาพ
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/cars/default-car.jpg');
    }

    // Scope สำหรับรถที่พร้อมใช้งาน
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope สำหรับรถที่ถูกเช่า
    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }

    // Scope สำหรับรถที่อยู่ในการซ่อมบำรุง
    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    // ความสัมพันธ์กับ Rental
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
