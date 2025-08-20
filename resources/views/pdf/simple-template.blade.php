<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'thsarabun', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header .date {
            color: #7f8c8d;
            margin: 10px 0 0 0;
            font-size: 16px;
        }

        .content {
            margin: 30px 0;
            text-align: justify;
        }

        .content p {
            margin: 15px 0;
            text-indent: 20px;
        }

        .features {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }

        .features h3 {
            color: #2c3e50;
            margin: 0 0 15px 0;
            font-size: 18px;
        }

        .features ul {
            margin: 0;
            padding-left: 25px;
        }

        .features li {
            margin: 8px 0;
            color: #34495e;
        }

        .highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            text-align: center;
        }

        .highlight h3 {
            margin: 0 0 15px 0;
            font-size: 20px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 2px solid #ecf0f1;
            padding-top: 20px;
        }

        .thai-text {
            font-size: 16px;
            line-height: 2;
            color: #2c3e50;
        }

        .example-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .example-table th,
        .example-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        .example-table th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        .example-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .example-table tr:hover {
            background-color: #e3f2fd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="date">วันที่: {{ $date }}</div>
    </div>

    <div class="content">
        <p>{{ $content }}</p>

        <p class="thai-text">
            สวัสดีครับ/ค่ะ นี่คือตัวอย่างการสร้างไฟล์ PDF ที่รองรับภาษาไทยใน Laravel โดยใช้ mPDF
            ซึ่งเป็น library ที่มีประสิทธิภาพสูงและรองรับการแสดงผลภาษาไทยได้อย่างสมบูรณ์
        </p>

        <p class="thai-text">
            mPDF มีความสามารถในการแปลง HTML และ CSS เป็น PDF ได้อย่างแม่นยำ
            รวมถึงการรองรับตัวอักษรภาษาไทยและภาษาอื่นๆ ที่ใช้ Unicode
        </p>
    </div>

    <div class="features">
        <h3>คุณสมบัติหลักของ mPDF</h3>
        <ul>
            @foreach ($features as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>
    </div>

    <div class="highlight">
        <h3>ตัวอย่างตารางข้อมูล</h3>
        <p>mPDF รองรับการสร้างตารางที่ซับซ้อนได้อย่างดี</p>
    </div>

    <table class="example-table">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รายการ</th>
                <th>รายละเอียด</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>การรองรับภาษาไทย</td>
                <td>แสดงผลภาษาไทยได้อย่างถูกต้อง</td>
                <td style="color: #27ae60; font-weight: bold;">✓ สำเร็จ</td>
            </tr>
            <tr>
                <td>2</td>
                <td>การใช้งาน CSS</td>
                <td>รองรับ CSS ได้เกือบครบถ้วน</td>
                <td style="color: #27ae60; font-weight: bold;">✓ สำเร็จ</td>
            </tr>
            <tr>
                <td>3</td>
                <td>การสร้างตาราง</td>
                <td>สร้างตารางที่ซับซ้อนได้</td>
                <td style="color: #27ae60; font-weight: bold;">✓ สำเร็จ</td>
            </tr>
            <tr>
                <td>4</td>
                <td>การใส่รูปภาพ</td>
                <td>รองรับการใส่รูปภาพได้</td>
                <td style="color: #27ae60; font-weight: bold;">✓ สำเร็จ</td>
            </tr>
            <tr>
                <td>5</td>
                <td>การกำหนดขนาดกระดาษ</td>
                <td>รองรับขนาดกระดาษหลายแบบ</td>
                <td style="color: #27ae60; font-weight: bold;">✓ สำเร็จ</td>
            </tr>
        </tbody>
    </table>

    <div class="content">
        <p class="thai-text">
            การใช้งาน mPDF ใน Laravel นั้นง่ายมาก เพียงแค่ติดตั้ง package ผ่าน Composer
            และเรียกใช้งานใน Controller เท่านั้น ระบบจะจัดการเรื่องการแปลง HTML เป็น PDF
            และการส่งไฟล์กลับไปยังผู้ใช้โดยอัตโนมัติ
        </p>

        <p class="thai-text">
            สำหรับการใช้งานจริง สามารถปรับแต่ง template ให้เหมาะสมกับความต้องการได้
            เช่น การเพิ่มโลโก้บริษัท การปรับแต่งสีและฟอนต์ หรือการเพิ่มข้อมูลอื่นๆ
            ตามที่ต้องการ
        </p>
    </div>

    <div class="footer">
        <p>เอกสารนี้ถูกสร้างโดยระบบอัตโนมัติ เมื่อ {{ $date }}</p>
        <p>ใช้เทคโนโลยี: Laravel + mPDF + HTML/CSS</p>
        <p>รองรับภาษาไทยและภาษาอื่นๆ ที่ใช้ Unicode</p>
    </div>
</body>

</html>
