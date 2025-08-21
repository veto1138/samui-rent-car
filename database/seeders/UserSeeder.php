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
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('Secret1234'),
            'email_verified_at' => now(),
        ]);

        // สร้าง users เพิ่มเติม
        User::factory(10)->create();
    }
}
