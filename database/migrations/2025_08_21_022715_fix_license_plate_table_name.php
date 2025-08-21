<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // แก้ไขชื่อตารางจาก licene_plate เป็น license_plate
        if (Schema::hasTable('licene_plate')) {
            Schema::rename('licene_plate', 'license_plate');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ย้อนกลับชื่อตารางจาก license_plate เป็น licene_plate
        if (Schema::hasTable('license_plate')) {
            Schema::rename('license_plate', 'licene_plate');
        }
    }
};
