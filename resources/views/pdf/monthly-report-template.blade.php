<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Sarabun', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 20px;
        }

        .header .subtitle {
            color: #7f8c8d;
            margin: 5px 0 0 0;
            font-size: 12px;
        }

        .summary-section {
            margin: 20px 0;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 15px 0;
        }

        .summary-item {
            text-align: center;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 10px;
            color: #7f8c8d;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .highlight-row {
            background-color: #fff3cd !important;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .chart-section {
            margin: 20px 0;
            text-align: center;
        }

        .chart-placeholder {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }

        .section-title {
            color: #34495e;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
            margin: 20px 0 15px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">ระบบจัดการการเช่ารถสมุย</div>
        <div class="subtitle">วันที่สร้างรายงาน: {{ $date }}</div>
    </div>

    <div class="summary-section">
        <h3 class="section-title">สรุปข้อมูลประจำเดือน {{ $month }}</h3>

        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ number_format($summary['total_rentals']) }}</div>
                <div class="summary-label">รายการเช่ารถทั้งหมด</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($summary['completed_rentals']) }}</div>
                <div class="summary-label">รายการที่เสร็จสิ้น</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($summary['total_revenue'], 2) }}</div>
                <div class="summary-label">รายได้รวม (บาท)</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ number_format($summary['average_daily_revenue'], 2) }}</div>
                <div class="summary-label">รายได้เฉลี่ยต่อวัน (บาท)</div>
            </div>
        </div>
    </div>

    <div class="chart-section">
        <h3 class="section-title">กราฟแสดงรายได้รายวัน</h3>
        <div class="chart-placeholder">
            กราฟแสดงรายได้รายวันประจำเดือน {{ $month }}<br>
            (สามารถเพิ่มกราฟแท่งหรือกราฟเส้นได้ตามต้องการ)
        </div>
    </div>

    <div class="section-title">รายละเอียดรายได้รายวัน</div>
    <table class="table">
        <thead>
            <tr>
                <th>วันที่</th>
                <th>จำนวนรายการ</th>
                <th>รายการที่เสร็จสิ้น</th>
                <th>รายได้ (บาท)</th>
                <th>เปอร์เซ็นต์</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyData as $data)
                <tr @if ($data->daily_revenue > $summary['average_daily_revenue'] * 1.2) class="highlight-row" @endif>
                    <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                    <td>{{ $data->total_rentals }}</td>
                    <td>{{ $data->completed_rentals }}</td>
                    <td>{{ number_format($data->daily_revenue, 2) }}</td>
                    <td>
                        @if ($summary['total_revenue'] > 0)
                            {{ number_format(($data->daily_revenue / $summary['total_revenue']) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #e8f5e8; font-weight: bold;">
                <td><strong>รวม</strong></td>
                <td><strong>{{ $summary['total_rentals'] }}</strong></td>
                <td><strong>{{ $summary['completed_rentals'] }}</strong></td>
                <td><strong>{{ number_format($summary['total_revenue'], 2) }}</strong></td>
                <td><strong>100%</strong></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin: 20px 0; padding: 15px; background-color: #e3f2fd; border-left: 4px solid #2196f3;">
        <h4 style="margin: 0 0 10px 0; color: #1976d2;">การวิเคราะห์ข้อมูล</h4>
        <ul style="margin: 0; padding-left: 20px;">
            <li>วันที่มีรายได้สูงสุด:
                @if ($monthlyData->count() > 0)
                    {{ \Carbon\Carbon::parse($monthlyData->sortByDesc('daily_revenue')->first()->date)->format('d/m/Y') }}
                    ({{ number_format($monthlyData->sortByDesc('daily_revenue')->first()->daily_revenue, 2) }} บาท)
                @else
                    ไม่มีข้อมูล
                @endif
            </li>
            <li>วันที่มีรายได้ต่ำสุด:
                @if ($monthlyData->count() > 0)
                    {{ \Carbon\Carbon::parse($monthlyData->sortBy('daily_revenue')->first()->date)->format('d/m/Y') }}
                    ({{ number_format($monthlyData->sortBy('daily_revenue')->first()->daily_revenue, 2) }} บาท)
                @else
                    ไม่มีข้อมูล
                @endif
            </li>
            <li>อัตราการเสร็จสิ้น:
                @if ($summary['total_rentals'] > 0)
                    {{ number_format(($summary['completed_rentals'] / $summary['total_rentals']) * 100, 1) }}%
                @else
                    0%
                @endif
            </li>
        </ul>
    </div>

    <div class="footer">
        <p>รายงานนี้ถูกสร้างโดยระบบอัตโนมัติ เมื่อ {{ $date }}</p>
        <p>ข้อมูลถูกดึงจากฐานข้อมูลในระบบ</p>
        <p>หากมีข้อสงสัย กรุณาติดต่อผู้ดูแลระบบ</p>
    </div>
</body>

</html>
