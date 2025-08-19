<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">       
    @endif
</head>
<body class="bg-gray-100">
    <div class="w-[500px] mx-auto border border-gray-300 rounded-lg px-10 py-10 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white">
        <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="w-24 h-24 block mx-auto">
        <div class="mt-6">
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 bg-red-100 rounded-md p-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif
            <form action="{{ route('login') }}" method="post">
                @csrf
                <h1 class="text-2xl font-medium text-left mb-4">เข้าสู่ระบบ</h1>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                    <input type="text" name="email" placeholder="Email" class="mt-1 block w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring-secondary sm:text-sm">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                    <input type="password" name="password" placeholder="Password" class="mt-1 block h-11 w-full rounded-md border-gray-300 shadow-sm focus:border-secondary focus:ring-secondary sm:text-sm">
                </div>
                <div class="mt-6">
                    <button type="submit" class="h-11 w-full text-white bg-secondary rounded hover:bg-secondary/90 transition-all duration-300">เข้าสู่ระบบ</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>