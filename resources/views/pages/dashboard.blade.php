<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบเช่ารถ - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <!-- Add New Button -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('rentals.index') }}"
                            class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>ดูข้อมูลการเช่ารถ</span>
                        </a>
                        <a href="{{ route('cars.index') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                            <span>จัดการรถยนต์</span>
                        </a>
                        <a href="{{ route('rentals.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>เพิ่มรายการใหม่</span>
                        </a>
                    </div>

                    <!-- Title -->
                    <div class="flex-1 text-center">
                        <h1 class="text-3xl font-bold text-red-600">รายงานการเช่ารถทั้งหมด</h1>
                    </div>

                    <!-- Search and Logout -->
                    <div class="flex items-center space-x-4">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                ออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-car text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">รถทั้งหมด</p>
                                <p class="text-2xl font-semibold text-gray-900" id="totalCars">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">รถที่พร้อมใช้งาน</p>
                                <p class="text-2xl font-semibold text-gray-900" id="availableCars">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">กำลังเช่า</p>
                                <p class="text-2xl font-semibold text-gray-900" id="rentedCars">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <i class="fas fa-tools text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">อยู่ในการซ่อม</p>
                                <p class="text-2xl font-semibold text-gray-900" id="maintenanceCars">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Rentals and Available Cars -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Current Rentals -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                                การเช่ารถปัจจุบัน
                            </h2>
                        </div>
                        <div class="p-6">
                            <div id="currentRentalsList" class="space-y-3">
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                    <p>กำลังโหลดข้อมูล...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Cars -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-car text-green-600 mr-2"></i>
                                รถที่พร้อมใช้งาน
                            </h2>
                        </div>
                        <div class="p-6">
                            <div id="availableCarsList" class="space-y-3">
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                    <p>กำลังโหลดข้อมูล...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">การดำเนินการด่วน</h2>
                    </div>
                    <div class="p-6 text-center">
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('rentals.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                เพิ่มรายการเช่ารถใหม่
                            </a>
                            <a href="{{ route('rentals.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-list mr-2"></i>
                                ดูข้อมูลการเช่ารถทั้งหมด
                            </a>
                            <a href="{{ route('cars.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-car mr-2"></i>
                                จัดการรถยนต์
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // โหลดข้อมูลสถิติ
            loadStatistics();

            // โหลดข้อมูลการเช่ารถปัจจุบัน
            loadCurrentRentals();

            // โหลดข้อมูลรถที่พร้อมใช้งาน
            loadAvailableCars();
        });

        // โหลดข้อมูลสถิติ
        function loadStatistics() {
            fetch('/api/statistics')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalCars').textContent = data.total_cars || 0;
                    document.getElementById('availableCars').textContent = data.available_cars || 0;
                    document.getElementById('rentedCars').textContent = data.rented_cars || 0;
                    document.getElementById('maintenanceCars').textContent = data.maintenance_cars || 0;
                })
                .catch(error => {
                    console.error('Error loading statistics:', error);
                });
        }

        // โหลดข้อมูลการเช่ารถปัจจุบัน
        function loadCurrentRentals() {
            fetch('/api/current-rentals')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('currentRentalsList');

                    if (data.rentals && data.rentals.length > 0) {
                        let html = '';
                        data.rentals.forEach(rental => {
                            html += `
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-medium text-gray-900">${rental.full_name}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full ${
                                            rental.status === 'using' ? 'bg-blue-100 text-blue-800' : 
                                            rental.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            'bg-gray-100 text-gray-800'
                                        }">${rental.status_text}</span>
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><i class="fas fa-car mr-2"></i>${rental.car_full_name || 'ไม่ระบุ'} - ${rental.car_license_plate || 'ไม่ระบุ'}</p>
                                        <p><i class="fas fa-calendar mr-2"></i>${rental.formatted_start_date} - ${rental.formatted_end_date}</p>
                                        <p><i class="fas fa-phone mr-2"></i>${rental.phone}</p>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>ไม่มีการเช่ารถในปัจจุบัน</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading current rentals:', error);
                    document.getElementById('currentRentalsList').innerHTML = `
                        <div class="text-center text-red-500 py-8">
                            <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                            <p>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>
                        </div>
                    `;
                });
        }

        // โหลดข้อมูลรถที่พร้อมใช้งาน
        function loadAvailableCars() {
            fetch('/api/available-cars')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('availableCarsList');

                    if (data.cars && data.cars.length > 0) {
                        let html = '';
                        data.cars.forEach(car => {
                            html += `
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-medium text-gray-900">${car.full_name}</h3>
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">พร้อมใช้งาน</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <p><i class="fas fa-tag mr-2"></i>${car.brand_name}</p>
                                        <p><i class="fas fa-hashtag mr-2"></i>${car.license_plate}</p>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-car text-4xl mb-4"></i>
                                <p>ไม่มีรถที่พร้อมใช้งาน</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading available cars:', error);
                    document.getElementById('availableCarsList').innerHTML = `
                        <div class="text-center text-red-500 py-8">
                            <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                            <p>เกิดข้อผิดพลาดในการโหลดข้อมูล</p>
                        </div>
                    `;
                });
        }
    </script>
</body>

</html>
