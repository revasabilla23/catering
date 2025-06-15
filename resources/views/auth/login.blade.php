@extends('layout.blank')

@section('title', 'Login')

<head>
    <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --light: #f9f9f9;
            --blue: #0088ff;
            --light-blue: #cfe8ff;
            --grey: #eee;
            --dark-grey: #aaaaaa;
            --dark: #342e37;
            --primary-color: #0088ff;
        }

        .dark {
            --light: #1a202c;
            --blue: #63b3ed;
            --light-blue: #2c3e50;
            --grey: #2d3748;
            --dark-grey: #4a5568;
            --dark: #f7fafc;
            --primary-color: #63b3ed;
        }

        body {
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--grey) 100%);
            transition: background 0.3s ease;
        }

        .dark body {
            background: linear-gradient(135deg, var(--light) 0%, var(--dark-grey) 100%);
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .dark .login-card {
            background-color: rgba(26, 32, 44, 0.95);
            border: 1px solid rgba(74, 85, 104, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .input-icon {
            color: var(--primary-color);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0066cc 100%);
            box-shadow: 0 4px 6px rgba(0, 136, 255, 0.2);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0066cc 0%, var(--primary-color) 100%);
            box-shadow: 0 6px 8px rgba(0, 136, 255, 0.3);
            transform: translateY(-1px);
        }

        .theme-toggle {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }
    </style>
</head>

<body class="min-h-screen antialiased" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    init() {
        this.$watch('darkMode', val => {
            localStorage.setItem('theme', val ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', val);
        });
        document.documentElement.classList.toggle('dark', this.darkMode);
    },
    toggleTheme() {
        this.darkMode = !this.darkMode;
    }
}" :class="{ 'dark': darkMode }">

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md relative">
        <!-- Login Card -->
        <div class="login-card rounded-xl overflow-hidden transition-all duration-300">
            <!-- Theme Toggle Button -->
            <div class="theme-toggle">
                <button @click="toggleTheme" aria-label="Toggle theme"
                    class="p-2 rounded-full bg-white dark:bg-gray-700 shadow-md hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707" />
                    </svg>
                </button>
            </div>

            <!-- Header -->
            <div class="p-8 text-center">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('img/dapuremak.png') }}" alt="DapurEmak Logo" class="w-20 h-20 object-contain">
                </div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">DapurEmak</h1>
                <p class="text-gray-600 dark:text-gray-300">Solusi Kenyang Massal</p>
            </div>

            <!-- Form -->
            <div class="px-8 pb-8">
                <h2 class="text-xl font-semibold text-center text-gray-700 dark:text-gray-200 mb-6">Masuk ke Akun Anda</h2>

                @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-lg">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-gray-100"
                                placeholder="isi email disini" required autofocus>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-gray-100"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-lg text-white font-medium btn-login transition-all duration-300">
                        Masuk
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-600 text-center">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    © {{ date('Y') }} DapurEmak. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
</html>