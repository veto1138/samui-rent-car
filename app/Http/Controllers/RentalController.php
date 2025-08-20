<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class RentalController extends Controller
{
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

    public function edit(Rental $rental)
    {
        return view('rentals.edit', compact('rental'));
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
                'start_location' => 'required|string|max:150',
                'end_location' => 'required|string|max:150',
                'status' => 'required|in:pending,using,success,cancel',
                'car_brand' => 'nullable|string|max:100',
                'car_license_plate' => 'nullable|string|max:20',
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

            $rental->update($data);

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

            $rental->delete();

            return redirect()->route('dashboard')->with('success', 'ข้อมูลการเช่ารถถูกลบเรียบร้อยแล้ว');

        } catch (\Exception $e) {
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

        // dd($rental);
        // ส่งข้อมูลการเช่าตาม ID ที่ส่งเข้ามา
        $data = [
            'rental' => $rental
        ];

        // ตรวจสอบข้อมูลและ accessor
        // dd([
        //     'rental_id' => $rental->id,
        //     'start_date' => $rental->start_date,
        //     'end_date' => $rental->end_date,
        //     'start_time' => $rental->start_time,
        //     'end_time' => $rental->end_time,
        //     'thai_start_date' => $rental->thai_start_date,
        //     'thai_end_date' => $rental->thai_end_date,
        //     'formatted_start_date' => $rental->formatted_start_date,
        //     'formatted_end_date' => $rental->formatted_end_date,
        // ]);
        
        $html = view('pdf.rental-report', $data)->render();

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
            'fontdata' => $fontData + [
                'thsarabun' => [
                    'R'  => 'THSarabunNew.ttf',
                    'B'  => 'THSarabunNew-Bold.ttf',
                    'I'  => 'THSarabunNew-Italic.ttf',
                    'BI' => 'THSarabunNew-BoldItalic.ttf',
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
}
