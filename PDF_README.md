# ระบบสร้าง PDF รองรับภาษาไทยด้วย mPDF ใน Laravel

## ภาพรวม

ระบบนี้ใช้ mPDF library สำหรับการสร้างไฟล์ PDF ที่รองรับภาษาไทยและภาษาอื่นๆ ที่ใช้ Unicode ได้อย่างสมบูรณ์

## คุณสมบัติหลัก

-   ✅ รองรับภาษาไทยและตัวอักษรพิเศษ
-   ✅ ใช้ CSS ได้เกือบครบถ้วน (Flexbox, Grid, Animation)
-   ✅ สร้างตารางที่ซับซ้อนได้
-   ✅ รองรับการใส่รูปภาพ
-   ✅ กำหนดขนาดกระดาษได้หลายแบบ (A4, Letter, Legal)
-   ✅ รองรับการจัดวางแนวตั้งและแนวนอน
-   ✅ ประสิทธิภาพสูง ใช้หน่วยความจำน้อย

## การติดตั้ง

### 1. ติดตั้ง mPDF ผ่าน Composer

```bash
composer require mpdf/mpdf
```

### 2. สร้างโฟลเดอร์ temp

```bash
mkdir -p storage/app/temp
```

## การใช้งาน

### 1. หน้าเว็บทดสอบ

เข้าที่: `/pdf-demo`

### 2. สร้าง PDF แบบง่าย

```php
// ใน Controller
use Mpdf\Mpdf;

public function generateSimplePDF()
{
    $data = [
        'title' => 'ทดสอบการสร้าง PDF ด้วยภาษาไทย',
        'content' => 'เนื้อหาภาษาไทย...',
        'features' => ['คุณสมบัติ 1', 'คุณสมบัติ 2'],
        'date' => now()->format('d/m/Y H:i')
    ];

    $html = view('pdf.simple-template', $data)->render();

    $mpdf = new Mpdf([
        'format' => 'A4',
        'orientation' => 'P',
        'default_font' => 'dejavusans',
        'mode' => 'utf-8',
    ]);

    $mpdf->SetDefaultFont('dejavusans');
    $mpdf->WriteHTML($html);

    return $mpdf->Output('test-thai-pdf.pdf', 'D');
}
```

### 3. สร้างรายงานการเช่ารถ

```php
public function generateRentalReport(Request $request)
{
    // ดึงข้อมูลการเช่ารถ
    $rentals = Rental::query()
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();

    // สร้างข้อมูลสรุป
    $summary = [
        'total' => $rentals->count(),
        'completed' => $rentals->where('status', 'completed')->count(),
        'pending' => $rentals->where('status', 'pending')->count(),
        'total_revenue' => $rentals->sum('price')
    ];

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

    $html = view('pdf.thai-template', $data)->render();

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

    $mpdf->SetDefaultFont('dejavusans');
    $mpdf->WriteHTML($html);

    $filename = 'rental-report-' . date('Y-m-d-H-i-s') . '.pdf';
    return $mpdf->Output($filename, 'D');
}
```

### 4. สร้างใบเสร็จ

```php
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
```

## Routes ที่มีให้

### Routes สำหรับการทดสอบ (ไม่ต้อง login)

-   `GET /pdf/test` - สร้าง PDF ทดสอบพื้นฐาน
-   `GET /pdf/rental-report` - สร้างรายงานการเช่ารถ
-   `GET /pdf/monthly-report` - สร้างรายงานประจำเดือน
-   `GET /pdf-demo` - หน้าเว็บทดสอบ

### Routes ที่ต้อง login

-   `GET /pdf/receipt/{rental}` - สร้างใบเสร็จการเช่ารถ

## Template ที่มีให้

### 1. simple-template.blade.php

-   สำหรับทดสอบพื้นฐาน
-   แสดงคุณสมบัติของ mPDF
-   มีตารางตัวอย่าง

### 2. thai-template.blade.php

-   สำหรับรายงานการเช่ารถ
-   แสดงข้อมูลสรุปและตารางรายการ
-   รองรับข้อมูลแบบมีเงื่อนไข

### 3. receipt-template.blade.php

-   สำหรับใบเสร็จการเช่ารถ
-   แสดงข้อมูลบริษัทและรายละเอียดการเช่า
-   มีส่วนลายเซ็น

### 4. monthly-report-template.blade.php

-   สำหรับรายงานประจำเดือน
-   แสดงสถิติรายวันและสรุปข้อมูล
-   รองรับการวิเคราะห์ข้อมูล

## การตั้งค่า mPDF

### ตัวเลือกสำคัญ

