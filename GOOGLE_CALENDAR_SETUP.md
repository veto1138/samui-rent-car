# การตั้งค่า Google Calendar Integration

คู่มือการตั้งค่าระบบ Google Calendar สำหรับ Samui Rent Car

## ขั้นตอนการตั้งค่า

### 1. สร้าง Google Cloud Project

1. ไปที่ [Google Cloud Console](https://console.cloud.google.com)
2. สร้างโปรเจกต์ใหม่หรือเลือกโปรเจกต์ที่มีอยู่
3. เปิดใช้งาน Google Calendar API:
    - ไปที่ "APIs & Services" > "Library"
    - ค้นหา "Google Calendar API"
    - คลิก "Enable"

### 2. สร้าง Service Account

1. ไปที่ "IAM & Admin" > "Service Accounts"
2. คลิก "Create Service Account"
3. ตั้งชื่อ Service Account (เช่น "samui-rent-car-calendar")
4. คลิก "Create and Continue"
5. ให้สิทธิ์ "Editor" หรือ "Calendar Admin"
6. คลิก "Done"
7. คลิกที่ Service Account ที่สร้างขึ้น
8. ไปที่แท็บ "Keys"
9. คลิก "Add Key" > "Create new key"
10. เลือก "JSON" และคลิก "Create"
11. ดาวน์โหลดไฟล์ JSON credentials

### 3. แชร์ Google Calendar

1. เปิด [Google Calendar](https://calendar.google.com)
2. เลือก calendar ที่ต้องการใช้
3. คลิกที่ไอคอน "Settings" (เฟือง)
4. เลือก "Share with specific people"
5. เพิ่ม Service Account email (จากไฟล์ JSON)
6. ให้สิทธิ์ "Make changes to events"
7. คลิก "Send"

### 4. อัปโหลด Credentials

1. อัปโหลดไฟล์ JSON ไปที่ `storage/app/`
2. ตั้งชื่อไฟล์เป็น `google-calendar-credentials.json`
3. ตรวจสอบว่าไฟล์มีสิทธิ์การอ่านที่ถูกต้อง

### 5. ตั้งค่า Environment Variables

เพิ่มการตั้งค่าต่อไปนี้ในไฟล์ `.env`:

```env
GOOGLE_CALENDAR_ID=your_calendar_id_here
GOOGLE_CREDENTIALS_PATH=storage/app/google-calendar-credentials.json
```

**หมายเหตุ:**

-   `GOOGLE_CALENDAR_ID` สามารถเป็น:
    -   `primary` (calendar หลัก)
    -   `your_email@gmail.com` (calendar เฉพาะ)
    -   Calendar ID จาก Google Calendar settings

### 6. ทดสอบการเชื่อมต่อ

1. ไปที่หน้า "จัดการ Google Calendar" ในระบบ
2. คลิกปุ่ม "ทดสอบการเชื่อมต่อ"
3. ตรวจสอบว่าสถานะแสดง "เชื่อมต่อสำเร็จ"

## การใช้งาน

### เพิ่มการเช่ารถลง Google Calendar

1. ไปที่หน้า "รายการจองรถ"
2. คลิกปุ่ม "เพิ่มลง Google Calendar" (ไอคอนสีม่วง)
3. ยืนยันการเพิ่มข้อมูล
4. ระบบจะสร้าง event ใน Google Calendar โดยอัตโนมัติ

### ดูข้อมูลใน Google Calendar

1. คลิกปุ่ม "ดูใน Google Calendar" (ไอคอนสีเขียว)
2. ระบบจะเปิด Google Calendar ในแท็บใหม่
3. แสดงรายละเอียดการเช่ารถครบถ้วน

### จัดการ Google Calendar

1. ไปที่หน้า "จัดการ Google Calendar"
2. ทดสอบการเชื่อมต่อ
3. Sync การเช่ารถทั้งหมด
4. ดูสถานะการเชื่อมต่อของแต่ละรายการ

## คุณสมบัติ

-   **อัตโนมัติ**: เพิ่มข้อมูลการเช่ารถลง Google Calendar โดยอัตโนมัติ
-   **แจ้งเตือน**: ตั้งการแจ้งเตือน 1 วันและ 1 ชั่วโมงก่อนเริ่มเช่า
-   **รายละเอียดครบถ้วน**: แสดงข้อมูลลูกค้า, รถยนต์, ราคา, สถานที่
-   **การจัดการ**: เพิ่ม, อัปเดต, ลบ event ได้ตามต้องการ
-   **Sync**: รองรับการ sync ข้อมูลทั้งหมดในครั้งเดียว

## การแก้ไขปัญหา

### ปัญหาการเชื่อมต่อ

1. ตรวจสอบว่าไฟล์ credentials ถูกต้อง
2. ตรวจสอบสิทธิ์การเข้าถึง calendar
3. ตรวจสอบการตั้งค่า environment variables
4. ตรวจสอบ log files ใน `storage/logs/`

### ปัญหาการสร้าง Event

1. ตรวจสอบว่า calendar มีพื้นที่เพียงพอ
2. ตรวจสอบสิทธิ์การเขียน event
3. ตรวจสอบรูปแบบวันที่และเวลา

## ความปลอดภัย

-   ไฟล์ credentials ควรเก็บใน `storage/app/` ที่ไม่สามารถเข้าถึงจากภายนอกได้
-   ใช้ Service Account แทนการเข้าถึงด้วย user account
-   จำกัดสิทธิ์การเข้าถึง calendar ให้เหมาะสม
-   ตรวจสอบ log files เป็นประจำ

## การบำรุงรักษา

-   อัปเดต Google API Client library เป็นประจำ
-   ตรวจสอบการหมดอายุของ credentials
-   สำรองข้อมูล calendar เป็นประจำ
-   ตรวจสอบการใช้งาน API quota

## ติดต่อสนับสนุน

หากมีปัญหาหรือคำถามเพิ่มเติม กรุณาติดต่อทีมพัฒนา
