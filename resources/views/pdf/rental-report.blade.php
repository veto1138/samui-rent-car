{{-- <!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หนังสือสัญญาเช่ารถ - {{ $rental->full_name }}</title>
    <style>
        body {
            font-family: 'thsarabun', sans-serif;
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 16px;
        }

        .m-0 {
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        .write_address_wrap {
            margin-left: 400px;
            font-size: 14px;
        }

        .write_address {
            border: dotted 1px;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="text-center">
        <h2 class="m-0">หนังสือสัญญาเช่ารถ</h2>
    </div>
    <div class="write_address_wrap">
        <p>
            เขียนที่ :
            @if ($rental->write_address)
                {{ $rental->write_address }}
            @else
                ........................................
            @endif
        </p>
    </div>
    <!-- Content -->
    <div class="content">
        <div class="paragraph">
            ข้าพเจ้า <span class="fillable-field">{{ $rental->full_name }}</span> เลขประจำตัวประชาชน <span
                class="fillable-field">{{ $rental->national_id }}</span> ที่อยู่ปัจจุบัน เลขที่ <span
                class="fillable-field">{{ $rental->address }}</span> เบอร์โทรศัพท์ <span
                class="fillable-field">{{ $rental->phone }}</span> ซึ่งต่อไปในสัญญานี้เรียกว่า <strong>ผู้เช่า</strong>
            ได้ทำสัญญาเช่ารถของ
        </div>

        <!-- Owner Information -->
        <div class="owner-info">
            <h3>ข้อมูลเจ้าของรถ/ผู้จัดการ</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ชื่อเจ้าของ:</span>
                    <span class="info-value">{{ $rental->owner_full_name ?? 'นายเทพทัต ทับทอง' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">ที่อยู่:</span>
                    <span
                        class="info-value">{{ $rental->write_address ?? 'เลขที่ 70 ม.2 ตำบล ท่าฉาง อำเภอ ท่าฉาง จังหวัด สุราษฎร์ธานี' }}</span>
                </div>
            </div>
        </div>

        <div class="paragraph">
            ทั้งสองฝ่ายได้ตกลงทำสัญญาดังมีข้อความต่อไปนี้
        </div>

        <!-- Agreement Clauses -->
        <div class="clause">
            <span class="clause-number">ข้อ 1.</span>
            <span class="clause-content">
                ผู้เช่าได้เช่ารถยนต์ยี่ห้อ <span class="fillable-field">{{ $rental->car_brand }}</span> เลขทะเบียน
                <span class="fillable-field">{{ $rental->car_license_plate }}</span> ของผู้ให้เช่าหนึ่งคัน ราคา <span
                    class="price-field">{{ number_format($rental->rent_price, 0) }}</span> บาท (<span
                    class="fillable-field">{{ $rental->rent_price_text }}</span>) ตั้งแต่
                วันที่ <span class="date-time-field">{{ $rental->thai_start_date }}</span> เวลา <span
                    class="fillable-field">{{ $rental->start_time ?? '09:00' }}</span> สถานที่ รับรถ <span
                    class="location-field">{{ $rental->start_location }}</span> ถึง วันที่ <span
                    class="date-time-field">{{ $rental->thai_end_date }}</span> เวลา <span
                    class="fillable-field">{{ $rental->end_time ?? '18:00' }}</span> สถานที่ คืนรถ <span
                    class="location-field">{{ $rental->end_location }}</span>
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 2.</span>
            <span class="clause-content">
                ผู้เช่าได้นำเงิน <span class="price-field">{{ number_format($rental->insurance_price, 0) }}</span> บาท
                มามอบให้ผู้เช่าถือไว้เป็นประกัน
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 3.</span>
            <span class="clause-content">
                ผู้เช่าต้องรับผิดชอบต่อความเสียหาย การสูญหาย การลักขโมยอุปกรณ์ทุกชิ้นไม่ว่าผู้เช่าจะเป็นฝ่ายถูกหรือผิด
                และต้องรับผิดชอบต่อความเสียหายของบุคคลที่สาม
            </span>
            <div class="sub-clause">
                <span class="sub-clause-number">ข้อ 3.1</span>
                กรณีผู้เช่าเป็นฝ่ายถูก ไม่มีค่าใช้จ่าย
            </div>
            <div class="sub-clause">
                <span class="sub-clause-number">ข้อ 3.2</span>
                กรณีผู้เช่าเป็นฝ่ายผิดหรือไม่มีคู่กรณี ผู้เช่าต้องจ่ายค่าเสียหายตามจริง
            </div>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 4.</span>
            <span class="clause-content">
                ห้ามผู้เช่าหรือให้ผู้อื่นเช่าหรือให้ยืมรถโดยไม่ได้รับอนุญาตเป็นลายลักษณ์อักษรจากผู้ให้เช่า
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 5.</span>
            <span class="clause-content">
                ผู้เช่าต้องคืนรถทันทีเมื่อผู้ให้เช่าต้องการ โดยรถและอุปกรณ์ทุกชิ้นต้องอยู่ในสภาพเดิมไม่ชำรุดเสียหาย
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 6.</span>
            <span class="clause-content">
                การเช่ารถจากเกาะสมุย ใช้ได้เฉพาะในเขตอำเภอเกาะสมุย การเช่ารถจากสุราษฎร์ธานี
                ใช้ได้เฉพาะในเขตจังหวัดสุราษฎร์ธานี หากต้องการเดินทางออกนอกเขตต้องแจ้งและขออนุญาตก่อน
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 7.</span>
            <span class="clause-content">
                หากรถสกปรกมาก จะหักค่าทำความสะอาด 300-500 บาท จากเงินประกัน
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 8.</span>
            <span class="clause-content">
                ผู้เช่าต้องคืนรถพร้อมน้ำมันเชื้อเพลิงเท่ากับวันที่รับรถ
            </span>
        </div>

        <div class="clause">
            <span class="clause-number">ข้อ 9.</span>
            <span class="clause-content">
                หากผู้เช่าผิดสัญญา ผู้ให้เช่าจะยึดเงินประกันและเรียกค่าเสียหายเพิ่มเติมและค่าขาดทุน 600 บาทต่อวันซ่อม
            </span>
        </div>
    </div>

    <!-- Price Summary -->
    <div class="price-summary">
        <h3>สรุปราคา</h3>
        <div class="price-grid">
            <div class="price-item">
                <div class="price-label">ค่าบริการเช่ารถ</div>
                <div class="price-value">฿{{ number_format($rental->rent_price, 0) }}</div>
            </div>
            <div class="price-item">
                <div class="price-label">ค่าประกัน</div>
                <div class="price-value">฿{{ number_format($rental->insurance_price, 0) }}</div>
            </div>
        </div>
        <div class="total-price">
            รวมทั้งสิ้น: ฿{{ number_format($rental->rent_price + $rental->insurance_price, 0) }}
        </div>
    </div>

    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $rental->full_name }}</div>
            <div class="signature-title">ผู้เช่า</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $rental->witness_full_name }}</div>
            <div class="signature-title">พยาน</div>
        </div>
    </div>

    <div class="footer">
        <p>เอกสารนี้ถูกสร้างโดยระบบอัตโนมัติ เมื่อ {{ now()->format('d/m/Y H:i') }}</p>
        <p>รหัสการเช่า: #{{ $rental->id }}</p>
        <p>รถเช่าสมุย - บ้านเช่าสุราษฎร์ธานี</p>
    </div>
</body>

</html> --}}
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สัญญาเช่ารถยนต์</title>
    <style>
        body {
            font-family: 'thsarabun', sans-serif;
            font-size: 14px;
            line-height: 1.8;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 14px;
        }

        .m-0 {
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .underline {
            text-decoration: underline;
        }

        .signature {
            margin-top: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 5px;
        }

        .logo {
            width: 100px;
            height: 100px;
            background-color: #000;
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
        }
    </style>
</head>

<body>
    <h2 class="center">หนังสือสัญญาเช่ารถยนต์</h2>
    <p class="right">เขียนที่ {{ $rental->write_address ?? '........................................' }}</p>
    <p>
        ข้าพเจ้า
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->full_name ?? '' }}
        </span>
        เลขประจำตัวประชาชน
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->national_id ?? '' }}
        </span>
        <br>
        ที่อยู่ปัจจุบัน เลขที่
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->address ?? '' }}
        </span>
        <br>
        เบอร์โทรศัพท์
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->phone ?? '' }}
        </span>
        ซึ่งต่อไปในสัญญานี้เรียกว่า <strong>“ผู้เช่า”</strong>
        ได้ทำสัญญาเช่ารถของ {{ $rental->owner_full_name ?? 'นายเทพทัต ทับทอง' }}
        ที่อยู่ {{ $rental->owner_address ?? 'เลขที่ 70 ม.2 ต.ท่าฉาง อ.ท่าฉาง จ.สุราษฎร์ธานี' }}
        ซึ่งต่อไปในสัญญานี้เรียกว่า <strong>เจ้าของกรรมสิทธิ์รถ/ผู้จัดการ <br />
            ทั้งสองฝ่ายได้ตกลงทำสัญญาดังมีข้อความต่อไปนี้</strong>
    </p>

    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 1.</strong> ผู้เช่าได้เช่ารถยนต์ยี่ห้อ
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->car_brand ?? '' }}
        </span>
        เลขทะเบียน
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->car_license_plate ?? '' }}
        </span>
        ของผู้ให้เช่าหนึ่งคัน ราคา
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ number_format($rental->rent_price, 0) ?? '' }}
        </span>
        บาท<br /> (<span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->rent_price_text ?? '' }}
        </span>)
        <br />
        ตั้งแต่ วันที่ เวลา สถานที่ <strong>รับรถ</strong>
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->thai_start_date ?? '' }} ที่ {{ $rental->start_location ?? '' }}
        </span><br />
        ถึง วันที่ เวลา สถานที่ <strong>คืนรถ</strong>
        <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->thai_end_date ?? '' }} {{ $rental->end_time ?? '' }} น.
            ที่ {{ $rental->end_location ?? '' }}
        </span>
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 2.</strong> ผู้เช่าได้นำเงิน <span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ number_format($rental->insurance_price, 0) ?? '' }}
        </span>
        บาท
        (<span
            style="border-bottom: 1px dotted #000; display: inline-block; min-width: 120px; text-align: center;padding: 0 10px; margin: 0 20px">
            {{ $rental->insurance_price_text ?? '' }}
        </span>)
        มามอบให้ผู้เช่าถือไว้เป็นประกัน
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 3.</strong>
        ผู้เช่ารับรองว่า ในระหว่างที่ผู้เช่ารับรถที่เช่าไป หากมีอุปกรณ์ชำรุดหรือเสียหายหรือสูญหายด้วย
        ประการใดๆ และไม่คำนึงว่าจะเป็นความผิดของผู้เช่าหรือไม่
        ผู้เช่ายอมรับใช้ให้แก่ผู้ให้เช่าตามราคาที่กำหนดไว้ทั้งสิ้น
        แม้ความเสียหายใด ๆ พึงทำให้เกิดแก่บุคคลภายนอก ผู้เช่าก็รับผิดชอบใช้ค่าเสียหายให้ผู้เดียวไม่เกี่ยวกับผู้ให้เช่า
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>กรณีเกิดอุบัติเหตุ</strong><br />
    </p>
    <p style="text-indent: 120px; margin: 0px">
        <strong>ข้อ 3.1</strong> กรณีผู้เช่าเป็นฝ่ายถูก ไม่มีค่าใช้จ่าย
    </p>
    <p style="text-indent: 120px; margin: 0px">
        <strong>ข้อ 3.2 กรณีผู้เช่าเป็นฝ่ายผิดหรือไม่มีคู่กรณี ผู้เช่าต้องจ่ายค่าเสียหายตามจริง</strong>
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 4.</strong>
        ผู้เช่ายอมให้สัญญาว่าจะไม่นำรถที่เช่าไปให้คนอื่นเช่าช่วง หรือให้ผู้อื่นยืม หรือนำไปด้วยประการ ใดๆ เป็นอันขาด
        เว้นแต่จะได้รับอนุญาตจากผู้ให้เช่าเป็นลายลักษณ์อักษร
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 5.</strong>
        ถ้าให้ผู้เช่าต้องการรถที่ให้เช่าคืนเมื่อใด ผู้เช่าต้องรีบนำรถส่งคืนพร้อมด้วยเครื่องอุปกรณ์ตามสภาพเดิม
        ซึ่งไม่มีชำรุดเสียหายใดๆ ทันที
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 6.</strong>
        กรณีเช่ารถสาขาเกาะสมุย ห้ามผู้เช่านำรถออกนอกเขตพื้นที่อำเภอเกาะสมุย และกรณีเช่ารถ
        สาขาสุราษฎร์ธานี ห้ามผู้เช่าจะนำรถออกนอกเขตพื้นที่จังหวัดสุราษฎร์ธานีหากผู้เช่าประสงค์ออกนอกพื้นที่จะต้องแจ้ง
        และได้รับอนุญาตจากผู้ให้เช่าก่อน
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 7.</strong>
        หากรถมีความสกปรก โคลน ดิน ทราย อื่นๆ ทั้งภายในและภายนอกมากเกินไป ผู้เช่าจะหักค่าทำความสะอาดในค่าประกัน 300-500
        บาท
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 8.</strong>
        ผู้เช่าจะต้องคืนรถพร้อมปริมาณน้ำมันเชื้อเพลิงที่เท่ากับวันรับรถ
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 9.</strong>
        ถ้าผู้เช่าทำผิดสัญญานี้แต่ข้อหนึ่งข้อใด ผู้เช่ายอมให้ผู้ให้เช่ารับยึดทรัพย์ตามที่วางประกันตามข้อ 2
        และต้องชดใช้ค่าเสียหายในส่วนที่เพิ่มจากค่าประกันและชดเชยรายได้ตามจำนวนวันซ่อม วันละ 600 บาท
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ข้อ 10.</strong>
        เมื่อผู้ให้เช่าได้รับรถและเครื่องอุปกรณ์ตรวจถูกต้องไม่มีการชำรุดเสียหายอย่างใดแล้ว ต้องคืน
        ทรัพย์ที่วางประกันให้ผู้เช่าไป
    </p>
    <div class="signature">
        <table>
            <tr>
                <td class="center">
                    ลงชื่อ ................................................. ผู้ให้เช่า<br>
                    ( {{ $rental->full_name ?? '' }} )
                </td>
                <td class="center">
                    ลงชื่อ ................................................. พยานฝั่งผู้เช่า<br>
                    ( {{ $rental->witness_firstname . ' ' . $rental->witness_lastname ?? '' }} )
                </td>
            </tr>
            <tr>
                <td class="center">
                    ลงชื่อ ................................................. ผู้ให้เช่า/ผู้จัดการ<br>
                    ( {{ $rental->owner_firstname . ' ' . $rental->owner_lastname ?? '' }} )
                </td>
                <td class="center">
                    ลงชื่อ ................................................. พยานฝั่งผู้ให้เช่า<br>
                    ( {{ $rental->owner_witness_firstname . ' ' . $rental->owner_witness_lastname ?? '' }} )
                </td>
            </tr>
        </table>
    </div>

    <h3 class="center">นิยามคำศัพท์ในสัญญาเช่ารถ</h3>
    <p style="text-indent: 70px; margin: 0px">
        <strong>สัญญาเช่า</strong>
        คือ ตามประมวลกฎหมายแพ่งและพาณิชย์ มาตรา 537 สัญญาเช่าทรัพย์ หรือสัญญาเช่า คือ
        สัญญาซึ่งบุคคลคนหนึ่งเรียกว่า "ผู้ให้เช่า" ตกลงให้บุคคลอีกคนหนึ่งเรียกว่า "ผู้เช่า"
        ได้ใช้หรือได้รับประโยชน์ในทรัพย์สิน
        อย่างใดอย่างหนึ่งชั่วระยะเวลาอันมีจำกัด และผู้เช่าตกลงจะให้ค่าเช่าตามจำนวนที่ได้ระบุไว้ในสัญญา
        ซึ่งผู้เช่ามีสิทธิที่
        จะได้ใช้ทรัพย์สินที่เช่า แต่ไม่ได้มีกรรมสิทธิ์ในทรัพย์สินที่เช่าแต่อย่างใด
        และต้องชำระเงินเพื่อตอบแทนการใช้ทรัพย์สิน
        นั้น ๆ เรียกว่า "ค่าเช่า"
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ผู้เช่า</strong>
        หมายถึง บุคคลหรือนิติบุคคลที่ทำสัญญาเช่ารถจากผู้ให้เช่า (บริษัทรถเช่า) โดยมีสิทธิ์ในการ
        ครอบครองและใช้งานรถตามระยะเวลาที่ตกลงกันในสัญญา และต้องชำระค่าเช่าเป็นงวดๆ ตามที่กำหนด. ผู้เช่ารถมี
        หน้าที่ดูแลรักษารถยนต์ให้อยู่ในสภาพดีและปฏิบัติตามเงื่อนไขที่ระบุไว้ในสัญญาเช่า
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ผู้ให้เช่า</strong>
        เจ้าของรถยนต์ หรือผู้ที่มีอำนาจแทนเจ้าของในการให้เช่ารถแก่ผู้เช่า หรือผู้รับมอบอนาจตาม
        สัญญานี้
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>การเช่ารถ</strong>
        เป็นการทำสัญญาเพื่อให้ได้สิทธิ์ในการใช้รถยนต์โดยไม่ต้องเป็นเจ้าของ
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>รถยนต์</strong>
        หมายถึง รถยนต์ที่ระบุในสัญญาเช่า ซึ่งเป็นทรัพย์สินของผู้ให้เช่าที่ถูกให้เช่าตามสัญญาน
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>การดูแลรักษารถ</strong>
        ผู้เช่าต้องดูแลรักษารถให้อยู่ในสภาพดีและปฏิบัติตามเงื่อนไขที่ระบุในสัญญา
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ค่าเช่า</strong>
        เป็นค่าตอบแทนที่ผู้เช่าต้องจ่ายให้ผู้ให้เช่าตามระยะเวลาที่ตกลงกัน
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>การเช่ารถ</strong>
        ผู้เช่ามีสิทธิ์ใช้รถ แต่ไม่ใช่เจ้าของ

    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ระยะเวลาการเช่า</strong>
        หมายถึง ช่วงเวลาตั้งแต่วันที่เริ่มสัญญาถึงวันที่สิ้นสุดสัญญา
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>ผู้รับมอบอำนาจ/ผู้จัดการ</strong>
        หมายถึง บุคคลหรือองค์กรที่มีอำนาจแทนผู้ให้เช่าในการดำเนินการเกี่ยวกับสัญญาเช่านี้
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>พยานผู้เช่า</strong>
        หมายถึง บุคคลที่รับหน้าที่ยืนยันว่าลายมือผู้ลงนามในสัญญาเป็นบุคคลจริงและการลงนาม
        เกิดขึ้นตามความสมัครใจ โดยไม่มีการบังคับ ในที่นี้อาจเป็นบิดา มารดา สามี ภรรยา พี่น้อง ญาติ หรือเพื่อนที่โดยสาร
        มากับผู้เช่า
    </p>
    <p style="text-indent: 70px; margin: 0px">
        <strong>พยานผู้ให้เช่า</strong>
        หมายถึง บุคคลที่รับหน้าที่ยืนยันว่าลายมือผู้ลงนามในสัญญาเป็นบุคคลจริงและการลงนาม
        เกิดขึ้นตามความสมัครใจ โดยไม่มีการบังคับ ในที่นี้อาจเป็นบิดา มารดา สามี ภรรยา พี่น้อง ญาติ
        หรือเพื่อนของผู้ให้เช่า
    </p>
</body>

</html>
