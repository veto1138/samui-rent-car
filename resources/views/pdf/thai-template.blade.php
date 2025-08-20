<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'รายงาน' }}</title>
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

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }

        .header .subtitle {
            color: #7f8c8d;
            margin: 5px 0 0 0;
            font-size: 14px;
        }

        .content {
            margin: 20px 0;
        }

        .section {
            margin: 20px 0;
        }

        .section h2 {
            color: #34495e;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            font-size: 16px;
        }

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

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }

        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title ?? 'รายงานระบบเช่ารถ' }}</h1>
        <div class="subtitle">ระบบจัดการการเช่ารถสมุย</div>
        <div class="subtitle">วันที่: {{ $date ?? now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="content">
        @if (isset($summary))
            <div class="section">
                <h2>สรุปข้อมูล</h2>
                <div class="info-box">
                    <p><strong>จำนวนรายการทั้งหมด:</strong> {{ $summary['total'] ?? 0 }}</p>
                    <p><strong>รายการที่เสร็จสิ้น:</strong> {{ $summary['completed'] ?? 0 }}</p>
                    <p><strong>รายการที่รอดำเนินการ:</strong> {{ $summary['pending'] ?? 0 }}</p>
                </div>
            </div>
        @endif

        @if (isset($rentals) && count($rentals) > 0)
            <div class="section">
                <h2>รายการเช่ารถ</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อลูกค้า</th>
                            <th>รถ</th>
                            <th>วันที่เช่า</th>
                            <th>วันที่คืน</th>
                            <th>สถานะ</th>
                            <th>ราคา</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rentals as $index => $rental)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $rental->customer_name ?? 'ไม่ระบุ' }}</td>
                                <td>{{ $rental->car_model ?? 'ไม่ระบุ' }}</td>
                                <td>{{ $rental->rental_date ?? 'ไม่ระบุ' }}</td>
                                <td>{{ $rental->return_date ?? 'ไม่ระบุ' }}</td>
                                <td>
                                    @if (($rental->status ?? '') == 'completed')
                                        เสร็จสิ้น
                                    @elseif(($rental->status ?? '') == 'pending')
                                        รอดำเนินการ
                                    @else
                                        {{ $rental->status ?? 'ไม่ระบุ' }}
                                    @endif
                                </td>
                                <td>{{ number_format($rental->price ?? 0, 2) }} บาท</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if (isset($description))
            <div class="section">
                <h2>รายละเอียดเพิ่มเติม</h2>
                <div class="highlight">
                    <p>{{ $description }}</p>
                </div>
            </div>
        @endif

        @if (isset($notes))
            <div class="section">
                <h2>หมายเหตุ</h2>
                <ul>
                    @foreach ($notes as $note)
                        <li>{{ $note }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>เอกสารนี้ถูกสร้างโดยระบบอัตโนมัติ</p>
        <p>หากมีข้อสงสัย กรุณาติดต่อผู้ดูแลระบบ</p>
    </div>
</body>

</html>
