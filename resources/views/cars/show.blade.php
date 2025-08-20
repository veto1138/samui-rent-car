@extends('layouts.layout')

@section('head')
    <title>รายละเอียดรถยนต์</title>
@endsection

@section('content')
    <div class="min-h-screen">
        <div class="w-full mx-auto">
            <!-- Header -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('cars.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                                <i class="fas fa-arrow-left text-xl"></i>
                            </a>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">รายละเอียดรถยนต์</h2>
                                <p class="text-gray-600 mt-1">{{ $car->full_name }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('cars.edit', $car) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                แก้ไข
                            </a>
                            <a href="{{ route('cars.index') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                กลับ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Car Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Car Image -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">รูปภาพรถยนต์</h3>
                            @if ($car->image)
                                <div class="aspect-w-16 aspect-h-9 mb-4">
                                    <img src="{{ asset('storage/' . $car->image) }}" alt="รูปรถยนต์ {{ $car->full_name }}"
                                        class="w-full h-64 object-cover rounded-lg">
                                </div>
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-car text-gray-400 text-6xl mb-4"></i>
                                        <p class="text-gray-500">ไม่มีรูปภาพ</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Car Information -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">ข้อมูลรถยนต์</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- ชื่อรถยนต์ -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อรถยนต์</label>
                                    <div class="text-gray-900 font-medium">{{ $car->full_name }}</div>
                                </div>

                                <!-- รุ่นรถ -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">รุ่นรถ</label>
                                    <div class="text-gray-900 font-medium">{{ $car->brand_name }}</div>
                                </div>

                                <!-- ทะเบียนรถ -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">ทะเบียนรถ</label>
                                    <div class="text-gray-900 font-medium">{{ $car->license_plate }}</div>
                                </div>

                                <!-- สถานะรถ -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">สถานะรถ</label>
                                    <div>
                                        @php
                                            $statusClasses = [
                                                'available' => 'bg-green-100 text-green-800',
                                                'rented' => 'bg-blue-100 text-blue-800',
                                                'maintenance' => 'bg-yellow-100 text-yellow-800',
                                            ];

                                            $statusTexts = [
                                                'available' => 'พร้อมใช้งาน',
                                                'rented' => 'ถูกเช่า',
                                                'maintenance' => 'ซ่อมบำรุง',
                                            ];

                                            $class = $statusClasses[$car->status] ?? 'bg-gray-100 text-gray-800';
                                            $text = $statusTexts[$car->status] ?? 'ไม่ทราบสถานะ';
                                        @endphp
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded-full {{ $class }}">{{ $text }}</span>
                                    </div>
                                </div>

                                <!-- วันที่สร้าง -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">วันที่สร้าง</label>
                                    <div class="text-gray-900">{{ $car->created_at->format('d/m/Y H:i') }}</div>
                                </div>

                                <!-- วันที่อัปเดตล่าสุด -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">วันที่อัปเดตล่าสุด</label>
                                    <div class="text-gray-900">{{ $car->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rental History -->
                    @if ($car->rentals->count() > 0)
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">ประวัติการเช่า</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    ลูกค้า</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    วันที่เช่า</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    สถานะ</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($car->rentals->take(5) as $rental)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $rental->firstname }} {{ $rental->lastname }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $rentalStatusClasses = [
                                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                                'using' => 'bg-blue-100 text-blue-800',
                                                                'success' => 'bg-green-100 text-green-800',
                                                                'cancel' => 'bg-red-100 text-red-800',
                                                            ];

                                                            $rentalStatusTexts = [
                                                                'pending' => 'จอง',
                                                                'using' => 'กำลังใช้งาน',
                                                                'success' => 'เสร็จสิ้น',
                                                                'cancel' => 'ยกเลิก',
                                                            ];

                                                            $rentalClass =
                                                                $rentalStatusClasses[$rental->status] ??
                                                                'bg-gray-100 text-gray-800';
                                                            $rentalText =
                                                                $rentalStatusTexts[$rental->status] ?? 'ไม่ทราบสถานะ';
                                                        @endphp
                                                        <span
                                                            class="px-2 py-1 text-xs font-medium rounded-full {{ $rentalClass }}">{{ $rentalText }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if ($car->rentals->count() > 5)
                                    <div class="mt-4 text-center">
                                        <p class="text-sm text-gray-500">แสดง 5 รายการล่าสุด จากทั้งหมด
                                            {{ $car->rentals->count() }} รายการ</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">ประวัติการเช่า</h3>
                                <div class="text-center py-8">
                                    <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500">ยังไม่มีประวัติการเช่า</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
