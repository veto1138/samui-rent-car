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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 100); //ชื่อรถยนต์
            $table->string('brand_name', 100); //ชื่อรุ่นรถ
            $table->string('license_plate', 100); //ทะเบียนรถ
            $table->string('image', 100)->nullable(); //รูปภาพรถ
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available'); //สถานะรถ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
