<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Flatpickr CSS & JS -->
    @yield('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>

    <nav
        class="bg-primary w-full h-16 fixed top-0 right-0 z-[999] border-b border-gray-200 flex grow items-center justify-end flex-row xl:px-6 transition-all duration-300">

        <div class="flex items-center justify-between w-full">
            <div>
                <ul class="flex items-center gap-6">
                    <li>
                        <a href="{{ route('rentals.index') }}">
                            <span>รายการจองรถ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cars.index') }}">
                            <span>รายการรถยนต์</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}">
                            <span>ผู้ใช้งาน</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="ml-2 ">
                            ออกจากระบบ
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </nav>


    <div
        class="w-full h-full transition-all duration-300 ease-in-out absolute z-10 pt-[70px] overflow-hidden  overflow-y-auto ">
        <div class=" p-4">
            <div class="border border-gray-200 rounded-md">
                @yield('content')
            </div>
        </div>
    </div>

</body>

</html>
