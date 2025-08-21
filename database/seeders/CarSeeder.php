<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'full_name' => 'Toyota Yaris สีแดง',
                'brand_name' => 'Toyota',
                'license_plate' => '4ขภ 5346 กรุงเทพมหานคร',
                'status' => 'available',
            ],
            [
                'full_name' => 'Toyota Revo สีบรอนเทา',
                'brand_name' => 'Toyota',
                'license_plate' => '1ขข 4338 กรุงเทพมหานคร',
                'status' => 'available',
            ],
            [
                'full_name' => 'MG รุ่น MG5 สีบรอนฟ้า',
                'brand_name' => 'MG',
                'license_plate' => '6กข 3987 กรุงเทพมหานคร',
                'status' => 'rented',
            ],
            [
                'full_name' => 'MG รุ่น MG5 สีขาว',
                'brand_name' => 'MG',
                'license_plate' => '7กม 1607 กรุงเทพมหานคร',
                'status' => 'available',
            ],
            [
                'full_name' => 'MG รุ่น MG5 สีดำ',
                'brand_name' => 'MG',
                'license_plate' => 'กย 9303 นครศรีธรรมราช',
                'status' => 'maintenance',
            ],
            [
               'full_name' => 'MG รุ่น MG5 สีดำ',
                'brand_name' => 'MG',
                'license_plate' => '3ขต 3383 กรุงเทพมหานคร',
                'status' => 'available',
            ],
            [
                'full_name' => 'MG รุ่น MG6 สีบรอนเงิน',
                'brand_name' => 'MG',
                'license_plate' => '5กฌ 5528 กรุงเทพมหานคร',
                'status' => 'available',
            ]
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
