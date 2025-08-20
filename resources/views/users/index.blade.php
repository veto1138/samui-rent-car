@extends('layouts.layout')

@section('head')
    <title>จัดการผู้ใช้งาน</title>
    <link href="{{ asset('css/datatables-custom.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="min-h-screen">
        <!-- Main Content -->
        <div class="">
            <div class="w-full mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6 p-4">
                    <h1 class="text-2xl font-bold text-gray-900">จัดการผู้ใช้งาน</h1>
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>เพิ่มผู้ใช้งานใหม่</span>
                    </a>
                </div>

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                @if (session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                <!-- DataTable Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-900">รายการผู้ใช้งานทั้งหมด</h2>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table id="usersTable" class="w-full">
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
                                            อีเมล</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            เบอร์โทร</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            วันที่สร้าง</th>
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
                            คุณต้องการลบผู้ใช้งานนี้หรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้
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
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: false, // ใช้ client-side processing
                ajax: {
                    url: '{{ route('users.index') }}',
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
                        data: 'name',
                        width: '200px'
                    },
                    {
                        data: 'email',
                        width: '250px'
                    },
                    {
                        data: 'phone',
                        render: function(data, type, row) {
                            return data || 'ไม่ระบุ';
                        },
                        width: '150px'
                    },
                    {
                        data: 'created_at',
                        render: function(data, type, row) {
                            return data ? new Date(data).toLocaleDateString('th-TH') + ' ' +
                                new Date(data).toLocaleTimeString('th-TH', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) : '-';
                        },
                        width: '150px'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let currentUserId = {{ auth()->id() }};
                            let deleteButton = '';

                            if (row.id !== currentUserId) {
                                deleteButton = `
                                    <button class="bg-red-500 w-11 h-11 hover:bg-red-600 text-white p-2 rounded-lg transition-colors duration-200 delete-btn" 
                                            data-id="${row.id}" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                `;
                            }

                            return `
                             <div class="flex space-x-2">
                                 <a href="/users/${row.id}" 
                                    title="ดูข้อมูล">
                                    <button class="bg-blue-500 w-11 h-11 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors duration-200">
                                     <i class="fas fa-eye"></i>    
                                    </button>
                                 </a>
                                 <a href="/users/${row.id}/edit" 
                                    title="แก้ไข">
                                    <button class="bg-yellow-400 w-11 h-11 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors duration-200">
                                     <i class="fas fa-edit"></i>    
                                    </button>
                                 </a>
                                 ${deleteButton}
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
                var userId = $(this).data('id');
                showDeleteModal();

                $('#confirmDelete').off('click').on('click', function() {
                    $.ajax({
                        url: '/users/' + userId,
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
                                text: 'ผู้ใช้งานถูกลบเรียบร้อยแล้ว',
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
