# Samui Rent Car - ระบบจัดการการเช่ารถ

## การแก้ไขปัญหาหน้าแก้ไขแสดงข้อมูลไม่ครบ

### ปัญหาที่พบ

หน้าแก้ไขข้อมูลการเช่ารถแสดงข้อมูลไม่ครบเนื่องจาก:

1. **ความไม่สอดคล้องของ field names** ระหว่าง Model, View และ Database
2. **Field ที่หายไป** ในหน้าแก้ไข เช่น ข้อมูลรถ, ราคา, ข้อมูลเจ้าของรถ
3. **การจัดการไฟล์แนบ** ที่ไม่สมบูรณ์

### การแก้ไขที่ทำ

#### 1. แก้ไขหน้าแก้ไข (edit.blade.php)

-   **แก้ไข field names** ให้ตรงกับโครงสร้างฐานข้อมูล:

    -   `first_name` → `firstname`
    -   `last_name` → `lastname`
    -   `id_card` → `national_id`
    -   `witness_name` → `witness_firstname`, `witness_lastname`

-   **เพิ่ม field ที่หายไป**:

    -   ข้อมูลรถ: `car_brand`, `car_license_plate`
    -   ราคา: `rent_price`, `insurance_price`
    -   ข้อมูลเจ้าของรถ: `owner_firstname`, `owner_lastname`, `owner_witness_firstname`, `owner_witness_lastname`
    -   เขียนที่: `write_address`

-   **ปรับปรุงการแสดงไฟล์แนบ**:
    -   แสดงรูปภาพปัจจุบัน
    -   อัปโหลดไฟล์ใหม่แยกตามประเภท

#### 2. แก้ไข RentalController

-   **อัปเดต validation rules** ให้รองรับ field ใหม่ทั้งหมด
-   **จัดการการอัปโหลดไฟล์** อย่างถูกต้อง
-   **ลบไฟล์เก่า** เมื่ออัปโหลดไฟล์ใหม่

#### 3. แก้ไข Model Rental

-   **เพิ่ม accessor methods** สำหรับ field ที่คำนวณได้:
    -   `rental_days`: คำนวณจำนวนวันที่เช่าจาก start_date และ end_date
    -   `full_name`: รวมชื่อ-นามสกุล
    -   `witness_full_name`: รวมชื่อพยาน
    -   `owner_full_name`: รวมชื่อเจ้าของรถ
    -   `formatted_*`: จัดรูปแบบข้อมูลต่างๆ

#### 4. ปรับปรุง CSS

-   **เพิ่ม styles** สำหรับหน้าแก้ไข
-   **ปรับปรุง responsive design**
-   **เพิ่ม animations** และ transitions

### โครงสร้างฐานข้อมูลที่ใช้

```sql
CREATE TABLE rentals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(100),           -- ชื่อ
    lastname VARCHAR(100),            -- นามสกุล
    national_id VARCHAR(100),         -- เลขบัตรประชาชน
    phone VARCHAR(15),                -- เบอร์โทรศัพท์
    address VARCHAR(100),             -- ที่อยู่
    witness_firstname VARCHAR(100),   -- ชื่อพยาน
    witness_lastname VARCHAR(100),    -- นามสกุลพยาน
    rent_price VARCHAR(10),           -- ราคาเช่า
    insurance_price VARCHAR(10),      -- ราคาประกัน
    start_date DATETIME,              -- วันที่เริ่ม
    end_date DATETIME,                -- วันที่สิ้นสุด
    start_location VARCHAR(150),      -- สถานที่เริ่ม
    end_location VARCHAR(150),        -- สถานที่สิ้นสุด
    selfie_image VARCHAR(255),        -- รูป Selfie
    national_id_image VARCHAR(255),   -- รูปบัตรประชาชน
    driver_license_image VARCHAR(255), -- รูปใบขับขี่
    car_brand VARCHAR(100),           -- ยี่ห้อรถ
    car_license_plate VARCHAR(20),    -- ทะเบียนรถ
    status ENUM('pending','using','success','cancel'), -- สถานะ
    write_address TEXT,               -- เขียนที่
    owner_firstname VARCHAR(100),     -- ชื่อเจ้าของรถ
    owner_lastname VARCHAR(100),      -- นามสกุลเจ้าของรถ
    owner_witness_firstname VARCHAR(100), -- ชื่อพยานเจ้าของรถ
    owner_witness_lastname VARCHAR(100),  -- นามสกุลพยานเจ้าของรถ
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### การใช้งาน

#### 1. เข้าสู่หน้าแก้ไข

```
GET /rentals/{id}/edit
```

#### 2. แก้ไขข้อมูล

-   กรอกข้อมูลในฟอร์ม
-   อัปโหลดไฟล์ใหม่ (ถ้ามี)
-   กดบันทึกการเปลี่ยนแปลง

#### 3. การจัดการไฟล์

-   รูปภาพปัจจุบันจะแสดงในหน้า
-   สามารถอัปโหลดรูปใหม่แทนที่รูปเก่า
-   ระบบจะลบไฟล์เก่าอัตโนมัติ

### ข้อควรระวัง

1. **การตรวจสอบสิทธิ์**: ต้อง login ก่อนเข้าถึงหน้าแก้ไข
2. **การตรวจสอบข้อมูล**: ระบบจะ validate ข้อมูลก่อนบันทึก
3. **การจัดการไฟล์**: ไฟล์เก่าจะถูกลบเมื่ออัปโหลดไฟล์ใหม่
4. **การคำนวณวันที่**: จำนวนวันที่เช่าจะคำนวณอัตโนมัติ

### การพัฒนาต่อ

-   เพิ่มการส่งอีเมลแจ้งเตือนเมื่ออัปเดตข้อมูล
-   เพิ่มการ log การเปลี่ยนแปลงข้อมูล
-   เพิ่มการ backup ข้อมูลก่อนอัปเดต
-   เพิ่มการแสดงประวัติการแก้ไข

## การติดตั้งและรัน

```bash
# ติดตั้ง dependencies
composer install
npm install

# สร้างไฟล์ .env
cp .env.example .env

# สร้าง key
php artisan key:generate

# รัน migration
php artisan migrate

# รัน seeder (ถ้ามี)
php artisan db:seed

# รัน development server
php artisan serve
```

## เทคโนโลยีที่ใช้

-   **Backend**: Laravel 10
-   **Frontend**: Blade Templates, Tailwind CSS
-   **Database**: MySQL/PostgreSQL
-   **JavaScript**: jQuery, DataTables
-   **File Storage**: Laravel Storage
