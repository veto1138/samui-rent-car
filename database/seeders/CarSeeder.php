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
                'full_name' => 'Toyota Camry',
                'brand_name' => 'Camry',
                'license_plate' => 'กข-1234',
                'status' => 'available',
            ],
            [
                'full_name' => 'Honda Civic',
                'brand_name' => 'Civic',
                'license_plate' => 'กข-5678',
                'status' => 'available',
            ],
            [
                'full_name' => 'Toyota Fortuner',
                'brand_name' => 'Fortuner',
                'license_plate' => 'กข-9012',
                'status' => 'rented',
            ],
            [
                'full_name' => 'Honda CR-V',
                'brand_name' => 'CR-V',
                'license_plate' => 'กข-3456',
                'status' => 'available',
            ],
            [
                'full_name' => 'Mazda CX-5',
                'brand_name' => 'CX-5',
                'license_plate' => 'กข-7890',
                'status' => 'maintenance',
            ],
            [
                'full_name' => 'Nissan X-Trail',
                'brand_name' => 'X-Trail',
                'license_plate' => 'กข-2345',
                'status' => 'available',
            ],
            [
                'full_name' => 'Toyota Vios',
                'brand_name' => 'Vios',
                'license_plate' => 'กข-6789',
                'status' => 'available',
            ],
            [
                'full_name' => 'Honda City',
                'brand_name' => 'City',
                'license_plate' => 'กข-0123',
                'status' => 'rented',
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
