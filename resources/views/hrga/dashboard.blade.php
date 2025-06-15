@extends('layout.app')

@section('title', 'HRGA')
@section('header', 'Dashboard Hrga')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8"> {{-- Added responsive padding --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6"> {{-- Adjusted for better stacking on small screens --}}
        <h2 class="text-2xl font-bold dark:text-gray-100 mb-2 sm:mb-0">Selamat Datang, {{ Auth::user()->profile->name ?? 'HRGA' }}</h2> {{-- Added margin bottom for smaller screens --}}
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Informasi Pesanan Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-t-4 border-blue-500 flex flex-col dark:border-b dark:border-gray-700">
            <div class="px-6 py-4 flex-grow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-white uppercase tracking-wider">Informasi Pesanan</h3>
                        <p class="text-2xl font-bold mt-1 dark:text-gray-100">{{ $data['pesanan_hari_ini'] }} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Pesanan</span></p>
                    </div>
                    <div class="bg-blue-100 dark:bg-gray-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-4 space-y-2">
                    @foreach($shifts as $shift)
                    <div class="flex justify-between items-center text-sm dark:text-white">
                        <span class="font-medium">Shift {{ $shift->namaShift }}</span>
                        <span>
                            {{ $pesananPerShift[$shift->IdShift] ?? 0 }} Pesanan
                            @if(($pesananPerShift[$shift->IdShift] ?? 0) > 0)
                                <span class="text-green-500 ml-1">✓</span>
                            @else
                                <span class="text-red-500 ml-1">✗</span>
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center dark:text-white">
                        <span class="text-sm font-medium">Belum Diverifikasi</span>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-500">{{ $unverifiedOrders }} pesanan</span>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 dark:bg-gray-800">
                <a href="{{ route('hrga.pesanan.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Kelola Pesanan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Informasi Karyawan Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-t-4 border-green-500 flex flex-col dark:border-b dark:border-gray-700">
            <div class="px-6 py-4 flex-grow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-white uppercase tracking-wider">Informasi Karyawan</h3>
                        <p class="text-2xl font-bold mt-1 dark:text-gray-100">{{ $data['total_karyawan'] }} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Karyawan</span></p>
                    </div>
                    <div class="bg-green-100 dark:bg-gray-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between items-center text-sm dark:text-gray-300">
                        <span class="font-medium">Karyawan Aktif</span>
                        <span class="font-semibold text-green-600 dark:text-green-500">{{ $activeEmployees }} orang</span>
                    </div>
                    <div class="flex justify-between items-center text-sm dark:text-gray-300">
                        <span class="font-medium">Karyawan Non-Aktif</span>
                        <span class="font-semibold text-red-600 dark:text-red-500">{{ $inactiveEmployees }} orang</span>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-sm font-medium mb-2 dark:text-white">Distribusi per Shift</h4>
                    <div class="space-y-1">
                        @foreach($employeePerShift as $shift)
                        <div class="flex justify-between items-center text-xs dark:text-gray-400">
                            <span>Shift {{ $shift->namaShift }}</span>
                            <span>{{ $shift->total }} orang</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 dark:bg-gray-800">
                <a href="{{ route('hrga.karyawan.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                    Kelola Karyawan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Informasi Konsumsi Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-t-4 border-yellow-500 flex flex-col dark:border-b dark:border-gray-700">
            <div class="px-6 py-4 flex-grow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-white uppercase tracking-wider">Informasi Konsumsi</h3>
                        <p class="text-2xl font-bold mt-1 dark:text-gray-100">{{ $data['konsumsi_hari_ini'] }} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Konsumsi</span></p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-gray-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="9" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="12" cy="12" r="4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-4 space-y-2">
                    @foreach($consumptionPerShift as $shift)
                        <div class="flex justify-between items-center text-sm dark:text-gray-300">
                            <span class="font-medium">Shift {{ $shift->namaShift }}</span>
                            <span>
                                {{ $shift->total }} konsumsi
                                @php
                                    $pesanan = $pesananPerShift[$shift->IdShift] ?? 0;
                                    $percentage = $pesanan > 0 ? round(min(($shift->total / $pesanan) * 100, 100)) : 0;
                                @endphp
                                <span class="{{ $percentage >= 80 ? 'text-green-500' : ($percentage >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                    ({{ $percentage }}%)
                                </span>
                            </span>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center dark:text-gray-300">
                        <span class="text-sm font-medium">Menu Paling Banyak</span>
                        <span class="text-sm font-semibold">{{ $mostConsumedMenu->namaMenu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1 text-xs dark:text-gray-400">
                        <span>Total Konsumsi</span>
                        <span>{{ $mostConsumedMenu->total ?? 0 }} kali</span>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 dark:bg-gray-800">
                <a href="{{ route('hrga.konsumsi') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors">
                    Monitoring Konsumsi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Informasi Report Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-t-4 border-purple-500 flex flex-col dark:border-b dark:border-gray-700">
            <div class="px-6 py-4 flex-grow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-white uppercase tracking-wider">Informasi Report</h3>
                        <p class="text-2xl font-bold mt-1 dark:text-gray-100">3 <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Jenis</span></p>
                    </div>
                    <div class="bg-purple-100 dark:bg-gray-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-4 space-y-3">
                    <div class="flex items-start">
                        <div class="bg-purple-100 dark:bg-gray-700 p-1 rounded mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Harian</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Detail konsumsi per hari</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-purple-100 dark:bg-gray-700 p-1 rounded mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Mingguan</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rekap 7 hari terakhir</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-purple-100 dark:bg-gray-700 p-1 rounded mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Bulanan</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Analisis bulan berjalan</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium dark:text-gray-300">Format Export</span>
                        <div class="flex space-x-1">
                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-600 dark:text-gray-300 rounded">PDF</span>
                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-600 dark:text-gray-300 rounded">Excel</span>
                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-600 dark:text-gray-300 rounded">CSV</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 dark:bg-gray-800">
                <a href="{{ route('hrga.report') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-md transition-colors">
                    Buat Report
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Aktivitas Terkini Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden lg:col-span-2 dark:border dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg font-semibold dark:text-gray-100">Aktivitas Terkini</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start"> {{-- Added items-start for better vertical alignment on small screens --}}
                        <div class="flex-shrink-0 mr-3">
                            <div class="bg-{{ $activity['color'] }}-100 dark:bg-gray-700 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $activity['icon'] }}" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $activity['title'] }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-white">
                                {{ $activity['description'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Actions Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden dark:border dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-lg font-semibold dark:text-gray-100">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <a href="{{ route('hrga.karyawan.create') }}" class="group flex items-center p-3 border dark:border-gray-600 rounded-lg hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 dark:hover:border-blue-500 transition-colors">
                        <div class="bg-blue-100 dark:bg-gray-900 p-2 rounded-full mr-3 group-hover:bg-blue-200 dark:group-hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Tambah Karyawan Baru</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Input data karyawan baru</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('hrga.pesanan.create') }}" class="group flex items-center p-3 border dark:border-gray-600 rounded-lg hover:border-green-500 hover:bg-green-50 dark:hover:bg-gray-700 dark:hover:border-green-500 transition-colors">
                        <div class="bg-green-100 dark:bg-gray-900 p-2 rounded-full mr-3 group-hover:bg-green-200 dark:group-hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Buat Pesanan Baru</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jadwalkan pesanan makanan</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('hrga.karyawan.index') }}" class="group flex items-center p-3 border dark:border-gray-600 rounded-lg hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-gray-700 dark:hover:border-purple-500 transition-colors">
                        <div class="bg-purple-100 dark:bg-gray-900 p-2 rounded-full mr-3 group-hover:bg-purple-200 dark:group-hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Rotasi Shift Karyawan</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Update shift secara manual</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('hrga.report') }}" class="group flex items-center p-3 border dark:border-gray-600 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 dark:hover:bg-gray-700 dark:hover:border-yellow-500 transition-colors">
                        <div class="bg-yellow-100 dark:bg-gray-900 p-2 rounded-full mr-3 group-hover:bg-yellow-200 dark:group-hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">Generate Report</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Buat laporan konsumsi</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Jadwal Pesanan Mendatang Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8 dark:border dark:border-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between"> {{-- Adjusted for better stacking on small screens --}}
                <h3 class="text-lg font-semibold dark:text-gray-100 mb-2 sm:mb-0">Jadwal Pesanan Mendatang</h3> {{-- Added margin bottom for smaller screens --}}
                <a href="{{ route('hrga.pesanan.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Lihat Semua</a>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto"> {{-- Crucial for responsive tables --}}
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Shift</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Menu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($upcomingOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ \Carbon\Carbon::parse($order->tanggalPesanan)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->shift->namaShift ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->menu->namaMenu ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->JumlahPesanan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->statusPesanan ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                    {{ $order->statusPesanan ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('hrga.pesanan.edit', $order->IdPesanan) }}" method="GET">
                                    <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        View
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada jadwal pesanan mendatang
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection