<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;


class MPDFController extends Controller 
{
    /**
     * สร้าง PDF รายงานการเช่ารถ
     */
    public function generateRentalReport(Request $request)
    {
        // รับพารามิเตอร์จาก request
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status', '');
        
        // ดึงข้อมูลการเช่ารถ
        $query = Rental::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $rentals = $query->orderBy('created_at', 'desc')->get();
        
        // สร้างข้อมูลสรุป
        $summary = [
            'total' => $rentals->count(),
            'completed' => $rentals->where('status', 'completed')->count(),
            'pending' => $rentals->where('status', 'pending')->count(),
            'total_revenue' => $rentals->sum('price')
        ];
        
        // ข้อมูลสำหรับ template
        $data = [
            'title' => 'รายงานการเช่ารถ',
            'date' => now()->format('d/m/Y H:i'),
            'rentals' => $rentals,
            'summary' => $summary,
            'description' => "รายงานการเช่ารถระหว่างวันที่ {$startDate} ถึง {$endDate}",
            'notes' => [
                'รายงานนี้แสดงข้อมูลการเช่ารถทั้งหมดในระบบ',
                'ข้อมูลถูกอัปเดตล่าสุดเมื่อ ' . now()->format('d/m/Y H:i'),
                'หากมีข้อสงสัย กรุณาติดต่อผู้ดูแลระบบ'
            ]
        ];
        
        // สร้าง HTML
        $html = view('pdf.thai-template', $data)->render();
        
        // สร้าง mPDF instance
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'default_font' => 'dejavusans',
            'mode' => 'utf-8',
            'tempDir' => storage_path('app/temp'),
        ]);
        
        // ตั้งค่า font สำหรับภาษาไทย
        $mpdf->SetDefaultFont('dejavusans');
        
        // เขียน HTML ลงใน PDF
        $mpdf->WriteHTML($html);
        
        // ส่งไฟล์ PDF กลับ
        $filename = 'rental-report-' . date('Y-m-d-H-i-s') . '.pdf';
        return $mpdf->Output($filename, 'D');
    }
    
    /**
     * สร้าง PDF ใบเสร็จการเช่ารถ
     */
    public function generateRentalReceipt($rentalId)
    {
        $rental = Rental::findOrFail($rentalId);
        
        $data = [
            'title' => 'ใบเสร็จการเช่ารถ',
            'date' => now()->format('d/m/Y H:i'),
            'rental' => $rental,
            'receipt_number' => 'RCP-' . str_pad($rental->id, 6, '0', STR_PAD_LEFT),
            'company_info' => [
                'name' => 'บริษัท สมุย รีสอร์ท แอนด์ คาร์ เรนท์ จำกัด',
                'address' => '123 ถนนชายหาดเฉวง ตำบลเฉวง อำเภอเกาะสมุย จังหวัดสุราษฎร์ธานี 84320',
                'phone' => '077-123-456',
                'email' => 'info@samui-rent-car.com'
            ]
        ];
        
        $html = view('pdf.receipt-template', $data)->render();
        
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'default_font' => 'dejavusans',
            'mode' => 'utf-8',
        ]);
        
        $mpdf->SetDefaultFont('dejavusans');
        $mpdf->WriteHTML($html);
        
        $filename = 'receipt-' . $rental->id . '-' . date('Y-m-d') . '.pdf';
        return $mpdf->Output($filename, 'D');
    }
    
    /**
     * สร้าง PDF รายงานสรุปประจำเดือน
     */
    public function generateMonthlyReport(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        
        // ดึงข้อมูลสรุปประจำเดือน
        $monthlyData = DB::table('rentals')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_rentals,
                SUM(price) as daily_revenue,
                COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_rentals
            ')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $totalRevenue = $monthlyData->sum('daily_revenue');
        $totalRentals = $monthlyData->sum('total_rentals');
        $completedRentals = $monthlyData->sum('completed_rentals');
        
        $data = [
            'title' => "รายงานสรุปประจำเดือน {$month}",
            'date' => now()->format('d/m/Y H:i'),
            'month' => $month,
            'monthlyData' => $monthlyData,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_rentals' => $totalRentals,
                'completed_rentals' => $completedRentals,
                'average_daily_revenue' => $monthlyData->count() > 0 ? $totalRevenue / $monthlyData->count() : 0
            ],
            'description' => "รายงานสรุปการดำเนินงานประจำเดือน {$month}",
            'notes' => [
                'รายงานนี้แสดงข้อมูลสรุปการเช่ารถประจำเดือน',
                'ข้อมูลถูกคำนวณจากฐานข้อมูลในระบบ',
                'รายได้รวม: ' . number_format($totalRevenue, 2) . ' บาท'
            ]
        ];
        
        $html = view('pdf.monthly-report-template', $data)->render();
        
        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'L', // แนวนอนสำหรับตารางข้อมูล
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'default_font' => 'dejavusans',
            'mode' => 'utf-8',
        ]);
        
        $mpdf->SetDefaultFont('dejavusans');
        $mpdf->WriteHTML($html);
        
        $filename = "monthly-report-{$month}.pdf";
        return $mpdf->Output($filename, 'D');
    }
    
    /**
     * สร้าง PDF แบบง่ายสำหรับทดสอบ
     */
    public function generateSimplePDF()
    {

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $data = [
            'title' => 'ทดสอบการสร้าง PDF ด้วยภาษาไทย',
            'content' => 'นี่คือตัวอย่างการสร้าง PDF ที่รองรับภาษาไทยใน Laravel โดยใช้ mPDF',
            'features' => [
                'รองรับภาษาไทยและตัวอักษรพิเศษ',
                'สามารถใช้ CSS ได้',
                'รองรับการสร้างตาราง',
                'รองรับการใส่รูปภาพ',
                'สามารถกำหนดขนาดกระดาษได้'
            ],
            'date' => now()->format('d/m/Y H:i')
        ];
        
        $html = view('pdf.simple-template', $data)->render();
        
        // $mpdf = new Mpdf([
        //     'format' => 'A4',
        //     'orientation' => 'P',
        //     'margin_left' => 20,
        //     'margin_right' => 20,
        //     'margin_top' => 20,
        //     'margin_bottom' => 20,
        //     'default_font' => 'Sarabun',
        //     'mode' => 'utf-8',
        // ]);
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
        
        return $mpdf->Output('test-thai-pdf.pdf', 'D');
    }
}
