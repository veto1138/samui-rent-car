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
        // แก้ไขความยาวของ car_license_plate ในตาราง rentals
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('car_license_plate', 100)->nullable()->change();
        });

        // แก้ไขความยาวของ license_plate ในตาราง cars
        Schema::table('cars', function (Blueprint $table) {
            $table->string('license_plate', 100)->change();
        });

        // แก้ไขความยาวของ license_plate ในตาราง license_plate (ถ้ามี)
        if (Schema::hasTable('license_plate')) {
            Schema::table('license_plate', function (Blueprint $table) {
                $table->string('license_plate', 100)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ย้อนกลับความยาวของ car_license_plate ในตาราง rentals
        Schema::table('rentals', function (Blueprint $table) {
            $table->string('car_license_plate', 100)->nullable()->change();
        });

        // ย้อนกลับความยาวของ license_plate ในตาราง cars
        Schema::table('cars', function (Blueprint $table) {
            $table->string('license_plate', 100)->change();
        });

        // ย้อนกลับความยาวของ license_plate ในตาราง license_plate (ถ้ามี)
        if (Schema::hasTable('license_plate')) {
            Schema::table('license_plate', function (Blueprint $table) {
                $table->string('license_plate', 100)->change();
            });
        }
    }
};
