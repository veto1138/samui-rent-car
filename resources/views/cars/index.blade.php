@extends('layouts.layout')

@section('head')
    <title>รายการรถยนต์</title>
    <link href="{{ asset('css/datatables-custom.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="min-h-screen">
        <!-- Main Content -->
        <div class="">
            <div class="w-full mx-auto">
                <!-- Header -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">รายการรถยนต์ทั้งหมด</h2>
                                <p class="text-gray-600 mt-1">จัดการข้อมูลรถยนต์ในระบบ</p>
                            </div>
                            <a href="{{ route('cars.create') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                เพิ่มรถยนต์ใหม่
                            </a>
                        </div>
                    </div>
                </div>

                <!-- DataTable Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900">ข้อมูลรถยนต์</h3>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table id="carsTable" class="w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ลำดับ</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            รูปภาพ</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ชื่อรถยนต์</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            รุ่นรถ</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ทะเบียนรถ</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            สถานะ</th>
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
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="deleteModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-2 text-center">
                    <h3 class="text-lg font-medium text-gray-900">ยืนยันการลบ</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            คุณต้องการลบข้อมูลรถยนต์นี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้
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
            // กำหนดค่า DataTable
            var table = $('#carsTable').DataTable({
                processing: true,
                serverSide: false, // ใช้ client-side processing
                ajax: {
                    url: '{{ route('cars.index') }}',
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
                        data: 'image_preview',
                        orderable: false,
                        searchable: false,
                        width: '100px',
                        className: 'text-center'
                    },
                    {
                        data: 'full_name',
                        width: '200px'
                    },
                    {
                        data: 'brand_name',
                        width: '150px'
                    },
                    {
                        data: 'license_plate',
                        width: '120px'
                    },
                    {
                        data: 'status_badge',
                        orderable: true,
                        searchable: true,
                        width: '130px',
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                             <div class="flex space-x-2 justify-center">
                                 <a href="/cars/${row.id}" 
                                    title="ดูรายละเอียด">
                                    <button class="bg-green-500 w-11 h-11 hover:bg-green-600 text-white p-2 rounded-lg transition-colors duration-200">
                                     <i class="fas fa-eye"></i>    
                                    </button>
                                 </a>
                                 <a href="/cars/${row.id}/edit" 
                                    title="แก้ไข">
                                    <button class="bg-yellow-400 w-11 h-11 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200">
                                     <i class="fas fa-edit"></i>    
                                    </button>
                                 </a>
                                 <button class="bg-red-500 w-11 h-11 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200 delete-btn" 
                                         data-id="${row.id}" title="ลบ">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         `;
                        },
                        orderable: false,
                        searchable: false,
                        width: '150px'
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
                var carId = $(this).data('id');
                showDeleteModal();

                $('#confirmDelete').off('click').on('click', function() {
                    $.ajax({
                        url: '/cars/' + carId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            closeDeleteModal();
                            table.ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'ข้อมูลรถยนต์ถูกลบเรียบร้อยแล้ว',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            closeDeleteModal();

                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถลบข้อมูลได้ กรุณาลองใหม่อีกครั้ง'
                            });
                        }
                    });
                });
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
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
