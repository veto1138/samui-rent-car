<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CarController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $cars = Car::query();
            
            $dataTable = DataTables::of($cars)
                ->addColumn('image_preview', function ($car) {
                    if ($car->image) {
                        return '<img src="' . asset('storage/' . $car->image) . '" alt="รูปรถ" class="w-16 h-16 object-cover rounded-lg">';
                    }
                    return '<div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center"><i class="fas fa-car text-gray-400 text-xl"></i></div>';
                })
                ->addColumn('status_badge', function ($car) {
                    $statusClasses = [
                        'available' => 'bg-green-100 text-green-800',
                        'rented' => 'bg-blue-100 text-blue-800',
                        'maintenance' => 'bg-yellow-100 text-yellow-800'
                    ];
                    
                    $statusTexts = [
                        'available' => 'พร้อมใช้งาน',
                        'rented' => 'ถูกเช่า',
                        'maintenance' => 'ซ่อมบำรุง'
                    ];
                    
                    $class = $statusClasses[$car->status] ?? 'bg-gray-100 text-gray-800';
                    $text = $statusTexts[$car->status] ?? 'ไม่ทราบสถานะ';
                    
                    return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $class . '">' . $text . '</span>';
                })
                ->rawColumns(['image_preview', 'status_badge']);
            
            return $dataTable->make(true);
        }
        
        return view('cars.index');
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:100',
                'brand_name' => 'required|string|max:100',
                'license_plate' => 'required|string|max:100|unique:cars',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:available,rented,maintenance',
            ], [
                'full_name.required' => 'กรุณากรอกชื่อรถยนต์',
                'brand_name.required' => 'กรุณากรอกชื่อรุ่นรถ',
                'license_plate.required' => 'กรุณากรอกทะเบียนรถ',
                'license_plate.unique' => 'ทะเบียนรถนี้มีอยู่ในระบบแล้ว',
                'status.required' => 'กรุณาเลือกสถานะรถ',
            ]);

            $data = $request->except(['image']);

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('cars', 'public');
            }

            Car::create($data);

            return redirect()->route('cars.index')->with('success', 'ข้อมูลรถยนต์ถูกบันทึกเรียบร้อยแล้ว');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        try {
            $request->validate([
                'full_name' => 'required|string|max:100',
                'brand_name' => 'required|string|max:100',
                'license_plate' => 'required|string|max:100|unique:cars,license_plate,' . $car->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|in:available,rented,maintenance',
            ], [
                'full_name.required' => 'กรุณากรอกชื่อรถยนต์',
                'brand_name.required' => 'กรุณากรอกชื่อรุ่นรถ',
                'license_plate.required' => 'กรุณากรอกทะเบียนรถ',
                'license_plate.unique' => 'ทะเบียนรถนี้มีอยู่ในระบบแล้ว',
                'status.required' => 'กรุณาเลือกสถานะรถ',
            ]);

            $data = $request->except(['image']);

            // Handle image upload
            if ($request->hasFile('image')) {
                // ลบไฟล์เก่า
                if ($car->image) {
                    Storage::disk('public')->delete($car->image);
                }
                $data['image'] = $request->file('image')->store('cars', 'public');
            }

            $car->update($data);

            return redirect()->route('cars.index')->with('success', 'ข้อมูลรถยนต์ถูกอัปเดตเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage());
        }
    }

    public function destroy(Car $car)
    {
        try {
            // ลบไฟล์รูปภาพ
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }

            $car->delete();

            return redirect()->route('cars.index')->with('success', 'ข้อมูลรถยนต์ถูกลบเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }

    public function show(Car $car)
    {
        return view('cars.show', compact('car'));
    }
}