```php
$mpdf = new Mpdf([
    'format' => 'A4',                    // ขนาดกระดาษ
    'orientation' => 'P',                // P = แนวตั้ง, L = แนวนอน
    'margin_left' => 15,                 // ระยะขอบซ้าย (mm)
    'margin_right' => 15,                // ระยะขอบขวา (mm)
    'margin_top' => 15,                  // ระยะขอบบน (mm)
    'margin_bottom' => 15,               // ระยะขอบล่าง (mm)
    'default_font' => 'dejavusans',      // ฟอนต์เริ่มต้น (รองรับไทย)
    'mode' => 'utf-8',                   // โหมดการเข้ารหัส
    'tempDir' => storage_path('app/temp'), // โฟลเดอร์ไฟล์ชั่วคราว
]);
```

### ฟอนต์ที่รองรับภาษาไทย

-   `dejavusans` - ฟอนต์หลักที่แนะนำ
-   `sarabun` - ฟอนต์ไทย (ต้องติดตั้งเพิ่ม)
-   `notosans` - ฟอนต์ Google (ต้องติดตั้งเพิ่ม)

## การจัดการข้อผิดพลาด

### 1. ตรวจสอบการติดตั้ง mPDF

```php
try {
    $mpdf = new Mpdf();
} catch (Exception $e) {
    return response()->json(['error' => 'ไม่สามารถสร้าง PDF ได้: ' . $e->getMessage()], 500);
}
```

### 2. ตรวจสอบสิทธิ์การเขียนไฟล์

```php
if (!is_writable(storage_path('app/temp'))) {
    return response()->json(['error' => 'ไม่มีสิทธิ์เขียนไฟล์ในโฟลเดอร์ temp'], 500);
}
```

### 3. ตรวจสอบหน่วยความจำ

```php
if (memory_get_usage() > 128 * 1024 * 1024) { // 128MB
    return response()->json(['error' => 'หน่วยความจำไม่เพียงพอ'], 500);
}
```

## เคล็ดลับการใช้งาน

### 1. การเพิ่มรูปภาพ

```html
<img
    src="{{ public_path('images/logo.jpg') }}"
    alt="โลโก้"
    style="width: 100px;"
/>
```

### 2. การสร้างตารางที่ซับซ้อน

```html
<table class="table">
    <thead>
        <tr>
            <th>หัวข้อ 1</th>
            <th>หัวข้อ 2</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->field1 }}</td>
            <td>{{ $item->field2 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
```

### 3. การใช้ CSS แบบ advanced

```css
.table {
    width: 100%;
    border-collapse: collapse;
    margin: 15px 0;
}

.table th,
.table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.table tr:nth-child(even) {
    background-color: #f9f9f9;
}
```

### 4. การจัดการหน้าใหม่

```html
<div style="page-break-before: always;"></div>
<h2>หน้าใหม่</h2>
```

## การแก้ไขปัญหา

### 1. ภาษาไทยแสดงเป็นเครื่องหมายคำถาม

-   ตรวจสอบว่าใช้ `dejavusans` font
-   ตรวจสอบว่าใช้ `mode => 'utf-8'`
-   ตรวจสอบว่าไฟล์ template ใช้ `charset="UTF-8"`

### 2. CSS ไม่ทำงาน

-   ใช้ inline CSS แทน external CSS
-   ตรวจสอบว่า CSS syntax ถูกต้อง
-   หลีกเลี่ยง CSS ที่ซับซ้อนเกินไป

### 3. ไฟล์ PDF ไม่สร้าง

-   ตรวจสอบสิทธิ์การเขียนไฟล์
-   ตรวจสอบหน่วยความจำ
-   ตรวจสอบ error log

### 4. ตารางแสดงผลผิด

-   ใช้ `border-collapse: collapse`
-   กำหนด `width: 100%`
-   หลีกเลี่ยง CSS ที่ซับซ้อน

## การพัฒนาต่อ

### 1. เพิ่มฟอนต์ไทย

```php
// ติดตั้งฟอนต์ Sarabun
$mpdf->AddFont('Sarabun', '', 'Sarabun-Regular.ttf');
$mpdf->AddFont('Sarabun', 'B', 'Sarabun-Bold.ttf');
```

### 2. เพิ่มลายน้ำ

```php
$mpdf->SetWatermarkText('DRAFT');
$mpdf->showWatermarkText = true;
```

### 3. เพิ่มหัวกระดาษ/ท้ายกระดาษ

```php
$mpdf->SetHeader('รายงานการเช่ารถ||{PAGENO}');
$mpdf->SetFooter('สร้างเมื่อ: ' . date('d/m/Y H:i'));
```

### 4. เพิ่มการป้องกัน

```php
$mpdf->SetProtection(['print', 'copy'], '', 'password123');
```

## สรุป

ระบบสร้าง PDF ด้วย mPDF ใน Laravel เป็นเครื่องมือที่มีประสิทธิภาพสูงสำหรับการสร้างเอกสารที่รองรับภาษาไทย สามารถใช้สร้างรายงาน ใบเสร็จ และเอกสารต่างๆ ได้อย่างสวยงามและเป็นมืออาชีพ
