@extends('layouts.layout')

@section('head')
    <title>จัดการ Google Calendar - Samui Rent Car</title>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">จัดการ Google Calendar</h2>
                            <p class="text-gray-600 mt-1">เชื่อมต่อและจัดการข้อมูลการเช่ารถใน Google Calendar</p>
                        </div>
                        <div class="flex space-x-3">
                            <button id="testConnectionBtn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-plug mr-2"></i>ทดสอบการเชื่อมต่อ
                            </button>
                            <button id="syncAllBtn"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-sync mr-2"></i>Sync ทั้งหมด
                            </button>
                            <button id="clearInvalidEventsBtn"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                <i class="fas fa-trash mr-2"></i>ลบ Event ID ที่ไม่ถูกต้อง
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Connection Status -->
            <div id="connectionStatus" class="bg-white shadow-sm rounded-lg overflow-hidden mb-6 hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div id="statusIcon" class="mr-3">
                            <i class="fas fa-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 id="statusTitle" class="text-lg font-medium"></h3>
                            <p id="statusMessage" class="text-gray-600"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">วิธีการตั้งค่า Google Calendar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">1. สร้าง Google Cloud Project</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• ไปที่ <a href="https://console.cloud.google.com" target="_blank"
                                        class="text-blue-600 hover:underline">Google Cloud Console</a></li>
                                <li>• สร้างโปรเจกต์ใหม่</li>
                                <li>• เปิดใช้งาน Google Calendar API</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">2. สร้าง Service Account</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• ไปที่ IAM & Admin > Service Accounts</li>
                                <li>• สร้าง Service Account ใหม่</li>
                                <li>• ดาวน์โหลดไฟล์ JSON credentials</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">3. แชร์ Calendar</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• เปิด Google Calendar</li>
                                <li>• แชร์ calendar กับ Service Account email</li>
                                <li>• ให้สิทธิ์ "Make changes to events"</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">4. อัปโหลด Credentials</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• อัปโหลดไฟล์ JSON ไปที่ storage/app/</li>
                                <li>• ตั้งชื่อไฟล์เป็น google-calendar-credentials.json</li>
                                <li>• ตั้งค่า GOOGLE_CALENDAR_ID ใน .env</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Environment Variables -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">การตั้งค่า Environment Variables</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-sm text-gray-700 mb-2">เพิ่มการตั้งค่าต่อไปนี้ในไฟล์ .env:</p>
                        <div class="bg-gray-800 text-green-400 p-3 rounded font-mono text-sm">
                            <div>GOOGLE_CALENDAR_ID=your_calendar_id_here</div>
                            <div>GOOGLE_CREDENTIALS_PATH=storage/app/google-calendar-credentials.json</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Rentals Status -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">สถานะการเชื่อมต่อ Google Calendar</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ลูกค้า</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        รถยนต์</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        วันที่เช่า</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        สถานะ</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody id="rentalsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- จะถูกเติมด้วย JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-xl">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                    <div class="text-gray-700">กำลังประมวลผล...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Test Connection
            document.getElementById('testConnectionBtn').addEventListener('click', function() {
                testConnection();
            });

            // Sync All Rentals
            document.getElementById('syncAllBtn').addEventListener('click', function() {
                syncAllRentals();
            });

            // Clear Invalid Event IDs
            document.getElementById('clearInvalidEventsBtn').addEventListener('click', function() {
                clearInvalidEventIds();
            });

            // Load rentals data
            loadRentalsData();
        });

        function testConnection() {
            showLoading();

            fetch('{{ route('google-calendar.test-connection') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    showConnectionStatus(data.success, data.message);
                })
                .catch(error => {
                    hideLoading();
                    showConnectionStatus(false, 'เกิดข้อผิดพลาดในการทดสอบการเชื่อมต่อ');
                    console.error('Error:', error);
                });
        }

        function syncAllRentals() {
            if (!confirm('คุณต้องการ sync การเช่ารถทั้งหมดลงใน Google Calendar หรือไม่?')) {
                return;
            }

            showLoading();

            fetch('{{ route('google-calendar.sync-all') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert(data.message + '\n\nสำเร็จ: ' + data.success_count + ' รายการ\nผิดพลาด: ' + data
                            .error_count + ' รายการ');
                        loadRentalsData(); // Reload data
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('เกิดข้อผิดพลาดในการ sync ข้อมูล');
                    console.error('Error:', error);
                });
        }

        function clearInvalidEventIds() {
            if (!confirm(
                    'คุณต้องการลบ Event ID ที่ไม่ถูกต้องออกจากฐานข้อมูลหรือไม่?\n\nการดำเนินการนี้จะลบ Event ID ที่ไม่สามารถเข้าถึงได้ใน Google Calendar'
                )) {
                return;
            }

            showLoading();

            fetch('{{ route('google-calendar.clear-invalid-events') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert(data.message + '\n\nลบ Event ID จำนวน: ' + data.cleared_count + ' รายการ');
                        loadRentalsData(); // Reload data
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('เกิดข้อผิดพลาดในการลบ Event ID');
                    console.error('Error:', error);
                });
        }

        function loadRentalsData() {
            fetch('/api/current-rentals')
                .then(response => response.json())
                .then(data => {
                    displayRentals(data.rentals);
                })
                .catch(error => {
                    console.error('Error loading rentals:', error);
                });
        }

        function displayRentals(rentals) {
            const tbody = document.getElementById('rentalsTableBody');
            tbody.innerHTML = '';

            rentals.forEach(rental => {
                const row = document.createElement('tr');

                // แสดงสถานะด้วยสีและข้อความ
                const statusInfo = getStatusInfo(rental.status);

                row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${rental.firstname} ${rental.lastname}</div>
                <div class="text-sm text-gray-500">${rental.phone}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${rental.car_brand || 'ไม่ระบุ'}</div>
                <div class="text-sm text-gray-500">${rental.car_license_plate || 'ไม่ระบุ'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${formatDate(rental.start_date)}</div>
                <div class="text-sm text-gray-500">ถึง ${formatDate(rental.end_date)}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusInfo.bgColor} ${statusInfo.textColor}">
                    ${statusInfo.text}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                ${rental.google_calendar_event_id ? 
                    `<button onclick="viewInCalendar(${rental.id})" class="text-blue-600 hover:text-blue-900 mr-2">ดูใน Calendar</button>
                                                                             <button onclick="removeFromCalendar(${rental.id})" class="text-red-600 hover:text-red-900">ลบออก</button>` :
                    `<button onclick="addToCalendar(${rental.id})" class="text-green-600 hover:text-green-900">เพิ่มลง Calendar</button>`
                }
            </td>
        `;
                tbody.appendChild(row);
            });
        }

        function addToCalendar(rentalId) {
            if (!confirm('คุณต้องการเพิ่มการเช่ารถนี้ลงใน Google Calendar หรือไม่?')) {
                return;
            }

            showLoading();

            fetch(`/google-calendar/rentals/${rentalId}/add`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert(data.message);
                        loadRentalsData(); // Reload data
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูลลง Calendar');
                    console.error('Error:', error);
                });
        }

        function removeFromCalendar(rentalId) {
            if (!confirm('คุณต้องการลบการเช่ารถนี้ออกจาก Google Calendar หรือไม่?')) {
                return;
            }

            showLoading();

            fetch(`/google-calendar/rentals/${rentalId}/remove`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        alert(data.message);
                        loadRentalsData(); // Reload data
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    alert('เกิดข้อผิดพลาดในการลบข้อมูลจาก Calendar');
                    console.error('Error:', error);
                });
        }

        function viewInCalendar(rentalId) {
            showLoading();

            fetch(`/google-calendar/rentals/${rentalId}/view`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        console.log('Calendar data:', data);
                        console.log('Opening calendar URL:', data.calendar_url);

                        // แสดงข้อมูลให้ user ดู
                        if (confirm('เปิด Google Calendar?\n\nEvent ID: ' + data.event_id + '\nURL: ' + data
                                .calendar_url)) {
                            window.open(data.calendar_url, '_blank');
                        }
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error in viewInCalendar:', error);
                    alert('เกิดข้อผิดพลาดในการเปิด Calendar: ' + error.message);
                });
        }

        function showConnectionStatus(success, message) {
            const statusDiv = document.getElementById('connectionStatus');
            const statusIcon = document.getElementById('statusIcon');
            const statusTitle = document.getElementById('statusTitle');
            const statusMessage = document.getElementById('statusMessage');

            if (success) {
                statusIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-2xl"></i>';
                statusTitle.textContent = 'เชื่อมต่อสำเร็จ';
                statusTitle.className = 'text-lg font-medium text-green-900';
                statusMessage.className = 'text-green-600';
            } else {
                statusIcon.innerHTML = '<i class="fas fa-times-circle text-red-500 text-2xl"></i>';
                statusTitle.textContent = 'เชื่อมต่อไม่สำเร็จ';
                statusTitle.className = 'text-lg font-medium text-red-900';
                statusMessage.className = 'text-red-600';
            }

            statusMessage.textContent = message;
            statusDiv.classList.remove('hidden');
        }

        function showLoading() {
            document.getElementById('loadingModal').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.add('hidden');
        }

        function formatDate(dateString) {
            if (!dateString) return 'ไม่ระบุ';
            const date = new Date(dateString);
            return date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getStatusInfo(status) {
            switch (status) {
                case 'pending':
                    return {
                        text: 'รอ',
                            bgColor: 'bg-yellow-100',
                            textColor: 'text-yellow-800'
                    };
                case 'using':
                    return {
                        text: 'ใช้งาน',
                            bgColor: 'bg-green-100',
                            textColor: 'text-green-800'
                    };
                case 'success':
                case 'returned':
                    return {
                        text: 'เสร็จสิ้น',
                            bgColor: 'bg-blue-100',
                            textColor: 'text-blue-800'
                    };
                case 'cancel':
                    return {
                        text: 'ยกเลิก',
                            bgColor: 'bg-red-100',
                            textColor: 'text-red-800'
                    };
                default:
                    return {
                        text: 'ไม่ระบุ',
                            bgColor: 'bg-gray-100',
                            textColor: 'text-gray-800'
                    };
            }
        }
    </script>
@endsection
