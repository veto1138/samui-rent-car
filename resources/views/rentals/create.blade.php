<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบฟอร์มการเช่ารถ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        /* ปรับแต่ง Flatpickr ให้สวยงาม */
        .flatpickr-calendar {
            font-family: 'Kanit', sans-serif !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            border: 1px solid #e5e7eb !important;
        }

        .flatpickr-day.selected {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        .flatpickr-day.selected:hover {
            background: #2563eb !important;
            border-color: #2563eb !important;
        }

        .flatpickr-time input {
            font-family: 'Kanit', sans-serif !important;
            border-radius: 8px !important;
        }

        .flatpickr-time input:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .flatpickr-current-month {
            font-family: 'Kanit', sans-serif !important;
            font-weight: 600 !important;
        }

        .flatpickr-monthDropdown-months {
            font-family: 'Kanit', sans-serif !important;
        }

        .flatpickr-weekday {
            font-family: 'Kanit', sans-serif !important;
            font-weight: 500 !important;
        }

        .flatpickr-day {
            font-family: 'Kanit', sans-serif !important;
            border-radius: 8px !important;
        }

        .flatpickr-day:hover {
            background: #f3f4f6 !important;
        }

        .flatpickr-time {
            border-radius: 0 0 16px 16px !important;
        }

        .flatpickr-am-pm {
            font-family: 'Kanit', sans-serif !important;
            font-weight: 500 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // ตั้งค่าเริ่มต้นเป็นเวลาปัจจุบัน (ปัดขึ้นเป็น 10 นาทีถัดไป)
            function getCurrentDateTime() {
                const now = new Date();
                // ปัดขึ้นเป็น 10 นาทีถัดไป
                const minutes = Math.ceil(now.getMinutes() / 10) * 10;
                now.setMinutes(minutes, 0, 0);
                // เพิ่ม 1 ชั่วโมงถ้าเป็นวันนี้
                if (minutes === 0) {
                    now.setHours(now.getHours() + 1);
                }
                return now;
            }

            function getEndDateTime(startValue) {
                if (!startValue) return '';
                const startDate = new Date(startValue);
                // เพิ่ม 1 วันเป็นค่าเริ่มต้น
                startDate.setDate(startDate.getDate() + 1);
                return startDate;
            }

            // ตั้งค่าเริ่มต้น
            const currentDateTime = getCurrentDateTime();
            const endDateTime = getEndDateTime(currentDateTime);

            // Flatpickr สำหรับวันที่เริ่ม
            const startPicker = flatpickr(startDateInput, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minuteIncrement: 10,
                locale: "th",
                defaultDate: currentDateTime,
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0]) {
                        // อัปเดตวันที่สิ้นสุด
                        endPicker.set('minDate', selectedDates[0]);

                        // ตรวจสอบว่าวันที่สิ้นสุดน้อยกว่าวันที่เริ่มหรือไม่
                        if (endPicker.selectedDates[0] && endPicker.selectedDates[0] <= selectedDates[
                                0]) {
                            const newEndDate = new Date(selectedDates[0]);
                            newEndDate.setDate(newEndDate.getDate() + 1);
                            endPicker.setDate(newEndDate);
                        }
                    }
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    // เพิ่ม CSS สำหรับปรับแต่ง Flatpickr
                    const fp = instance.calendarContainer;
                    fp.style.fontFamily = 'Kanit, sans-serif';
                }
            });

            // Flatpickr สำหรับวันที่สิ้นสุด
            const endPicker = flatpickr(endDateInput, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minuteIncrement: 10,
                locale: "th",
                defaultDate: endDateTime,
                minDate: currentDateTime,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates[0] && startPicker.selectedDates[0]) {
                        if (selectedDates[0] <= startPicker.selectedDates[0]) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'คำเตือน!',
                                text: 'วันที่และเวลาสิ้นสุดต้องมากกว่าวันที่เริ่ม',
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#f59e0b',
                                customClass: {
                                    popup: 'font-kanit',
                                    title: 'font-kanit',
                                    content: 'font-kanit',
                                    confirmButton: 'font-kanit'
                                }
                            });
                            // ตั้งค่าวันที่สิ้นสุดเป็น 1 วันหลังจากวันที่เริ่ม
                            const newEndDate = new Date(startPicker.selectedDates[0]);
                            newEndDate.setDate(newEndDate.getDate() + 1);
                            instance.setDate(newEndDate);
                        }
                    }
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    // เพิ่ม CSS สำหรับปรับแต่ง Flatpickr
                    const fp = instance.calendarContainer;
                    fp.style.fontFamily = 'Kanit, sans-serif';
                }
            });


        });

        // เพิ่ม Client-side Validation สำหรับเลขบัตรประชาชนและเบอร์โทรศัพท์
        function validateThaiCitizenId(input) {
            if (typeof input !== 'string') return false;

            // 1) เช็คฟอร์แมต (อนุญาต 2 แบบ)
            const withDashes = /^\d-\d{4}-\d{5}-\d{2}-\d$/;
            const plain13 = /^\d{13}$/;
            if (!withDashes.test(input) && !plain13.test(input)) {
                return false; // ฟอร์แมตไม่ถูก
            }

            // 2) ตัดขีดออกแล้วเช็ค checksum
            const digits = input.replace(/-/g, '');
            if (digits.length !== 13) return false;

            // หลักที่ 13 ต้องเท่ากับ: (11 - (ผลรวม(d1*13 + d2*12 + ... + d12*2) mod 11)) mod 10
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                sum += parseInt(digits[i], 10) * (13 - i);
            }
            const check = (11 - (sum % 11)) % 10;
            return check === parseInt(digits[12], 10);
        }

        function validateNationalId(input) {
            // ลบตัวอักษรที่ไม่ใช่ตัวเลข
            let value = input.value.replace(/\D/g, '');

            // จำกัดความยาวไม่เกิน 13 หลัก
            if (value.length > 13) {
                value = value.substring(0, 13);
            }

            // อัปเดตค่าใน input
            input.value = value;

            // ตรวจสอบรูปแบบตามมาตรฐานบัตรประชาชนไทยด้วย algorithm ที่ถูกต้อง
            const isValid = validateThaiCitizenId(value);

            // เปลี่ยนสีขอบตามสถานะ
            input.classList.remove('border-red-500', 'border-green-500', 'border-gray-300');

            if (value.length === 0) {
                input.classList.add('border-gray-300');
            } else if (isValid && value.length === 13) {
                input.classList.add('border-green-500');
            } else {
                input.classList.add('border-red-500');
            }

            // แสดง/ซ่อน error message
            const errorElement = input.parentElement.querySelector('.text-red-600');
            if (errorElement) {
                errorElement.style.display = value.length > 0 && !isValid ? 'block' : 'none';
            }

            // แสดง/ซ่อน helper text
            const helperElement = input.parentElement.querySelector('.text-xs.text-gray-500');
            if (helperElement) {
                helperElement.style.display = value.length === 0 ? 'block' : 'none';
            }
        }

        function validatePhone(input) {
            // ลบตัวอักษรที่ไม่ใช่ตัวเลข
            let value = input.value.replace(/\D/g, '');

            // ตรวจสอบหลักแรก ต้องเป็น 0 เท่านั้น
            if (value.length > 0 && !/^0/.test(value)) {
                value = value.substring(1); // ลบหลักแรกที่ผิด
            }

            // จำกัดความยาวไม่เกิน 10 หลัก
            if (value.length > 10) {
                value = value.substring(0, 10);
            }

            // อัปเดตค่าใน input
            input.value = value;

            // ตรวจสอบรูปแบบ
            const isValid = /^0[0-9]{9}$/.test(value);

            // เปลี่ยนสีขอบตามสถานะ
            input.classList.remove('border-red-500', 'border-green-500', 'border-gray-300');

            if (value.length === 0) {
                input.classList.add('border-gray-300');
            } else if (isValid && value.length === 10) {
                input.classList.add('border-green-500');
            } else {
                input.classList.add('border-red-500');
            }

            // แสดง/ซ่อน error message
            const errorElement = input.parentElement.querySelector('.text-red-600');
            if (errorElement) {
                errorElement.style.display = value.length > 0 && !isValid ? 'block' : 'none';
            }

            // แสดง/ซ่อน helper text
            const helperElement = input.parentElement.querySelector('.text-xs.text-gray-500');
            if (helperElement) {
                helperElement.style.display = value.length === 0 ? 'block' : 'none';
            }
        }

        // เพิ่ม Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            const nationalIdInput = document.getElementById('national_id');
            const phoneInput = document.getElementById('phone');
            const form = document.querySelector('form');

            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // แสดง SweetAlert2 เมื่อมี session success
            const successMessage = document.getElementById('success-message');
            if (successMessage && successMessage.textContent.trim()) {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: successMessage.textContent.trim(),
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#10b981',
                    customClass: {
                        popup: 'font-kanit',
                        title: 'font-kanit',
                        content: 'font-kanit',
                        confirmButton: 'font-kanit'
                    }
                });
            }







            if (nationalIdInput) {
                // ป้องกันการกรอกผิดตั้งแต่ต้น
                nationalIdInput.addEventListener('keydown', function(e) {
                    // อนุญาตเฉพาะตัวเลข, backspace, delete, arrow keys
                    if (!/[\d]/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab']
                        .includes(e.key)) {
                        e.preventDefault();
                        return false;
                    }
                });

                nationalIdInput.addEventListener('input', function() {
                    validateNationalId(this);
                });

                nationalIdInput.addEventListener('blur', function() {
                    validateNationalId(this);
                });

                // ป้องกันการ paste ข้อมูลผิด
                nationalIdInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const numericOnly = pastedText.replace(/\D/g, '');

                    if (numericOnly.length > 0) {
                        this.value = numericOnly.substring(0, 13);
                        validateNationalId(this);
                    }
                });
            }

            if (phoneInput) {
                // ป้องกันการกรอกผิดตั้งแต่ต้น
                phoneInput.addEventListener('keydown', function(e) {
                    // อนุญาตเฉพาะตัวเลข, backspace, delete, arrow keys
                    if (!/[\d]/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab']
                        .includes(e.key)) {
                        e.preventDefault();
                        return false;
                    }

                    // ตรวจสอบหลักแรก ต้องเป็น 0 เท่านั้น
                    if (this.value.length === 0 && e.key !== '0') {
                        e.preventDefault();
                        return false;
                    }
                });

                phoneInput.addEventListener('input', function() {
                    validatePhone(this);
                });

                phoneInput.addEventListener('blur', function() {
                    validatePhone(this);
                });

                // ป้องกันการ paste ข้อมูลผิด
                phoneInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const numericOnly = pastedText.replace(/\D/g, '');

                    if (numericOnly.length > 0 && /^0/.test(numericOnly)) {
                        this.value = numericOnly.substring(0, 10);
                        validatePhone(this);
                    }
                });
            }

            // เพิ่ม Form Validation และ Loading ก่อนส่ง
            if (form) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // ตรวจสอบเลขบัตรประชาชน
                    if (nationalIdInput && !validateThaiCitizenId(nationalIdInput.value)) {
                        isValid = false;
                        nationalIdInput.classList.add('border-red-500');
                        nationalIdInput.parentElement.querySelector('.text-red-600').style.display =
                            'block';
                    }

                    // ตรวจสอบเบอร์โทรศัพท์
                    if (phoneInput && !/^0[0-9]{9}$/.test(phoneInput.value)) {
                        isValid = false;
                        phoneInput.classList.add('border-red-500');
                        phoneInput.parentElement.querySelector('.text-red-600').style.display = 'block';
                    }

                    if (!isValid) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนส่งฟอร์ม',
                            confirmButtonText: 'ตกลง',
                            confirmButtonColor: '#ef4444',
                            customClass: {
                                popup: 'font-kanit',
                                title: 'font-kanit',
                                content: 'font-kanit',
                                confirmButton: 'font-kanit'
                            }
                        });
                        return false;
                    }

                    // แสดง Loading หลังกด Submit
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        กำลังบันทึกข้อมูล...
                    `;
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                    // ตั้งเวลาเพื่อคืนค่าปุ่มหลังจาก 5 วินาที (กรณีที่เกิดข้อผิดพลาด)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    }, 5000);
                });
            }
        });
    </script>
</head>

<body class="">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header -->
        <div class="text-center mb-0">
            <h2 class="text-3xl font-bold text-secondary mb-2 ">แบบฟอร์มการเช่ารถ</h2>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div id="success-message" style="display: none;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm8.707-8.707a1 1 0 00-1.414-1.414L11 10.586 7.707 8.293a1 1 0 00-1.414 1.414L9.586 12l-2.293 2.293a1 1 0 101.414 1.414L11 13.414l2.293 2.293a1 1 0 001.414-1.414L12.414 12l2.293-2.293a1 1 0 00-1.414-1.414L11 10.586 8.707 9.293a1 1 0 00-1.414 1.414L9.586 12l-2.293 2.293a1 1 0 101.414 1.414L11 13.414l2.293 2.293a1 1 0 001.414-1.414L12.414 12l2.293-2.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 ">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">พบข้อผิดพลาดในการกรอกข้อมูล</h3>
                    </div>
                </div>
                <div class="ml-8">
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('rentals.store') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- ข้อมูลส่วนตัว -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-primary px-6 py-4">
                    <h3 class="text-xl font-semibold text-secondary flex items-center ">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        ข้อมูลส่วนตัว
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="firstname" class="block text-sm font-medium text-secondary ">ชื่อ</label>
                            <input id="firstname"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="firstname" value="{{ old('firstname') }}" required
                                placeholder="กรอกชื่อ" />
                            @error('firstname')
                                <p class="mt-2 text-sm text-red-600 ">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="lastname" class="block text-sm font-medium text-gray-700 ">นามสกุล</label>
                            <input id="lastname"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="lastname" value="{{ old('lastname') }}" required
                                placeholder="กรอกนามสกุล" />
                            @error('lastname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="national_id"
                                class="block text-sm font-medium text-gray-700 ">รหัสประชาชน</label>
                            <input id="national_id"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="national_id" value="{{ old('national_id') }}" required
                                placeholder="กรอกรหัสประชาชน 13 หลัก" maxlength="17" />
                            <p class="mt-2 text-sm text-red-600" style="display: none;">เลขบัตรประชาชนไม่ถูกต้อง</p>
                            @error('national_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 ">เบอร์โทรศัพท์</label>
                            <input id="phone"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="tel" name="phone" value="{{ old('phone') }}" required
                                placeholder="กรอกเบอร์โทรศัพท์" maxlength="10" pattern="0[0-9]{9}" />
                            <p class="mt-2 text-sm text-red-600" style="display: none;">เบอร์โทรศัพท์ไม่ถูกต้อง</p>
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="address"
                                class="block text-sm font-medium text-gray-700 ">กรอกที่อยู่ปัจจุบัน</label>
                            <input id="address"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="address" value="{{ old('address') }}" required
                                placeholder="บ้านเลขที่ 70 หมู่บ้าน ศุภาลัย ซอย มังกร 1 ถนน หลวง หมู่ 5 ต.ผักแว่น อ.จังหาร จ.สกลนคร 67250" />
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลพยาน -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-primary px-6 py-4">
                    <h3 class="text-xl font-semibold text-secondary flex items-center ">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        ข้อมูลพยาน
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="witness_firstname"
                                class="block text-sm font-medium text-gray-700">ชื่อพยาน</label>
                            <input id="witness_firstname"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                type="text" name="witness_firstname" value="{{ old('witness_firstname') }}"
                                required placeholder="กรอกชื่อพยาน" />
                            @error('witness_firstname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="witness_lastname"
                                class="block text-sm font-medium text-gray-700">นามสกุลพยาน</label>
                            <input id="witness_lastname"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                type="text" name="witness_lastname" value="{{ old('witness_lastname') }}"
                                required placeholder="กรอกนามสกุลพยาน" />
                            @error('witness_lastname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">
                        <span class="text-red-500">*</span>
                        ใช้เพื่อลงนามในสัญญาเช่า โดยใช้บุคคลที่โดยสารมาด้วยหรือบิดา มารดา ญาติพี่น้อง เพื่อน
                    </p>
                </div>
            </div>

            <!-- ข้อมูลการเช่า -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-primary px-6 py-4">
                    <h3 class="text-xl font-semibold text-secondary flex items-center ">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        ข้อมูลการเช่า
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700">วันที่และเวลาเริ่มเช่า *</label>
                            <input id="start_date"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="start_date" value="{{ old('start_date') }}" required
                                placeholder="คลิกเพื่อเลือกวันที่และเวลา" readonly />
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700">วันที่และเวลาสิ้นสุดการเช่า *</label>
                            <input id="end_date"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="end_date" value="{{ old('end_date') }}" required
                                placeholder="คลิกเพื่อเลือกวันที่และเวลา" readonly />
                            @error('end_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="start_location" class="block text-sm font-medium text-gray-700">สถานที่รับรถ
                                *</label>
                            <input id="start_location"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="start_location" value="{{ old('start_location') }}" required
                                placeholder="เช่น สนามบินดอนเมือง, เมกะบางนา, โรงแรมในกรุงเทพ" />
                            @error('start_location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="end_location" class="block text-sm font-medium text-gray-700">สถานที่ส่งรถ
                                *</label>
                            <input id="end_location"
                                class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                type="text" name="end_location" value="{{ old('end_location') }}" required
                                placeholder="เช่น สนามบินดอนเมือง, เมกะบางนา, โรงแรมในกรุงเทพ" />
                            @error('end_location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                </div>
            </div>



            <!-- อัปโหลดรูปภาพ -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-primary px-6 py-4">
                    <h3 class="text-xl font-semibold text-secondary flex items-center ">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        อัปโหลดรูปภาพ
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="selfie_image"
                                class="block text-sm font-medium text-gray-700">ถ่ายรูปเซลฟี่กับบัตรประชาชน *</label>
                            <div class="relative">
                                <input id="selfie_image"
                                    class="block w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 hover:border-pink-400 cursor-pointer"
                                    type="file" name="selfie_image" accept="image/*" required />
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm text-gray-600">คลิกเพื่อเลือกไฟล์</p>
                                    </div>
                                </div>
                            </div>
                            @error('selfie_image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="national_id_image"
                                class="block text-sm font-medium text-gray-700">รูปถ่ายบัตรประชาชน *</label>
                            <div class="relative">
                                <input id="national_id_image"
                                    class="block w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 hover:border-pink-400 cursor-pointer"
                                    type="file" name="national_id_image" accept="image/*" required />
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm text-gray-600">คลิกเพื่อเลือกไฟล์</p>
                                    </div>
                                </div>
                            </div>
                            @error('national_id_image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="driver_license_image"
                                class="block text-sm font-medium text-gray-700">รูปถ่ายใบขับขี่</label>
                            <div class="relative">
                                <input id="driver_license_image"
                                    class="block w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200 hover:border-pink-400 cursor-pointer"
                                    type="file" name="driver_license_image" accept="image/*" />
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="text-center">
                                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm text-gray-600">คลิกเพื่อเลือกไฟล์</p>
                                    </div>
                                </div>
                            </div>
                            @error('driver_license_image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit"
                    class="inline-flex items-center px-8 py-4 bg-primary border border-transparent rounded-2xl text-secondary font-semibold text-lg uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl ">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    บันทึกข้อมูลการเช่ารถ
                </button>
            </div>
        </form>
    </div>
</body>

</html>
