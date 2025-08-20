<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function index()
    {
        if (request()->ajax()) {
            $rentals = Rental::query();
            

            
            $dataTable = DataTables::of($rentals)
                ->addColumn('rental_days', function ($rental) {
                    if ($rental->start_date && $rental->end_date) {
                        $startDate = \Carbon\Carbon::parse($rental->start_date);
                        $endDate = \Carbon\Carbon::parse($rental->end_date);
                        $days = $startDate->diffInDays($endDate);
                        return $days;
                    }
                    return '-';
                })
                ->addColumn('employee', function ($rental) {
                    return '<span class="badge bg-secondary"><i class="fas fa-user me-1"></i>ป้อ</span>'; // หรือข้อมูลพนักงานจริง
                })
                ->addColumn('files', function ($rental) {
                    $files = [];
                    if ($rental->selfie_image) {
                        $files[] = '<a href="' . asset('storage/' . $rental->selfie_image) . '" target="_blank"  title="รูป Selfie">
                        <button class=" bg-primary text-white w-9 h-9 hover:bg-primary-dark">
                        <i class="fas fa-image me-1"></i>
                        </button>
                        </a>';
                    }
                    if ($rental->national_id_image) {
                        $files[] = '<a href="' . asset('storage/' . $rental->national_id_image) . '" target="_blank"  title="รูปบัตรประชาชน">
                        <button class=" bg-primary text-white w-9 h-9 hover:bg-primary-dark">
                        <i class="fas fa-image me-1"></i>
                        </button>
                        </a>';
                    }
                    if ($rental->driver_license_image) {
                        $files[] = '<a href="' . asset('storage/' . $rental->driver_license_image) . '" target="_blank"  title="ใบขับขี่">
                        <button class=" bg-primary text-white w-9 h-9 hover:bg-primary-dark">
                        <i class="fas fa-id-card me-1"></i>
                        </button>
                        </a>';
                    }
                    if (empty($files)) {
                        return '<span class="text-muted"><i class="fas fa-times-circle me-1"></i>ไม่มีไฟล์</span>';
                    }
                    return implode(' ', $files);
                })
                ->rawColumns(['files', 'rental_days', 'employee']);
            

            
            return $dataTable->make(true);
        }
        
        return view('rentals.index');
    }

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
                'car_id' => 'nullable|exists:cars,id',
                'car_license_plate' => 'nullable|string|max:20',
            ], [
                'national_id.regex' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลัก และหลักแรกต้องเป็น 1-8 เท่านั้น',
                'national_id.size' => 'เลขบัตรประชาชนต้องมี 13 หลักเท่านั้น',
                'phone.regex' => 'เบอร์โทรศัพท์ต้องขึ้นต้นด้วย 0 และมี 10 หลักเท่านั้น',
                'phone.size' => 'เบอร์โทรศัพท์ต้องมี 10 หลักเท่านั้น',
            ]);

            // ตรวจสอบการเช่ารถซ้ำกัน
            if ($request->car_id) {
                if (Rental::checkDuplicateRental(
                    $request->car_id, 
                    $request->start_date, 
                    $request->end_date
                )) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['car_id' => 'รถยนต์คันนี้ถูกเช่าในช่วงเวลาดังกล่าวแล้ว กรุณาเลือกรถคันอื่นหรือเปลี่ยนช่วงเวลา']);
                }
            }

            if ($request->car_license_plate) {
                if (Rental::checkDuplicateRentalByLicensePlate(
                    $request->car_license_plate, 
                    $request->start_date, 
                    $request->end_date
                )) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['car_license_plate' => 'รถยนต์ทะเบียนนี้ถูกเช่าในช่วงเวลาดังกล่าวแล้ว กรุณาเลือกรถคันอื่นหรือเปลี่ยนช่วงเวลา']);
                }
            }

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

            $rental = Rental::create($data);

            // Auto-sync ไปยัง Google Calendar
            try {
                $this->googleCalendarService->createRentalEvent($rental);
                Log::info('Rental auto-synced to Google Calendar', [
                    'rental_id' => $rental->id,
                    'customer' => $rental->full_name
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to auto-sync rental to Google Calendar', [
                    'rental_id' => $rental->id,
                    'error' => $e->getMessage()
                ]);
                // ไม่ต้องหยุดการทำงาน แค่ log error
            }

            return redirect()->route('rentals.create')->with('success', 'ข้อมูลการเช่ารถถูกบันทึกเรียบร้อยแล้ว');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors จะถูกจัดการโดย Laravel อัตโนมัติ
            throw $e;
        } catch (\Exception $e) {
            // จัดการ error อื่นๆ
            return redirect()->route('rentals.create')->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    public function edit(Rental $rental)
    {
        $cars = \App\Models\Car::all();
        
        // ดึงรายการรถที่พร้อมใช้งานในช่วงเวลาที่เลือก
        $availableCars = [];
        if ($rental->start_date && $rental->end_date) {
            $availableCars = Rental::getAvailableCars($rental->start_date, $rental->end_date);
        }
        
        return view('rentals.edit', compact('rental', 'cars', 'availableCars'));
    }

    public function update(Request $request, Rental $rental)
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
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:pending,using,success,cancel',
                'car_id' => 'nullable|exists:cars,id',
                'car_brand' => 'nullable|string|max:100',
                'car_license_plate' => 'nullable|string|max:20',
                'car_full_name' => 'nullable|string|max:100',
                'rent_price' => 'nullable|numeric|min:0',
                'insurance_price' => 'nullable|numeric|min:0',
                'owner_firstname' => 'nullable|string|max:100',
                'owner_lastname' => 'nullable|string|max:100',
                'owner_witness_firstname' => 'nullable|string|max:100',
                'owner_witness_lastname' => 'nullable|string|max:100',
                'write_address' => 'nullable|string|max:255',
                'selfie_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'national_id_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'driver_license_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'national_id.regex' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลัก และหลักแรกต้องเป็น 1-8 เท่านั้น',
                'national_id.size' => 'เลขบัตรประชาชนต้องมี 13 หลักเท่านั้น',
                'phone.regex' => 'เบอร์โทรศัพท์ต้องขึ้นต้นด้วย 0 และมี 10 หลักเท่านั้น',
                'phone.size' => 'เบอร์โทรศัพท์ต้องมี 10 หลักเท่านั้น',
            ]);

            // ตรวจสอบการเช่ารถซ้ำกัน (ไม่รวมการเช่าปัจจุบันที่กำลังแก้ไข)
            if ($request->car_id) {
                if (Rental::checkDuplicateRental(
                    $request->car_id, 
                    $request->start_date, 
                    $request->end_date,
                    $rental->id
                )) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['car_id' => 'รถยนต์คันนี้ถูกเช่าในช่วงเวลาดังกล่าวแล้ว กรุณาเลือกรถคันอื่นหรือเปลี่ยนช่วงเวลา']);
                }
            }

            if ($request->car_license_plate) {
                if (Rental::checkDuplicateRentalByLicensePlate(
                    $request->car_license_plate, 
                    $request->start_date, 
                    $request->end_date,
                    $rental->id
                )) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['car_license_plate' => 'รถยนต์ทะเบียนนี้ถูกเช่าในช่วงเวลาดังกล่าวแล้ว กรุณาเลือกรถคันอื่นหรือเปลี่ยนช่วงเวลา']);
                }
            }

            $data = $request->except(['selfie_image', 'national_id_image', 'driver_license_image']);

            // Handle file uploads
            if ($request->hasFile('selfie_image')) {
                // ลบไฟล์เก่า
                if ($rental->selfie_image) {
                    Storage::disk('public')->delete($rental->selfie_image);
                }
                $data['selfie_image'] = $request->file('selfie_image')->store('rentals/selfie', 'public');
            }

            if ($request->hasFile('national_id_image')) {
                // ลบไฟล์เก่า
                if ($rental->national_id_image) {
                    Storage::disk('public')->delete($rental->national_id_image);
                }
                $data['national_id_image'] = $request->file('national_id_image')->store('rentals/national_id', 'public');
            }

            if ($request->hasFile('driver_license_image')) {
                // ลบไฟล์เก่า
                if ($rental->driver_license_image) {
                    Storage::disk('public')->delete($rental->driver_license_image);
                }
                $data['driver_license_image'] = $request->file('driver_license_image')->store('rentals/driver_license', 'public');
            }

            $oldStatus = $rental->status;
            $rental->update($data);

            // Auto-sync ไปยัง Google Calendar เมื่อมีการเปลี่ยนแปลง
            try {
                if ($oldStatus !== $rental->status || $rental->google_calendar_event_id) {
                    // อัปเดต event ใน Google Calendar
                    $this->googleCalendarService->updateRentalEvent($rental);
                    Log::info('Rental auto-updated in Google Calendar', [
                        'rental_id' => $rental->id,
                        'customer' => $rental->full_name,
                        'old_status' => $oldStatus,
                        'new_status' => $rental->status
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to auto-update rental in Google Calendar', [
                    'rental_id' => $rental->id,
                    'error' => $e->getMessage()
                ]);
                // ไม่ต้องหยุดการทำงาน แค่ log error
            }

            return redirect()->route('rentals.index')->with('success', 'ข้อมูลการเช่ารถถูกอัปเดตเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage());
        }
    }

    public function destroy(Rental $rental)
    {
        try {
            // ลบไฟล์รูปภาพ
            if ($rental->selfie_image) {
                Storage::disk('public')->delete($rental->selfie_image);
            }
            if ($rental->national_id_image) {
                Storage::disk('public')->delete($rental->national_id_image);
            }
            if ($rental->driver_license_image) {
                Storage::disk('public')->delete($rental->driver_license_image);
            }

            // ลบ event จาก Google Calendar ก่อน
            try {
                if ($rental->google_calendar_event_id) {
                    $this->googleCalendarService->deleteRentalEvent($rental);
                    Log::info('Rental auto-deleted from Google Calendar', [
                        'rental_id' => $rental->id,
                        'customer' => $rental->full_name
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to auto-delete rental from Google Calendar', [
                    'rental_id' => $rental->id,
                    'error' => $e->getMessage()
                ]);
                // ไม่ต้องหยุดการทำงาน แค่ log error
            }

            $rental->delete();

            // ตรวจสอบว่าเป็น AJAX request หรือไม่
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ข้อมูลการเช่ารถถูกลบเรียบร้อยแล้ว'
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'ข้อมูลการเช่ารถถูกลบเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            // ตรวจสอบว่าเป็น AJAX request หรือไม่
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage());
        }
    }

    public function export()
    {
        $rentals = Rental::all();
        
        return DataTables::of($rentals)
            ->addColumn('rental_days', function ($rental) {
                $startDate = \Carbon\Carbon::parse($rental->start_date);
                $endDate = \Carbon\Carbon::parse($rental->end_date);
                return $startDate->diffInDays($endDate);
            })
            ->addColumn('created_at_formatted', function ($rental) {
                return \Carbon\Carbon::parse($rental->created_at)->format('d/m/Y H:i');
            })
            ->rawColumns([])
            ->make(true);
    }

    public function exportPdfSingle(Rental $rental)
    {
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // ส่งข้อมูลการเช่าตาม ID ที่ส่งเข้ามา

        // dd($rental);

        $name = $rental->full_name ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLength = 50; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $nameLength = mb_strlen($name, 'UTF-8');
        $dots = $totalLength - $nameLength;
        if ($dots < 0) $dots = 0;

        $nationalId = $rental->national_id ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthNationalId = 50; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $nationalIdLength = mb_strlen($nationalId, 'UTF-8');
        $dotsNationalId = $totalLengthNationalId - $nationalIdLength;
        if ($dotsNationalId < 0) $dotsNationalId = 0;

    
        $leftDotsName  = intdiv($dots, 2);
        $rightDotsName = $dots - $leftDotsName;

        $leftDotsNationalId  = intdiv($dotsNationalId, 2);
        $rightDotsNationalId = $dotsNationalId - $leftDotsNationalId;

        $address = $rental->address ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthAddress = 90; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $addressLength = mb_strlen($address, 'UTF-8');
        $dotsAddress = $totalLengthAddress - $addressLength;
        if ($dotsAddress < 0) $dotsAddress = 0;

        $leftDotsAddress  = intdiv($dotsAddress, 2);
        $rightDotsAddress = $dotsAddress - $leftDotsAddress;

        $phone = $rental->phone ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthPhone = 50; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $phoneLength = mb_strlen($phone, 'UTF-8');
        $dotsPhone = $totalLengthPhone - $phoneLength;
        if ($dotsPhone < 0) $dotsPhone = 0;

        $leftDotsPhone  = intdiv($dotsPhone, 2);
        $rightDotsPhone = $dotsPhone - $leftDotsPhone;

        $carBrand = $rental->car_full_name ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthCarBrand = 30; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $carBrandLength = mb_strlen($carBrand, 'UTF-8');
        $dotsCarBrand = $totalLengthCarBrand - $carBrandLength;
        if ($dotsCarBrand < 0) $dotsCarBrand = 0;

        $leftDotsCarBrand  = intdiv($dotsCarBrand, 2);
        $rightDotsCarBrand = $dotsCarBrand - $leftDotsCarBrand;

        $carLicensePlate = $rental->car_license_plate ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthCarLicensePlate = 36; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $carLicensePlateLength = mb_strlen($carLicensePlate, 'UTF-8');
        $dotsCarLicensePlate = $totalLengthCarLicensePlate - $carLicensePlateLength;
        if ($dotsCarLicensePlate < 0) $dotsCarLicensePlate = 0;

        $leftDotsCarLicensePlate  = intdiv($dotsCarLicensePlate, 2);
        $rightDotsCarLicensePlate = $dotsCarLicensePlate - $leftDotsCarLicensePlate;

        $rentPrice = $rental->rent_price ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthRentPrice = 50; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $rentPriceLength = mb_strlen($rentPrice, 'UTF-8');
        $dotsRentPrice = $totalLengthRentPrice - $rentPriceLength;
        if ($dotsRentPrice < 0) $dotsRentPrice = 0;
        
        $leftDotsRentPrice  = intdiv($dotsRentPrice, 2);
        $rightDotsRentPrice = $dotsRentPrice - $leftDotsRentPrice;

        $rentPriceText = $rental->rent_price_text ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthRentPriceText = 50; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $rentPriceTextLength = mb_strlen($rentPriceText, 'UTF-8');
        $dotsRentPriceText = $totalLengthRentPriceText - $rentPriceTextLength;
        if ($dotsRentPriceText < 0) $dotsRentPriceText = 0;
        
        $leftDotsRentPriceText  = intdiv($dotsRentPriceText, 2);
        $rightDotsRentPriceText = $dotsRentPriceText - $leftDotsRentPriceText;

        $startDate = $rental->start_date ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthStartDate = 60; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $startDateLength = mb_strlen($startDate, 'UTF-8');
        $dotsStartDate = $totalLengthStartDate - $startDateLength;
        if ($dotsStartDate < 0) $dotsStartDate = 0;
        
        $leftDotsStartDate  = intdiv($dotsStartDate, 2);
        $rightDotsStartDate = $dotsStartDate - $leftDotsStartDate;

        $endDate = $rental->end_date ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthEndDate = 50; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $endDateLength = mb_strlen($endDate, 'UTF-8');
        $dotsEndDate = $totalLengthEndDate - $endDateLength;
        if ($dotsEndDate < 0) $dotsEndDate = 0;
        
        $leftDotsEndDate  = intdiv($dotsEndDate, 2);
        $rightDotsEndDate = $dotsEndDate - $leftDotsEndDate;


        // insurancePrice
        $insurancePrice = $rental->insurance_price ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthInsurancePrice = 14; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $insurancePriceLength = mb_strlen($insurancePrice, 'UTF-8');
        $dotsInsurancePrice = $totalLengthInsurancePrice - $insurancePriceLength;
        if ($dotsInsurancePrice < 0) $dotsInsurancePrice = 0;

        $leftDotsInsurancePrice  = intdiv($dotsInsurancePrice, 2);
        $rightDotsInsurancePrice = $dotsInsurancePrice - $leftDotsInsurancePrice;

        // insurancePriceText
        $insurancePriceText = $rental->insurance_price_text ?? ''; // สมมติว่ามีชื่อใน $user->name
        $totalLengthInsurancePriceText = 34; // ความยาวรวมของช่องชื่อ (จำนวนจุด+ชื่อ)
    
        $insurancePriceTextLength = mb_strlen($insurancePriceText, 'UTF-8');
        $dotsInsurancePriceText = $totalLengthInsurancePriceText - $insurancePriceTextLength;
        if ($dotsInsurancePriceText < 0) $dotsInsurancePriceText = 0;

        $leftDotsInsurancePriceText  = intdiv($dotsInsurancePriceText, 2);
        $rightDotsInsurancePriceText = $dotsInsurancePriceText - $leftDotsInsurancePriceText;

        $logoImage = public_path('images/logo.jpg');
        $selfieImage = storage_path('app/public/' . str_replace('storage/', '', $rental->selfie_image));
        $nationalIdImage = storage_path('app/public/' . str_replace('storage/', '', $rental->national_id_image));
        $driverLicenseImage = storage_path('app/public/' . str_replace('storage/', '', $rental->driver_license_image));

        $data = [
            'rental' => $rental,
            'leftDotsName' => $leftDotsName,
            'rightDotsName' => $rightDotsName,
            'leftDotsNationalId' => $leftDotsNationalId,
            'rightDotsNationalId' => $rightDotsNationalId,
            'leftDotsAddress' => $leftDotsAddress,
            'rightDotsAddress' => $rightDotsAddress,
            'leftDotsPhone' => $leftDotsPhone,
            'rightDotsPhone' => $rightDotsPhone,
            'leftDotsCarBrand' => $leftDotsCarBrand,
            'rightDotsCarBrand' => $rightDotsCarBrand,
            'leftDotsCarLicensePlate' => $leftDotsCarLicensePlate,
            'rightDotsCarLicensePlate' => $rightDotsCarLicensePlate,
            'leftDotsRentPrice' => $leftDotsRentPrice,
            'rightDotsRentPrice' => $rightDotsRentPrice,
            'leftDotsRentPriceText' => $leftDotsRentPriceText,
            'rightDotsRentPriceText' => $rightDotsRentPriceText,
            'leftDotsStartDate' => $leftDotsStartDate,
            'rightDotsStartDate' => $rightDotsStartDate,
            'leftDotsEndDate' => $leftDotsEndDate,
            'rightDotsEndDate' => $rightDotsEndDate,
            'leftDotsInsurancePrice' => $leftDotsInsurancePrice,
            'rightDotsInsurancePrice' => $rightDotsInsurancePrice,
            'leftDotsInsurancePriceText' => $leftDotsInsurancePriceText,
            'rightDotsInsurancePriceText' => $rightDotsInsurancePriceText,
            'selfieImage' => $selfieImage,
            'nationalIdImage' => $nationalIdImage,
            'driverLicenseImage' => $driverLicenseImage,
            'logoImage' => $logoImage,
        ];
        
        $html = view('pdf.old', $data)->render();

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'thsarabun' => [
                    'R'  => 'THSarabun.ttf',
                    'B'  => 'THSarabun Bold.ttf',
                    'I'  => 'THSarabun Italic.ttf',
                    'BI' => 'THSarabun Bold Italic.ttf',
                ]
            ],
            'default_font' => 'thsarabun'
        ]);
        
        $mpdf->SetDefaultFont('Sarabun');
        $mpdf->WriteHTML($html);
        
        // ตั้งชื่อไฟล์ตามข้อมูลการเช่า
        $filename = 'car-rental-agreement-' . $rental->id . '-' . $rental->full_name . '.pdf';
        $filename = str_replace(' ', '-', $filename); // แทนที่ช่องว่างด้วย -
        
        return $mpdf->Output($filename, 'D');
    }

    // เพิ่ม method สำหรับตรวจสอบรถที่พร้อมใช้งานแบบ AJAX
    public function checkAvailableCars(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // ดึงรายการรถที่พร้อมใช้งาน
        $availableCars = Rental::getAvailableCars($startDate, $endDate);
        
        // ดึงรายการรถที่ถูกเช่าในช่วงเวลาดังกล่าว
        $rentedCars = Rental::getRentedCars($startDate, $endDate);

        return response()->json([
            'available_cars' => $availableCars,
            'rented_cars' => $rentedCars,
            'message' => 'พบรถที่พร้อมใช้งาน ' . $availableCars->count() . ' คัน'
        ]);
    }

    // เพิ่ม method สำหรับตรวจสอบการซ้ำกันแบบ AJAX
    public function checkDuplicateRentalAjax(Request $request)
    {
        $request->validate([
            'car_id' => 'nullable|exists:cars,id',
            'car_license_plate' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'rental_id' => 'nullable|exists:rentals,id', // สำหรับการแก้ไข
        ]);

        $isDuplicate = false;
        $message = '';

        if ($request->car_id) {
            $isDuplicate = Rental::checkDuplicateRental(
                $request->car_id,
                $request->start_date,
                $request->end_date,
                $request->rental_id
            );
            
            if ($isDuplicate) {
                $message = 'รถยนต์คันนี้ถูกเช่าในช่วงเวลาดังกล่าวแล้ว';
            }
        }

        if (!$isDuplicate && $request->car_license_plate) {
            $isDuplicate = Rental::checkDuplicateRentalByLicensePlate(
                $request->car_license_plate,
                $request->start_date,
                $request->end_date,
                $request->rental_id
            );
            
            if ($isDuplicate) {
                $message = 'รถยนต์ทะเบียนนี้ถูกเช่าในช่วงเวลาดังกล่าวแล้ว';
            }
        }

        return response()->json([
            'is_duplicate' => $isDuplicate,
            'message' => $message
        ]);
    }
}
