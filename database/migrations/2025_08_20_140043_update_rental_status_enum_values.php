<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // อัปเดต enum values ของคอลัมน์ status
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'booked', 'using', 'success', 'cancel') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // คืนค่าเดิม
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'using', 'success', 'cancel') DEFAULT 'pending'");
    }
};
