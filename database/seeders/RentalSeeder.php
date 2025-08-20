<?php

namespace Database\Seeders;

use App\Models\Rental;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลการเช่ารถตัวอย่าง
        Rental::create([
            'firstname' => 'สมชาย',
            'lastname' => 'ใจดี',
            'national_id' => '1234567890123',
            'phone' => '0812345678',
            'address' => '123 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
            'witness_firstname' => 'สมหญิง',
            'witness_lastname' => 'รักดี',
            'rent_price' => 1500.00,
            'insurance_price' => 500.00,
            'start_date' => Carbon::now()->addDays(1),
            'end_date' => Carbon::now()->addDays(3),
            'start_location' => 'สนามบินสุวรรณภูมิ',
            'end_location' => 'หาดพัทยา',
            'car_brand' => 'Toyota',
            'car_license_plate' => 'กข-1234',
            'status' => 'pending',
            'write_address' => '123 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110',
            'owner_firstname' => 'สมศักดิ์',
            'owner_lastname' => 'เจ้าของ',
            'owner_witness_firstname' => 'สมศรี',
            'owner_witness_lastname' => 'พยานเจ้าของ',
        ]);

        Rental::create([
            'firstname' => 'นางสาว',
            'lastname' => 'สวยงาม',
            'national_id' => '2345678901234',
            'phone' => '0823456789',
            'address' => '456 ถนนรัชดาภิเษก แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
            'witness_firstname' => 'นาง',
            'witness_lastname' => 'รักลูก',
            'rent_price' => 2000.00,
            'insurance_price' => 800.00,
            'start_date' => Carbon::now()->addDays(2),
            'end_date' => Carbon::now()->addDays(5),
            'start_location' => 'ห้างสรรพสินค้าเซ็นทรัลเวิลด์',
            'end_location' => 'หาดหัวหิน',
            'car_brand' => 'Honda',
            'car_license_plate' => 'ขค-5678',
            'status' => 'using',
            'write_address' => '456 ถนนรัชดาภิเษก แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
        ]);

        Rental::create([
            'firstname' => 'นาย',
            'lastname' => 'นักธุรกิจ',
            'national_id' => '3456789012345',
            'phone' => '0834567890',
            'address' => '789 ถนนเพชรบุรี แขวงทุ่งพญาไท เขตราชเทวี กรุงเทพฯ 10400',
            'witness_firstname' => 'นางสาว',
            'witness_lastname' => 'เพื่อนรัก',
            'rent_price' => 3000.00,
            'insurance_price' => 1000.00,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->subDays(2),
            'start_location' => 'โรงแรมดุสิตธานี',
            'end_location' => 'สนามบินดอนเมือง',
            'car_brand' => 'BMW',
            'car_license_plate' => 'คง-9012',
            'status' => 'success',
            'write_address' => '789 ถนนเพชรบุรี แขวงทุ่งพญาไท เขตราชเทวี กรุงเทพฯ 10400',
        ]);
    }
}
