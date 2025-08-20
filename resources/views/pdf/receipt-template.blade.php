<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Sarabun', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.4;
        }

        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
        }

        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .receipt-details {
            flex: 1;
        }

        .receipt-number {
            font-size: 14px;
            font-weight: bold;
            color: #e74c3c;
        }

        .date-info {
            font-size: 12px;
            color: #7f8c8d;
        }

        .rental-details {
            margin: 30px 0;
        }

        .detail-row {
            display: flex;
            margin: 10px 0;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 10px;
        }

        .detail-label {
            width: 120px;
            font-weight: bold;
            color: #34495e;
        }

        .detail-value {
            flex: 1;
            color: #2c3e50;
        }

        .price-section {
            margin: 30px 0;
            text-align: right;
        }

        .total-price {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
            border-top: 2px solid #e74c3c;
            padding-top: 10px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature-section {
            margin: 40px 0;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            padding-top: 5px;
        }

        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ $company_info['name'] }}</div>
            <div class="company-details">
                {{ $company_info['address'] }}<br>
                โทร: {{ $company_info['phone'] }} | อีเมล: {{ $company_info['email'] }}
            </div>
        </div>

        <div class="receipt-title">{{ $title }}</div>

        <div class="receipt-info">
            <div class="receipt-details">
                <div class="receipt-number">เลขที่: {{ $receipt_number }}</div>
                <div class="date-info">วันที่: {{ $date }}</div>
            </div>
        </div>
    </div>

    <div class="rental-details">
        <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
            รายละเอียดการเช่ารถ
        </h3>

        <div class="detail-row">
            <div class="detail-label">ชื่อลูกค้า:</div>
            <div class="detail-value">{{ $rental->customer_name ?? 'ไม่ระบุ' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">รุ่นรถ:</div>
            <div class="detail-value">{{ $rental->car_model ?? 'ไม่ระบุ' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">เลขทะเบียน:</div>
            <div class="detail-value">{{ $rental->license_plate ?? 'ไม่ระบุ' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">วันที่เช่า:</div>
            <div class="detail-value">{{ $rental->rental_date ?? 'ไม่ระบุ' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">วันที่คืน:</div>
            <div class="detail-value">{{ $rental->return_date ?? 'ไม่ระบุ' }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">จำนวนวัน:</div>
            <div class="detail-value">
                @if ($rental->rental_date && $rental->return_date)
                    {{ \Carbon\Carbon::parse($rental->rental_date)->diffInDays(\Carbon\Carbon::parse($rental->return_date)) }}
                    วัน
                @else
                    ไม่ระบุ
                @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">สถานะ:</div>
            <div class="detail-value">
                @if (($rental->status ?? '') == 'completed')
                    <span style="color: #27ae60; font-weight: bold;">เสร็จสิ้น</span>
                @elseif(($rental->status ?? '') == 'pending')
                    <span style="color: #f39c12; font-weight: bold;">รอดำเนินการ</span>
                @else
                    {{ $rental->status ?? 'ไม่ระบุ' }}
                @endif
            </div>
        </div>
    </div>

    <div class="price-section">
        <div class="total-price">
            ราคารวม: {{ number_format($rental->price ?? 0, 2) }} บาท
        </div>
    </div>

    <div class="highlight">
        <strong>หมายเหตุ:</strong><br>
        • กรุณาเก็บใบเสร็จนี้ไว้เป็นหลักฐาน<br>
        • การชำระเงินเป็นไปตามเงื่อนไขที่ตกลงกัน<br>
        • หากมีข้อสงสัย กรุณาติดต่อเจ้าหน้าที่
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">ลายเซ็นลูกค้า</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">ลายเซ็นเจ้าหน้าที่</div>
        </div>
    </div>

    <div class="footer">
        <p>ขอบคุณที่ใช้บริการของเรา</p>
        <p>เอกสารนี้ถูกสร้างโดยระบบอัตโนมัติ เมื่อ {{ $date }}</p>
    </div>
</body>

</html>
