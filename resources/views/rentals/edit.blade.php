@extends('layouts.layout')


@section('head')
    <link href="{{ asset('css/datatables-custom.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
@endsection

@section('content')
    <div class="min-h-screen">
        <!-- Main Content -->
        <div class="py-8">
            <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Form Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4">
                        <h2 class="text-lg font-semibold text-gray-900">ข้อมูลการเช่ารถ</h2>
                    </div>

                    <form method="POST" action="{{ route('rentals.update', $rental->id) }}" enctype="multipart/form-data"
                        class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลส่วนตัว</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">
                                        ชื่อ <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="firstname" id="firstname"
                                        value="{{ old('firstname', $rental->firstname) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('firstname')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">
                                        นามสกุล <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="lastname" id="lastname"
                                        value="{{ old('lastname', $rental->lastname) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('lastname')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        เบอร์โทรศัพท์ <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" id="phone"
                                        value="{{ old('phone', $rental->phone) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- National ID -->
                                <div>
                                    <label for="national_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        เลขบัตรประชาชน <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="national_id" id="national_id"
                                        value="{{ old('national_id', $rental->national_id) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('national_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        ที่อยู่ <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="address" id="address" rows="3" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">{{ old('address', $rental->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Witness Information Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลพยาน</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Witness First Name -->
                                <div>
                                    <label for="witness_firstname" class="block text-sm font-medium text-gray-700 mb-2">
                                        ชื่อพยาน <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="witness_firstname" id="witness_firstname"
                                        value="{{ old('witness_firstname', $rental->witness_firstname) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('witness_firstname')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Witness Last Name -->
                                <div>
                                    <label for="witness_lastname" class="block text-sm font-medium text-gray-700 mb-2">
                                        นามสกุลพยาน <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="witness_lastname" id="witness_lastname"
                                        value="{{ old('witness_lastname', $rental->witness_lastname) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('witness_lastname')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Car Information Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลรถ</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Car Selection -->
                                <div>
                                    <label for="car_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        เลือกรถยนต์
                                    </label>
                                    <select name="car_id" id="car_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                        onchange="updateCarInfo()">
                                        <option value="">-- เลือกรถยนต์ --</option>
                                        @foreach ($cars as $car)
                                            <option value="{{ $car->id }}" data-brand="{{ $car->brand_name }}"
                                                data-license="{{ $car->license_plate }}"
                                                data-full-name="{{ $car->full_name }}"
                                                {{ old('car_id', $rental->car_id) == $car->id ? 'selected' : '' }}>
                                                {{ $car->full_name }} - {{ $car->license_plate }} ({{ $car->status }})
                                            </option>
                                        @endforeach

                                        <!-- Debug info -->
                                        <script>
                                            console.log('Rental car_id: {{ $rental->car_id }}');
                                            console.log('Available cars: {!! json_encode($cars->pluck('id', 'id')) !!}');
                                            console.log('Rental car_brand: {{ $rental->car_id }}');
                                            console.log('Rental car_license_plate: {{ $rental->car_license_plate }}');
                                            console.log('Rental car_full_name: {{ $rental->car_full_name }}');

                                            // Debug: ตรวจสอบการเปรียบเทียบ
                                            @foreach ($cars as $car)
                                                console.log(
                                                    'Car {{ $car->id }}: {{ $car->id }} == {{ $rental->car_id }} = {{ $car->id == $rental->car_id }}'
                                                );
                                            @endforeach

                                            // ตรวจสอบว่า option ไหนถูกเลือก
                                            const carSelect = document.getElementById('car_id');
                                            if (carSelect) {
                                                console.log('Selected option value:', carSelect.value);
                                                console.log('Selected option text:', carSelect.options[carSelect.selectedIndex]?.text);

                                                // ตรวจสอบทุก option
                                                for (let i = 0; i < carSelect.options.length; i++) {
                                                    const option = carSelect.options[i];
                                                    console.log(`Option ${i}: value="${option.value}", text="${option.text}", selected=${option.selected}`);
                                                }
                                            }
                                        </script>
                                    </select>
                                    @error('car_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Car Brand (Auto-filled) -->
                                <div>
                                    <label for="car_brand" class="block text-sm font-medium text-gray-700 mb-2">
                                        ยี่ห้อรถ
                                    </label>
                                    <input type="text" name="car_brand" id="car_brand"
                                        value="{{ old('car_brand', $rental->car_brand) }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('car_brand')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Car License Plate (Auto-filled) -->
                                <div>
                                    <label for="car_license_plate" class="block text-sm font-medium text-gray-700 mb-2">
                                        ทะเบียนรถ
                                    </label>
                                    <input type="text" name="car_license_plate" id="car_license_plate"
                                        value="{{ old('car_license_plate', $rental->car_license_plate) }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('car_license_plate')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Car Full Name (Auto-filled) -->
                                <div>
                                    <label for="car_full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        ชื่อรถยนต์
                                    </label>
                                    <input type="text" name="car_full_name" id="car_full_name"
                                        value="{{ old('car_full_name', $rental->car_full_name ?? '') }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Rental Details Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">รายละเอียดการเช่า</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        วันที่และเวลาเริ่มเช่า <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="start_date" id="start_date"
                                        value="{{ old('start_date', $rental->start_date ? $rental->start_date->format('Y-m-d H:i') : '') }}"
                                        required placeholder="คลิกเพื่อเลือกวันที่และเวลา" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                        วันที่และเวลาสิ้นสุดการเช่า <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="end_date" id="end_date"
                                        value="{{ old('end_date', $rental->end_date ? $rental->end_date->format('Y-m-d H:i') : '') }}"
                                        required placeholder="คลิกเพื่อเลือกวันที่และเวลา" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Start Location -->
                                <div>
                                    <label for="start_location" class="block text-sm font-medium text-gray-700 mb-2">
                                        สถานที่รับรถ <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="start_location" id="start_location"
                                        value="{{ old('start_location', $rental->start_location) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('start_location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Location -->
                                <div>
                                    <label for="end_location" class="block text-sm font-medium text-gray-700 mb-2">
                                        สถานที่คืนรถ <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="end_location" id="end_location"
                                        value="{{ old('end_location', $rental->end_location) }}" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('end_location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Rent Price -->
                                <div>
                                    <label for="rent_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        ราคาเช่า
                                    </label>
                                    <input type="number" name="rent_price" id="rent_price" min="0"
                                        step="0.01" value="{{ old('rent_price', $rental->rent_price) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('rent_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Insurance Price -->
                                <div>
                                    <label for="insurance_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        ราคาประกัน
                                    </label>
                                    <input type="number" name="insurance_price" id="insurance_price" min="0"
                                        step="0.01" value="{{ old('insurance_price', $rental->insurance_price) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    @error('insurance_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-4">
                                <span class="text-red-500">*</span>
                                กรุณาเลือกวันที่ เวลา และสถานที่ที่ชัดเจน เพื่อความสะดวกในการจัดส่งรถ (เวลาเลือกได้ทีละ 10
                                นาที)
                            </p>
                        </div>

                        <!-- Owner Information Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลเจ้าของรถ</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Owner First Name -->
                                <div>
                                    <label for="owner_full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        ชื่อเจ้าของรถ
                                    </label>
                                    <select name="owner_full_name" id="owner_full_name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">เลือกชื่อเจ้าของรถ</option>
                                        <option value="นายเทพทัต ทับทอง"
                                            {{ old('owner_full_name', $rental->owner_full_name) == 'นายเทพทัต ทับทอง' ? 'selected' : '' }}>
                                            นายเทพทัต ทับทอง
                                        </option>
                                        <option value="นายทรงยศ ทับทอง"
                                            {{ old('owner_full_name', $rental->owner_full_name) == 'นายทรงยศ ทับทอง' ? 'selected' : '' }}>
                                            นายทรงยศ ทับทอง
                                        </option>
                                    </select>
                                    @error('owner_full_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Owner Witness First Name -->
                                <div>
                                    <label for="owner_witness_full_name"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        ชื่อพยานเจ้าของรถ
                                    </label>

                                    <select name="owner_witness_full_name" id="owner_witness_full_name" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">เลือกชื่อเจ้าของรถ</option>
                                        <option value="นางสาวเพียงโพยม ทองมั่น"
                                            {{ old('owner_witness_full_name', $rental->owner_witness_full_name) == 'นางสาวเพียงโพยม ทองมั่น' ? 'selected' : '' }}>
                                            นางสาวเพียงโพยม ทองมั่น
                                        </option>
                                        <option value="นางสาวเมธิกา ทองมีเพชร"
                                            {{ old('owner_witness_full_name', $rental->owner_witness_full_name) == 'นางสาวเมธิกา ทองมีเพชร' ? 'selected' : '' }}>
                                            นางสาวเมธิกา ทองมีเพชร
                                        </option>
                                    </select>
                                    @error('owner_witness_full_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">สถานะ</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        สถานะ <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">เลือกสถานะ</option>
                                        <option value="pending"
                                            {{ old('status', $rental->status) == 'pending' ? 'selected' : '' }}>
                                            รอจอง
                                        </option>
                                        <option value="booked"
                                            {{ old('status', $rental->status) == 'booked' ? 'selected' : '' }}>
                                            จองแล้ว
                                        </option>
                                        <option value="using"
                                            {{ old('status', $rental->status) == 'using' ? 'selected' : '' }}>
                                            กำลังใช้งาน
                                        </option>
                                        <option value="success"
                                            {{ old('status', $rental->status) == 'success' ? 'selected' : '' }}>
                                            เสร็จสิ้น
                                        </option>
                                        <option value="cancel"
                                            {{ old('status', $rental->status) == 'cancel' ? 'selected' : '' }}>
                                            ยกเลิก
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Write Address -->
                                <div>
                                    <label for="write_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        เขียนที่
                                    </label>
                                    {{-- <input type="text" name="write_address" id="write_address"
                                        value="{{ old('write_address', $rental->write_address) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"> --}}
                                    <select name="write_address" id="write_address" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">เขียนที่</option>
                                        <option value="154/2 หมู่ 2 ตำบลแม่น้ำ อำเภอเกาะสมุย จังหวัดสุราษฎร์ธานี 84330"
                                            {{ old('write_address', $rental->write_address) == '154/2 หมู่ 2 ตำบลแม่น้ำ อำเภอเกาะสมุย จังหวัดสุราษฎร์ธานี 84330' ? 'selected' : '' }}>
                                            154/2 หมู่ 2 ตำบลแม่น้ำ อำเภอเกาะสมุย จังหวัดสุราษฎร์ธานี 84330
                                        </option>
                                        <option value="70 หมู่ 2 ตำบลท่าฉาง อำเภอท่าฉาง จังหวัดสุราษฎร์ธานี 84150"
                                            {{ old('write_address', $rental->write_address) == '70 หมู่ 2 ตำบลท่าฉาง อำเภอท่าฉาง จังหวัดสุราษฎร์ธานี 84150' ? 'selected' : '' }}>
                                            70 หมู่ 2 ตำบลท่าฉาง อำเภอท่าฉาง จังหวัดสุราษฎร์ธานี 84150
                                        </option>
                                    </select>
                                    @error('write_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Files Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ไฟล์แนบ</h3>
                            <div class="space-y-4">
                                <!-- Current Files -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @if ($rental->selfie_image)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                รูป Selfie
                                            </label>
                                            <div class="bg-gray-50 p-3 rounded-md">
                                                <img src="{{ asset('storage/' . $rental->selfie_image) }}" alt="Selfie"
                                                    class="w-full h-32 object-cover rounded">
                                            </div>
                                        </div>
                                    @endif

                                    @if ($rental->national_id_image)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                รูปบัตรประชาชน
                                            </label>
                                            <div class="bg-gray-50 p-3 rounded-md">
                                                <img src="{{ asset('storage/' . $rental->national_id_image) }}"
                                                    alt="National ID" class="w-full h-32 object-cover rounded">
                                            </div>
                                        </div>
                                    @endif

                                    @if ($rental->driver_license_image)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                รูปใบขับขี่
                                            </label>
                                            <div class="bg-gray-50 p-3 rounded-md">
                                                <img src="{{ asset('storage/' . $rental->driver_license_image) }}"
                                                    alt="Driver License" class="w-full h-32 object-cover rounded">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- New Files -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="selfie_image" class="block text-sm font-medium text-gray-700 mb-2">
                                            อัปโหลดรูป Selfie ใหม่
                                        </label>
                                        <input type="file" name="selfie_image" id="selfie_image" accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        @error('selfie_image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="national_id_image"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            อัปโหลดรูปบัตรประชาชนใหม่
                                        </label>
                                        <input type="file" name="national_id_image" id="national_id_image"
                                            accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        @error('national_id_image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="driver_license_image"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            อัปโหลดรูปใบขับขี่ใหม่
                                        </label>
                                        <input type="file" name="driver_license_image" id="driver_license_image"
                                            accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        @error('driver_license_image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="{{ route('rentals.index') }}"
                                class="bg-secondary hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                                ยกเลิก
                                <i class="fas fa-times"></i>
                            </a>
                            <button type="submit"
                                class="bg-primary hover:bg-primary/80 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                                บันทึก
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // เรียก updateCarInfo เมื่อโหลดหน้าเพื่อแสดงข้อมูลรถที่เลือกไว้
            // ใช้ setTimeout เพื่อให้แน่ใจว่า DOM โหลดเสร็จแล้ว
            setTimeout(function() {
                console.log('DOM loaded, calling updateCarInfo...');
                updateCarInfo();

                // ตรวจสอบว่าข้อมูลถูกอัปเดตหรือไม่
                setTimeout(function() {
                    const carBrand = document.getElementById('car_brand');
                    const carLicense = document.getElementById('car_license_plate');
                    const carFullName = document.getElementById('car_full_name');

                    console.log('After updateCarInfo:');
                    console.log('car_brand value:', carBrand ? carBrand.value :
                        'element not found');
                    console.log('car_license_plate value:', carLicense ? carLicense.value :
                        'element not found');
                    console.log('car_full_name value:', carFullName ? carFullName.value :
                        'element not found');
                }, 200);
            }, 100);

            // ตั้งค่าเริ่มต้นจากค่าที่มีอยู่หรือเวลาปัจจุบัน
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
            let currentDateTime = getCurrentDateTime();
            let endDateTime = getEndDateTime(currentDateTime);

            // ถ้ามีค่าอยู่แล้ว ให้ใช้ค่านั้น
            if (startDateInput.value) {
                currentDateTime = new Date(startDateInput.value);
                if (endDateInput.value) {
                    endDateTime = new Date(endDateInput.value);
                } else {
                    endDateTime = getEndDateTime(currentDateTime);
                }
            }

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

            // Auto-hide flash messages
            setTimeout(function() {
                const successMessage = document.getElementById('success-message');
                const errorMessage = document.getElementById('error-message');

                if (successMessage) {
                    successMessage.style.display = 'none';
                }
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 5000);
        });

        // Function สำหรับอัปเดตข้อมูลรถยนต์อัตโนมัติ
        function updateCarInfo() {
            const carSelect = document.getElementById('car_id');
            const selectedOption = carSelect.options[carSelect.selectedIndex];

            console.log('updateCarInfo called');
            console.log('carSelect:', carSelect);
            console.log('selectedOption:', selectedOption);

            if (selectedOption && selectedOption.value) {
                // อัปเดตข้อมูลรถยนต์
                const brand = selectedOption.getAttribute('data-brand');
                const license = selectedOption.getAttribute('data-license');
                const fullName = selectedOption.getAttribute('data-full-name');

                console.log('Car data:', {
                    brand,
                    license,
                    fullName
                });

                document.getElementById('car_brand').value = brand || '';
                document.getElementById('car_license_plate').value = license || '';
                document.getElementById('car_full_name').value = fullName || '';
            } else {
                // ล้างข้อมูลเมื่อไม่เลือกรถ
                document.getElementById('car_brand').value = '';
                document.getElementById('car_license_plate').value = '';
                document.getElementById('car_full_name').value = '';
                console.log('No car selected, clearing fields');
            }
        }
    </script>
@endsection
