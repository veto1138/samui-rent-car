<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // สร้าง admin user
        User::create([
            'name' => 'Admin User',
            'nickname' => 'Admin',
            'phone' => '081-234-5678',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // สร้าง user ทั่วไป
        User::create([
            'name' => 'John Doe',
            'nickname' => 'John',
            'phone' => '082-345-6789',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'nickname' => 'Jane',
            'phone' => '083-456-7890',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // สร้าง users เพิ่มเติม
        User::factory(10)->create();
    }
}
