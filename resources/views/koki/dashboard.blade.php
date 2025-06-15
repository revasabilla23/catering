@extends('layout.app')

@section('title', 'Koki')
@section('header', 'Dashboard Koki')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                Selamat Bertugas, {{ auth()->user()->profile->name ?? 'Pengguna' }}!
            </h1>
            <p class="text-gray-600 mt-2 dark:text-gray-300">Pantau konsumsi hari ini dari semua shift secara realtime</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 dark:bg-gray-700 dark:border-blue-700">
                <h3 class="text-gray-500 font-medium dark:text-gray-300">Total Pesanan Hari Ini</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2 dark:text-blue-400">{{ $totalPesanan ?? 0 }}</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 dark:bg-gray-700 dark:border-green-700">
                <h3 class="text-gray-500 font-medium dark:text-gray-300">Sudah Scan QR</h3>
                <p class="text-3xl font-bold text-green-600 mt-2 dark:text-green-400">{{ $totalScan ?? 0 }}</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 dark:bg-gray-700 dark:border-red-700">
                <h3 class="text-gray-500 font-medium dark:text-gray-300">Sisa Pesanan</h3>
                <p class="text-3xl font-bold text-red-600 mt-2 dark:text-red-400">{{ $sisaPesanan ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar Karyawan yang Sudah Scan QR Hari Ini</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Shift</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">Waktu Scan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-700 dark:divide-gray-600">
                        @isset($karyawanScan)
                            @forelse ($karyawanScan as $scan)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $scan->user->profile->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $scan->shift->namaShift ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($scan->waktuScan)->format('H:i:s') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada yang scan hari ini
                                    </td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-red-500 dark:text-red-400">
                                    Data belum tersedia
                                </td>
                            </tr>
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection