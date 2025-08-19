<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">ยินดีต้อนรับสู่ระบบเช่ารถ</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="text-lg font-semibold text-blue-800 mb-3">เช่ารถ</h4>
                            <p class="text-blue-600 mb-4">กรอกข้อมูลเพื่อทำการเช่ารถ</p>
                            <a href="{{ route('rentals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                เริ่มต้นเช่ารถ
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <h4 class="text-lg font-semibold text-green-800 mb-3">สถานะการเช่า</h4>
                            <p class="text-green-600 mb-4">ตรวจสอบสถานะการเช่ารถของคุณ</p>
                            <button class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                ดูสถานะ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>