<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
