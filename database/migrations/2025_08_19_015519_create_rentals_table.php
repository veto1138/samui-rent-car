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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 100); //ชื่อ
            $table->string('lastname', 100); //นามสกุล
            $table->string('national_id', 100); //รหัสประชาชน
            $table->string('phone', 15); //เบอร์โทรศัพท์
            $table->string('address', 100); //ที่อยู่ตามบัตรประชาชน
            $table->string('witness_firstname', 100); //ชื่อพยาน
            $table->string('witness_lastname', 100); //นามสกุลพยาน
            $table->string('rent_price', 10)->nullable(); //ราคาเช่า
            $table->string('insurance_price', 10)->nullable(); //ราคาประกัน
            $table->dateTime('start_date'); //วันที่เริ่ม
            $table->dateTime('end_date'); //วันที่สิ้นสุด
            $table->string('start_location', 150); //สถานที่เริ่ม
            $table->string('end_location', 150); //สถานที่สิ้นสุด
            $table->string('selfie_image', 255)->nullable(); //รูปถ่ายตัวเอง
            $table->string('national_id_image', 255)->nullable(); //รูปถ่ายบัตรประชาชน
            $table->string('driver_license_image', 255)->nullable(); //รูปถ่ายใบขับขี่
            $table->unsignedBigInteger('car_id')->nullable(); //ID รถยนต์
            $table->string('car_brand', 100)->nullable(); //ยี่ห้อรถ
            $table->string('car_license_plate', 20)->nullable(); //ทะเบียนรถ
            $table->string('car_full_name', 100)->nullable(); //ชื่อรถยนต์
            $table->enum('status', ['pending', 'booked', 'using', 'success', 'cancel'])->default('pending'); //สถานะ
            $table->text('write_address',255)->nullable(); //เขียนที่
            $table->string('owner_full_name', 100)->nullable(); //ชื่อเจ้าของรถ
            $table->string('owner_witness_full_name', 100)->nullable(); //ชื่อพยานเจ้าของรถ
            $table->string('google_calendar_event_id')->nullable();
            $table->timestamp('calendar_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
// ตัวอย่าง : บ้านเลขที่ 70 หมู่บ้าน ศุภาลัย ซอย มังกร 1 ถนน หลวง หมู่ 5 ต.ผักแว่น อ.จังหาร จ.สกลนคร 67250

//ข้อ 5,6 ตัวอย่าง วันที่ 25 สิงหาคม 2568 เวลา 10:30 น. ที่ เมกะบางนา

// พยานฝั่งผู้เช่า (ใช้เพื่อลงนามในสัญญาเช่า โดยใช้บุคคลที่โดยสารมาด้วยหรือบิดา มารดา ญาติพี่น้อง เพื่อน)

//เวลาเลือกได้ทีละ 10 นาที

// บังคับ 2 ไฟล์ บัตรประชาชนกับเซลฟี่