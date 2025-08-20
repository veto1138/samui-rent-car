<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สัญญาเช่ารถยนต์</title>
    <style>
        body {
            font-family: 'thsarabun', sans-serif;
            font-size: 22px;
            line-height: 1.1;
            color: #000;
            max-width: 800px;
            margin: 0 auto;
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
            margin-top: 20px;
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
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
        }

        .image-container {
            width: 100%;
            height: 1000px;
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="{{ $logoImage }}" alt="Logo" style="width: 100px; height: 100px;">
    </div>
    <h3 class="center m-0">หนังสือสัญญาเช่ารถยนต์</h3>
    <p class="right">เขียนที่ {{ $rental->write_address ?? '........................................' }}</p>
    <p class="m-0" style="">
        ข้าพเจ้า{{ str_repeat('.', $leftDotsName) }} {{ $rental->full_name }} {{ str_repeat('.', $rightDotsName) }}
        เลขประจำตัวประชาชน{{ str_repeat('.', $leftDotsNationalId) }} {{ $rental->national_id }}
        {{ str_repeat('.', $rightDotsNationalId) }}
    </p>
    <p class="m-0" style="">
        ที่อยู่ปัจจุบัน เลขที่{{ str_repeat('.', $leftDotsAddress) }} {{ $rental->address }}
        {{ str_repeat('.', $rightDotsAddress) }}
    </p>
    <p class="m-0" style="">
        เบอร์โทรศัพท์{{ str_repeat('.', $leftDotsPhone) }} {{ $rental->phone }}
        {{ str_repeat('.', $rightDotsPhone) }}
        ซึ่งต่อไปในสัญญานี้เรียกว่า <strong>“ผู้เช่า”</strong> ได้ทำสัญญาเช่ารถของ
    </p>
    <p class="m-0" style="">
        นายเทพทัต ทับทอง ที่อยู่ เลขที่ 70 ม.2 ตำบล ท่าฉาง อ.ท่าฉาง จ.สุราษฎร์ธานี<br />
        ซึ่งต่อไปในสัญญานี้เรียกว่า <strong>เจ้าของกรรมสิทธิ์รถ/ผู้จัดการ
            ทั้งสองฝ่ายได้ตกลงทำสัญญาดังมีข้อความต่อไปนี้</strong>
    </p>
    <p class="m-0" style="text-indent: 70px;margin-top: 16px;">
        <strong>ข้อ 1.</strong> ผู้เช่าได้เช่ารถยนต์ยี่ห้อ {{ str_repeat('.', $leftDotsCarBrand) }}
        {{ $rental->car_full_name }}
        {{ str_repeat('.', $rightDotsCarBrand) }}
        เลขทะเบียน
        {{ str_repeat('.', $leftDotsCarLicensePlate) }} {{ $rental->car_license_plate }}
        {{ str_repeat('.', $rightDotsCarLicensePlate) }}
    </p>
    <p class="m-0">
        ของผู้ให้เช่าหนึ่งคัน ราคา {{ str_repeat('.', $leftDotsRentPrice) }} {{ $rental->rent_price }}
        {{ str_repeat('.', $rightDotsRentPrice) }} บาท (<span>{{ str_repeat('.', $leftDotsRentPriceText) }}
            {{ $rental->rent_price_text ?? '' }}
            {{ str_repeat('.', $rightDotsRentPriceText) }}</span>)
    </p>
    <p class="m-0">
        ตั้งแต่ วันที่ เวลา สถานที่ <strong>รับรถ</strong>
        {{ str_repeat('.', $leftDotsStartDate) }} {{ $rental->thai_start_date ?? '' }} ที่
        {{ $rental->start_location ?? '' }}
        {{ str_repeat('.', $rightDotsStartDate) }}
    </p>
    <p class="m-0">
        ถึง วันที่ เวลา สถานที่ <strong>คืนรถ</strong>
        {{ str_repeat('.', $leftDotsEndDate) }} {{ $rental->thai_end_date ?? '' }} {{ $rental->end_time ?? '' }} น.
        ที่ {{ $rental->end_location ?? '' }}
        {{ str_repeat('.', $rightDotsEndDate) }}
    </p>
    <p class="m-0" style="text-indent: 70px;">
        <strong>ข้อ 2.</strong> ผู้เช่านำเงิน {{ str_repeat('.', $leftDotsInsurancePrice) }}
        {{ $rental->insurance_price }}
        {{ str_repeat('.', $rightDotsInsurancePrice) }} บาท
        (<span>{{ str_repeat('.', $leftDotsInsurancePriceText) }} {{ $rental->insurance_price_text ?? '' }}
            {{ str_repeat('.', $rightDotsInsurancePriceText) }}</span>)
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
                    ลงชื่อ ................................................. ผู้เช่า<br>
                    ( {{ $rental->full_name ?? '' }} )
                </td>
                <td class="center">
                    ลงชื่อ ................................................. ผู้ให้เช่า/ผู้จัดการ<br>

                    ( {{ $rental->owner_firstname . ' ' . $rental->owner_lastname ?? '' }} )
                </td>
            </tr>
            <tr>
                <td class="center">
                    ลงชื่อ .................................................พยานฝั่งผู้เช่า<br>
                    ( {{ $rental->witness_firstname . ' ' . $rental->witness_lastname ?? '' }} )
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
    </p>

    <div class="image-container">

        <img src="{{ $selfieImage }}" alt="Selfie Image" style="width: 100%; height: auto;">

    </div>
    <div class="image-container">

        <img src="{{ $nationalIdImage }}" alt="National ID Image" style="width: 100%; height: auto;">

    </div>

    <div class="image-container">

        <img src="{{ $driverLicenseImage }}" alt="Driver License Image" style="width: 100%; height: auto;">

    </div>
</body>

</html>
