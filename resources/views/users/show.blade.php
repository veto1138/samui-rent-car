@extends('layouts.layout')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ข้อมูลผู้ใช้งาน</h1>
            <div class="flex space-x-3">
                <a href="{{ route('users.edit', $user) }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>แก้ไข</span>
                </a>
                <a href="{{ route('users.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>กลับไป</span>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลพื้นฐาน</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ชื่อ</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">อีเมล</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">เบอร์โทรศัพท์</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'ไม่ระบุ' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">สถานะการยืนยันอีเมล</label>
                            <p class="mt-1">
                                @if ($user->email_verified_at)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        ยืนยันแล้ว
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        ยังไม่ยืนยัน
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลระบบ</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">วันที่สร้างบัญชี</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">อัปเดตล่าสุด</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID ผู้ใช้งาน</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($user->id === auth()->id())
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                นี่คือบัญชีของคุณเอง คุณสามารถแก้ไขข้อมูลได้ในหน้าโปรไฟล์
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
