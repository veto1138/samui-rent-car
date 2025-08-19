<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;

class RentalController extends Controller
{
    public function create()
    {
        return view('rentals.create');
    }

    public function store(Request $request)
    {
        try {
                    $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'national_id' => 'required|string|regex:/^[1-8][0-9]{12}$/|size:13',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/|size:10',
            'address' => 'required|string|max:100',
            'witness_firstname' => 'required|string|max:100',
            'witness_lastname' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_location' => 'required|string|max:150',
            'end_location' => 'required|string|max:150',
            'selfie_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'national_id_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'driver_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'national_id.regex' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลัก และหลักแรกต้องเป็น 1-8 เท่านั้น',
            'national_id.size' => 'เลขบัตรประชาชนต้องมี 13 หลักเท่านั้น',
            'phone.regex' => 'เบอร์โทรศัพท์ต้องขึ้นต้นด้วย 0 และมี 10 หลักเท่านั้น',
            'phone.size' => 'เบอร์โทรศัพท์ต้องมี 10 หลักเท่านั้น',
        ]);

            $data = $request->all();
            $data['status'] = 'pending';

            // Handle file uploads
            if ($request->hasFile('selfie_image')) {
                $data['selfie_image'] = $request->file('selfie_image')->store('rentals/selfie', 'public');
            }

            if ($request->hasFile('national_id_image')) {
                $data['national_id_image'] = $request->file('national_id_image')->store('rentals/national_id', 'public');
            }

            if ($request->hasFile('driver_license_image')) {
                $data['driver_license_image'] = $request->file('driver_license_image')->store('rentals/driver_license', 'public');
            }

            Rental::create($data);

            return redirect()->route('rentals.create')->with('success', 'ข้อมูลการเช่ารถถูกบันทึกเรียบร้อยแล้ว');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors จะถูกจัดการโดย Laravel อัตโนมัติ
            throw $e;
        } catch (\Exception $e) {
            // จัดการ error อื่นๆ
            return redirect()->route('rentals.create')->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }
}
