@extends('layouts.layout')

@section('head')
    <title>เพิ่มรถยนต์ใหม่</title>
@endsection

@section('content')
    <div class="min-h-screen">
        <div class="w-full mx-auto">
            <!-- Header -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        <a href="{{ route('cars.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">เพิ่มรถยนต์ใหม่</h2>
                            <p class="text-gray-600 mt-1">กรอกข้อมูลรถยนต์ที่ต้องการเพิ่ม</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ชื่อรถยนต์ -->
                            <div class="col-span-2">
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    ชื่อรถยนต์ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="เช่น Toyota Camry, Honda Civic">
                                @error('full_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- รุ่นรถ -->
                            <div>
                                <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    รุ่นรถ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="เช่น Camry, Civic">
                                @error('brand_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ทะเบียนรถ -->
                            <div>
                                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">
                                    ทะเบียนรถ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="license_plate" id="license_plate"
                                    value="{{ old('license_plate') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="เช่น กข-1234">
                                @error('license_plate')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- สถานะรถ -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    สถานะรถ <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">เลือกสถานะ</option>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>
                                        พร้อมใช้งาน</option>
                                    <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>ถูกเช่า
                                    </option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                        ซ่อมบำรุง</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- รูปภาพรถ -->
                            <div class="col-span-2">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    รูปภาพรถ
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50"
                                        id="imagePreview">
                                        <div class="text-center">
                                            <i class="fas fa-car text-gray-400 text-3xl mb-2"></i>
                                            <p class="text-sm text-gray-500">ไม่มีรูปภาพ</p>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="image" id="image" accept="image/*"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            onchange="previewImage(this)">
                                        <p class="text-sm text-gray-500 mt-1">รองรับไฟล์ JPG, PNG ขนาดไม่เกิน 2MB</p>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4  flex justify-end space-x-3">
                        <a href="{{ route('cars.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            ยกเลิก
                            <i class="fas fa-times ml-2"></i>
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-primary border border-transparent rounded-md text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            บันทึกข้อมูล
                            <i class="fas fa-save ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover rounded-lg">
                    `;
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-car text-gray-400 text-3xl mb-2"></i>
                        <p class="text-sm text-gray-500">ไม่มีรูปภาพ</p>
                    </div>
                `;
            }
        }
    </script>
@endsection
