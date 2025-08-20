@extends('layouts.layout')

@section('head')
    <title>รายการจองรถ</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/datatables-custom.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="min-h-screen">
        <!-- Main Content -->
        <div class="">
            <div class="w-full mx-auto">
                <!-- DataTable Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">รายการจองรถทั้งหมด</h2>
                            {{-- <div class="flex space-x-3">
                                <a href="{{ route('google-calendar.index') }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                    <i class="fas fa-calendar-alt mr-2"></i>จัดการ Google Calendar
                                </a>
                            </div> --}}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table id="rentalsTable" class="w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ลำดับ</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ชื่อ-นามสกุล</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            เบอร์โทร</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            รถที่จอง</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            วันที่เริ่ม</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            วันที่สิ้นสุด</th>
                                        {{-- <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            จำนวนวัน</th> --}}
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ราคาเช่า</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            สถานะ</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ไฟล์แนบ</th>
                                        {{-- <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        พนักงาน</th> --}}
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- DataTables จะเติมข้อมูลที่นี่ -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="deleteModal"
        style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-2 text-center">
                    <h3 class="text-lg font-medium text-gray-900">ยืนยันการลบ</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            คุณต้องการลบข้อมูลการจองรถนี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button type="button"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                            onclick="closeDeleteModal()">
                            ยกเลิก
                        </button>
                        <button type="button"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                            id="confirmDelete">
                            ลบข้อมูล
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            console.log('Rentals page loaded');

            // ตรวจสอบว่ามี delete button หรือไม่
            setTimeout(function() {
                var deleteButtons = $('.delete-btn');
                console.log('Found delete buttons:', deleteButtons.length);
                deleteButtons.each(function(index) {
                    console.log('Delete button', index, 'data-id:', $(this).data('id'));
                });
            }, 1000);

            // กำหนดค่า DataTable
            var table = $('#rentalsTable').DataTable({
                processing: true,
                serverSide: false, // ใช้ client-side processing
                ajax: {
                    url: '{{ route('rentals.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.ajax = true;
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถโหลดข้อมูลได้ กรุณาลองใหม่อีกครั้ง'
                        });
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                        width: '70px',
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.firstname + ' ' + row.lastname;
                        },
                        width: '180px'
                    },
                    {
                        data: 'phone',
                        render: function(data, type, row) {
                            return data || '-';
                        },
                        width: '140px'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return row.car_brand ?
                                `<div class="text-sm">${row.car_brand} (${row.car_license_plate})</div>` :
                                '-';
                        },
                        width: '160px'
                    },
                    {
                        data: 'start_date',
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('th-TH') : '-';
                        },
                        width: '120px'
                    },
                    {
                        data: 'end_date',
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('th-TH') : '-';
                        },
                        width: '120px'
                    },
                    // {
                    //     data: 'rental_days',

                    //     width: '100px',
                    //     className: 'text-center',
                    //     orderable: true,
                    //     render: function(data, type, row) {
                    //         return data ?
                    //             `<div class="text-center px-2 py-[2px] bg-secondary text-white rounded-md text-sm">${data} วัน</div>` :
                    //             '-';
                    //     },
                    // },
                    {
                        data: 'rent_price',
                        render: function(data, type, row) {
                            return data ? '฿' + parseFloat(data).toLocaleString('th-TH') : '-';
                        },
                        width: '120px',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            let statusText = '';
                            let statusClass = '';
                            switch (data) {
                                case 'pending':
                                    statusText = 'รอจอง';
                                    statusClass = 'bg-orange-400 text-white';
                                    break;
                                case 'booked':
                                    statusText = 'จองแล้ว';
                                    statusClass = 'bg-yellow-400 text-white';
                                    break;
                                case 'using':
                                    statusText = 'กำลังใช้งาน';
                                    statusClass = 'bg-blue-400 text-white';
                                    break;
                                case 'success':
                                    statusText = 'เสร็จสิ้น';
                                    statusClass = 'bg-green-400 text-white';
                                    break;
                                case 'cancel':
                                    statusText = 'ยกเลิก';
                                    statusClass = 'bg-red-400 text-white';
                                    break;
                                default:
                                    statusText = 'ไม่ทราบสถานะ';
                                    statusClass = 'bg-gray-600 text-white';
                            }
                            return `<div class="px-2 py-1 text-xs font-medium rounded-md ${statusClass}">${statusText}</div>`;
                        },
                        width: '130px'
                    },
                    {
                        data: 'files',
                        orderable: false,
                        searchable: false,
                        width: '150px'
                    },
                    // {
                    //     data: 'employee',
                    //     width: '120px',
                    //     orderable: false
                    // },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let calendarButton = '';
                            if (row.google_calendar_event_id) {
                                // ถ้ามีใน Google Calendar แล้ว
                                calendarButton = `
                                    <button onclick="viewInCalendar('${row.id}')" 
                                            title="ดูใน Google Calendar" 
                                            class="bg-green-500 w-11 h-11 hover:bg-green-600 text-white p-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-calendar-check"></i>
                                    </button>
                                `;
                            } else {
                                // ถ้ายังไม่มีใน Google Calendar
                                calendarButton = `
                                    <button onclick="addToCalendar('${row.id}')" 
                                            title="เพิ่มลง Google Calendar" 
                                            class="bg-purple-500 w-11 h-11 hover:bg-purple-600 text-white p-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>
                                `;
                            }

                            return `
                             <div class="flex space-x-2">
                                 <a href="/rentals/${row.id}/edit" 
                                    title="แก้ไข">
                                    <button class="bg-yellow-400 w-11 h-11 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200">
                                     <i class="fas fa-edit"></i>    
                                    </button>
                                 </a>
                                 <a href="/rentals/export-pdf-single/${row.id}" 
                                    title="ส่งออก PDF">
                                    <button class="bg-blue-500 w-11 h-11 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors duration-200" 
                                            data-id="${row.id}" title="ส่งออก PDF">
                                     <i class="fas fa-file-export"></i>
                                 </button>
                                 </a>
                                 ${calendarButton}
                                 <button class="bg-red-500 w-11 h-11 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200 delete-btn" 
                                         data-id="${row.id}" title="ลบ">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         `;
                        },
                        orderable: false,
                        searchable: false,
                        width: '120px'
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                language: {
                    "sProcessing": "กำลังประมวลผล...",
                    "sLengthMenu": "แสดง _MENU_ รายการ",
                    "sZeroRecords": "ไม่พบข้อมูล",
                    "sEmptyTable": "ไม่มีข้อมูลในตาราง",
                    "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
                    "sInfoFiltered": "(กรองจาก _MAX_ รายการทั้งหมด)",
                    "sInfoPostFix": "",
                    "sSearch": "ค้นหา:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "กำลังโหลด...",
                    "oPaginate": {
                        "sFirst": "<i class='fas fa-angle-double-left'></i>",
                        "sPrevious": "<i class='fas fa-angle-left'></i>",
                        "sNext": "<i class='fas fa-angle-right'></i>",
                        "sLast": "<i class='fas fa-angle-double-right'></i>"
                    },
                    "oAria": {
                        "sSortAscending": ": เปิดใช้งานการเรียงลำดับจากน้อยไปมาก",
                        "sSortDescending": ": เปิดใช้งานการเรียงลำดับจากมากไปน้อย"
                    }
                },
                responsive: true,
                dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4"<"flex-1"l><"flex-1"f>>' +
                    '<"overflow-x-auto"tr>' +
                    '<"flex flex-col sm:flex-row justify-between items-center mt-4"<"flex-1"i><"flex-1"p>>',
            });

            // จัดการการลบข้อมูล
            $(document).on('click', '.delete-btn', function() {
                var rentalId = $(this).data('id');
                console.log('Delete button clicked for rental ID:', rentalId);

                // เก็บ rental ID ไว้ใน global variable
                window.currentDeleteRentalId = rentalId;

                showDeleteModal();
            });

            // จัดการการยืนยันการลบ
            $('#confirmDelete').on('click', function() {
                var rentalId = window.currentDeleteRentalId;
                console.log('Confirming delete for rental ID:', rentalId);

                if (!rentalId) {
                    console.error('No rental ID found');
                    return;
                }

                // แสดง loading
                $('#confirmDelete').prop('disabled', true).text('กำลังลบ...');

                $.ajax({
                    url: '/rentals/' + rentalId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Delete successful:', response);
                        closeDeleteModal();
                        table.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'ข้อมูลการจองรถถูกลบเรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', xhr.responseText);
                        closeDeleteModal();

                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถลบข้อมูลได้: ' + (xhr.responseJSON
                                ?.message || error),
                            confirmButtonText: 'ตกลง'
                        });
                    },
                    complete: function() {
                        // Reset button
                        $('#confirmDelete').prop('disabled', false).text('ลบข้อมูล');
                    }
                });
            });

            // จัดการการส่งออกข้อมูล
            $('#exportBtn').click(function() {
                window.location.href = '{{ route('rentals.export') }}';
            });

            // เพิ่ม loading indicator
            $(document).on('ajaxStart', function() {
                $('body').append(
                    '<div id="loading" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"><div class="bg-white p-4 rounded-lg"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div></div></div>'
                );
            });

            $(document).on('ajaxComplete', function() {
                $('#loading').remove();
            });
        });

        // Modal functions
        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
            console.log('Delete modal shown');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            console.log('Delete modal hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Google Calendar Functions
        function addToCalendar(rentalId) {
            if (!confirm('คุณต้องการเพิ่มการเช่ารถนี้ลงใน Google Calendar หรือไม่?')) {
                return;
            }

            // Show loading
            $('body').append(
                '<div id="loading" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50"><div class="bg-white p-4 rounded-lg"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div></div></div>'
            );

            $.ajax({
                url: '/google-calendar/rentals/' + rentalId + '/add',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#loading').remove();
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Reload table
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    $('#loading').remove();
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเพิ่มข้อมูลลงใน Google Calendar ได้'
                    });
                }
            });
        }

        function viewInCalendar(rentalId) {
            $.ajax({
                url: '/google-calendar/rentals/' + rentalId + '/view',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        window.open(response.calendar_url, '_blank');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถเปิด Google Calendar ได้'
                    });
                }
            });
        }
    </script>
@endsection
