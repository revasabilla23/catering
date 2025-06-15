<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title') | DapurEmak</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <script>
        (function() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = localStorage.getItem('theme');

            if (theme === 'dark' || (theme === null && prefersDark)) {
                document.documentElement.classList.add('dark'); // Terapkan ke <html>
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Variabel untuk mode terang (default) */
        :root {
            --light: #f9f9f9;
            --blue: #abd5ff;
            --light-blue: #cfe8ff;
            --grey: #eee;
            --dark-grey: #aaaaaa;
            --dark: #342e37;
            --red: #db504a;
            --primary-color: #0088ff;
        }

        /* Variabel untuk mode gelap, diterapkan ketika <html> memiliki kelas 'dark' */
        :root.dark {
            --light: #2d3748;
            /* Latar belakang komponen yang lebih gelap */
            --blue: #2c5282;
            /* Biru yang lebih gelap */
            --light-blue: #2c3e50;
            /* Biru yang sangat gelap untuk sidebar */
            --grey: #1a202c;
            /* Latar belakang utama halaman */
            --dark-grey: #4a5568;
            /* Abu-abu terang untuk dark mode */
            --dark: #f7fafc;
            /* Teks menjadi terang */
            --red: #e53e3e;
            /* Merah yang lebih cerah */
            --primary-color: #63b3ed;
            /* Biru terang untuk teks/ikon utama */
        }

        /* Transisi warna yang mulus */
        /* Hapus elemen yang sudah diatur oleh Tailwind atau terlalu spesifik */
        body,
        #sidebar,
        .main-header,
        #sidebar .side-menu li.active,
        #sidebar .side-menu li a,
        .main-content,
        .profile-dropdown,
        .profile-dropdown div {
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        /* Hapus penyesuaian warna spesifik untuk dark mode yang sudah bisa ditangani Tailwind */
        /* body.dark .bg-white, body.dark .text-gray-800, dll. dihapus */

        body {
            background: var(--grey);
            /* Menggunakan variabel CSS */
            color: var(--dark);
            /* Tambahkan ini agar warna teks default mengikuti tema */
            font-family: 'Lato', sans-serif;
            overflow-x: hidden;
            margin: 0;
        }

        /* Animasi transisi halaman */
        .main-content {
            animation: fadeIn 0.5s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sidebar styles */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--light);
            /* Menggunakan variabel CSS */
            z-index: 50;
            transition: transform 0.3s ease, width 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), background-color 0.3s ease;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Mobile sidebar behavior */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 70;
            }

            #sidebar.show-mobile {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            #sidebar.hide {
                /* Ini mungkin tidak diperlukan lagi dengan logika mobile-specific */
                transform: translateX(-100%);
            }

            .main-header {
                left: 0 !important;
            }

            #content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            /* Transparent active menu for mobile */
            #sidebar .side-menu li.active {
                background: rgba(249, 249, 249, 0.3) !important;
                position: relative;
            }

            #sidebar .side-menu li.active a {
                background: transparent !important;
            }

            #sidebar .side-menu li.active::before,
            #sidebar .side-menu li.active::after {
                display: none;
            }

            /* Header adjustments for mobile */
            .main-header {
                padding: 0 12px !important;
                gap: 8px !important;
            }

            .main-header h1 {
                font-size: 1rem !important;
                max-width: calc(100% - 150px) !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                margin: 0;
            }

            .mobile-menu-btn {
                margin-right: 0 !important;
            }
        }

        #sidebar.hide:not(.show-mobile) {
            width: 60px;
        }

        /* Logo dan brand name di sidebar */
        #sidebar .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.25rem;
            user-select: none;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        #sidebar .brand svg {
            width: 32px;
            height: 32px;
            fill: var(--primary-color);
            min-width: 32px;
            transition: all 0.3s ease;
        }

        #sidebar .side-menu {
            flex: 1;
            width: 100%;
            margin-top: 0;
            padding-top: 0;
            overflow-y: auto;
        }

        #sidebar .side-menu ul {
            margin-top: 20px;
        }

        #sidebar .side-menu li {
            height: 48px;
            background: transparent;
            margin-left: 6px;
            border-radius: 48px 0 0 48px;
            padding: 4px;
            transition: all 0.3s ease;
        }

        #sidebar .side-menu li.active {
            background: var(--grey);
            /* Menggunakan variabel CSS */
            position: relative;
        }

        #sidebar .side-menu li.active::before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            top: -40px;
            right: 0;
            box-shadow: 20px 20px 0 var(--grey);
            /* Menggunakan variabel CSS */
            z-index: -1;
        }

        #sidebar .side-menu li.active::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            bottom: -40px;
            right: 0;
            box-shadow: 20px -20px 0 var(--grey);
            /* Menggunakan variabel CSS */
            z-index: -1;
        }

        #sidebar .side-menu li a {
            width: 100%;
            height: 100%;
            background: transparent;
            display: flex;
            align-items: center;
            border-radius: 48px;
            font-size: 16px;
            color: var(--dark);
            /* Menggunakan variabel CSS */
            white-space: nowrap;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        /* Animasi saat menu sidebar ditekan */
        #sidebar .side-menu li a:active {
            transform: scale(0.95);
            transition: transform 0.1s ease;
        }

        #sidebar.hide .side-menu li a {
            width: calc(48px - (4px * 2));
            transition: all 0.3s ease;
        }

        #sidebar .side-menu li a .bx {
            min-width: calc(60px - ((4px + 6px) * 2));
            display: flex;
            justify-content: center;
        }

        /* Sidebar link text transition */
        .sidebar-text {
            transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
                transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            transform-origin: left center;
        }

        #sidebar.hide .sidebar-text {
            opacity: 0;
            transform: translateX(-10px);
        }

        #sidebar:not(.hide) .sidebar-text {
            opacity: 1;
            transform: translateX(0);
        }

        /* --- MODIFIKASI UNTUK LOGO SAAT SIDEBAR COLLAPSE --- */
        #sidebar.hide .brand {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
            gap: 0;
        }

        #sidebar.hide .brand svg {
            width: 32px;
            height: 32px;
            min-width: 32px;
        }

        #sidebar.hide .brand .sidebar-text {
            display: none;
        }

        /* Header yang bergeser ke kanan, mengikuti lebar sidebar */
        .main-header {
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            height: 56px;
            background: var(--light);
            /* Menggunakan variabel CSS */
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 16px;
            z-index: 60;
            transition: left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        #sidebar.hide ~ .main-header {
            left: 60px;
        }

        /* Mobile header adjustments */
        @media (max-width: 768px) {
            .main-header {
                justify-content: flex-start;
                padding: 0 12px;
            }

            .mobile-menu-btn {
                display: flex !important;
                margin-right: 8px;
            }

            .sidebar-toggle {
                display: none !important;
            }
        }

        /* Tombol toggle sidebar */
        .sidebar-toggle {
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: transparent;
            border: none;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }

        .mobile-menu-btn {
            display: none;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 4px;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }

        .sidebar-toggle:focus,
        .mobile-menu-btn:focus {
            outline: none;
        }

        /* Rotasi ikon saat sidebar disembunyikan */
        #sidebar.hide ~ .main-header .sidebar-toggle {
            transform: rotate(180deg);
        }

        /* Header judul */
        .main-header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            user-select: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 60vw;
            margin: 0;
        }

        /* Content area */
        #content {
            position: relative;
            margin-top: 56px;
            margin-left: 280px;
            width: calc(100% - 280px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 10;
        }

        #sidebar.hide ~ #content {
            margin-left: 60px;
            width: calc(100% - 60px);
        }

        /* Main content styling */
        .main-content {
            padding: 20px;
            min-height: calc(100vh - 56px);
        }

        /* Responsive content padding */
        @media (max-width: 640px) {
            .main-content {
                padding: 15px;
            }
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 65;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Profile Dropdown Styles */
        /* Pastikan transisi ini tetap ada untuk animasi mulus */
        .profile-dropdown {
            transition: all 0.3s ease;
        }

        .profile-img {
            transition: transform 0.2s ease;
        }

        .profile-img:hover {
            transform: scale(1.1);
        }

        .brand img.logo-image {
            width: 40px;
            height: auto;
            margin-right: 8px;
            vertical-align: middle;
        }
    </style>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body class="min-h-screen antialiased" x-data="{
    sidebarCollapsed: JSON.parse(localStorage.getItem('sidebarCollapsed')) || false,
    mobileSidebarOpen: false,
    isMobile: window.innerWidth < 768,

    /* === LOGIKA TEMA DENGAN PENYESUAIAN === */
    // darkMode sekarang menginisialisasi dari kelas 'dark' yang sudah diterapkan di awal
    darkMode: document.documentElement.classList.contains('dark'),

    init() {
        this.$watch('darkMode', val => {
            localStorage.setItem('theme', val ? 'dark' : 'light');
            // Toggle kelas 'dark' pada elemen <html>
            document.documentElement.classList.toggle('dark', val);
        });
        // Tidak perlu lagi menerapkan tema di sini karena sudah ditangani oleh script di <head>
    },

    toggleTheme() {
        this.darkMode = !this.darkMode;
    },
    /* === AKHIR PENYESUAIAN LOGIKA TEMA === */

    checkIfMobile() {
        this.isMobile = window.innerWidth < 768;
        if (!this.isMobile) {
            this.mobileSidebarOpen = false;
        }
    },
    toggleSidebar() {
        if (this.isMobile) {
            this.mobileSidebarOpen = !this.mobileSidebarOpen;
        } else {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', JSON.stringify(this.sidebarCollapsed));
        }
    },
    closeMobileSidebar() {
        this.mobileSidebarOpen = false;
    }
}" x-init="init()" @keyup.escape="closeMobileSidebar" @resize.window="checkIfMobile"
    :class="{'overflow-hidden': mobileSidebarOpen && isMobile}">
    @auth
        <aside id="sidebar" :class="{
            'hide': !isMobile && sidebarCollapsed,
            'show-mobile': mobileSidebarOpen
        }" x-cloak>

            <div class="brand">
                <img src="{{ asset('img/dapuremak.png') }}" alt="Logo DapurEmak" class="logo-image" />
                <span class="sidebar-text">DapurEmak</span>
            </div>

            <nav class="side-menu">
                <ul>
                    @if (Auth::user()->role === 'HRGA')
                        @include('layout.partials.sidebar-hrga', ['collapsed' => 'sidebarCollapsed'])
                    @elseif (Auth::user()->role === 'Karyawan')
                        @include('layout.partials.sidebar-karyawan', ['collapsed' => 'sidebarCollapsed'])
                    @elseif (Auth::user()->role === 'Koki')
                        @include('layout.partials.sidebar-koki', ['collapsed' => 'sidebarCollapsed'])
                    @endif
                </ul>
            </nav>
        </aside>

        <div class="sidebar-overlay" x-show="mobileSidebarOpen && isMobile" @click="closeMobileSidebar"
            :class="{'show': mobileSidebarOpen && isMobile}" x-transition x-cloak></div>
    @endauth

    <div class="main-header">
        <button x-show="isMobile" @click="toggleSidebar" class="mobile-menu-btn" aria-label="Toggle mobile menu"
            type="button">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <button x-show="!isMobile" @click="toggleSidebar" class="sidebar-toggle" aria-label="Toggle sidebar"
            type="button">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <h1>@yield('header')</h1>

        @auth
            <div class="ml-auto flex items-center gap-4">
                <button @click="toggleTheme"
                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200"
                    :class="{ 'bg-blue-600': darkMode, 'bg-gray-200': !darkMode }" aria-label="Toggle theme">
                    <span aria-hidden="true"
                        class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 flex items-center justify-center"
                        :class="{ 'translate-x-5': darkMode, 'translate-x-0': !darkMode }">
                        <span x-show="!darkMode" x-transition.opacity class="text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </span>
                        <span x-show="darkMode" x-cloak x-transition.opacity class="text-yellow-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                            </svg>
                        </span>
                    </span>
                </button>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition border border-gray-300 dark:hover:bg-gray-600 dark:border-gray-600"
                        aria-label="Profile menu">
                        @if (Auth::user()->profile && Auth::user()->profile->foto)
                            <img src="{{ asset('storage/' . Auth::user()->profile->foto) }}" alt="Profile"
                                class="w-10 h-10 rounded-full object-cover dark:border dark:border-gray-600">
                        @else
                            <div
                                class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold dark:bg-blue-700">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="border border-gray-300 rounded-full p-1 dark:border-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 text-gray-500 transition-transform duration-200 dark:text-gray-300"
                                :class="{ 'transform rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition x-cloak
                        class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg z-50 border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                        <div class="px-4 py-3 border-b bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <h3 class="font-medium text-gray-800 dark:text-gray-200">
                                @if (Auth::user()->role === 'Karyawan')
                                    Profil Karyawan
                                @elseif (Auth::user()->role === 'HRGA')
                                    Profil HRGA
                                @elseif (Auth::user()->role === 'Koki')
                                    Profil Koki
                                @else
                                    Profil Pengguna
                                @endif
                            </h3>
                        </div>

                        <div class="p-4">
                            @if (Auth::user()->profile)
                                @if (Auth::user()->profile->foto)
                                    <div class="flex justify-center mb-4">
                                        <img src="{{ asset('storage/' . Auth::user()->profile->foto) }}"
                                            alt="Foto Profil"
                                            class="w-20 h-20 rounded-full object-cover border dark:border-gray-600">
                                    </div>
                                @endif

                                <dl class="space-y-3 text-sm">
                                    <div class="flex items-start">
                                        <dt class="w-28 font-medium text-gray-500 dark:text-gray-300">Nama:</dt>
                                        <dd class="flex-1 text-gray-800 dark:text-gray-50">
                                            {{ Auth::user()->profile->name }}</dd>
                                    </div>
                                    @if (Auth::user()->role === 'Karyawan')
                                        <div class="flex items-start">
                                            <dt class="w-28 font-medium text-gray-500 dark:text-gray-300">Jenis
                                                Kelamin:</dt>
                                            <dd class="flex-1 text-gray-800 dark:text-gray-50">
                                                {{ Auth::user()->profile->gender }}</dd>
                                        </div>
                                        <div class="flex items-start">
                                            <dt class="w-28 font-medium text-gray-500 dark:text-gray-300">NIK:</dt>
                                            <dd class="flex-1 text-gray-800 dark:text-gray-50">
                                                {{ Auth::user()->profile->nik }}</dd>
                                        </div>
                                        <div class="flex items-start">
                                            <dt class="w-28 font-medium text-gray-500 dark:text-gray-300">Tanggal Lahir:
                                            </dt>
                                            <dd class="flex-1 text-gray-800 dark:text-gray-50">
                                                {{ \Carbon\Carbon::parse(Auth::user()->profile->tanggalLahir)->format('d-m-Y') }}
                                            </dd>
                                        </div>
                                    @endif
                                    <div class="flex items-start">
                                        <dt class="w-28 font-medium text-gray-500 dark:text-gray-300">Alamat:</dt>
                                        <dd class="flex-1 text-gray-800 dark:text-gray-50">
                                            {{ Auth::user()->profile->address }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-28 font-medium text-gray-500 dark:text-gray-300">No. Telepon:</dt>
                                        <dd class="flex-1 text-gray-800 dark:text-gray-50">
                                            {{ Auth::user()->profile->noTelepon }}</dd>
                                    </div>
                                </dl>
                            @else
                                <div class="text-center py-4 text-sm text-red-500 dark:text-red-300">
                                    Profil belum dilengkapi
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endauth

    </div>

    <div id="content" :class="{
        'ml-[280px]': !isMobile && !sidebarCollapsed,
        'ml-[60px]': !isMobile && sidebarCollapsed,
        'ml-0': isMobile
    }">
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/html5-qrcode" crossorigin="anonymous"></script>

    @yield('scripts')
</body>

</html>