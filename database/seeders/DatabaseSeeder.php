<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'nickname' => 'Test',
            'phone' => '080-000-0000',
            'email' => 'test@example.com',
        ]);

        // เพิ่มข้อมูลการเช่ารถตัวอย่าง
        $this->call([
            RentalSeeder::class,
            UserSeeder::class,
            CarSeeder::class,
        ]);
    }
}
