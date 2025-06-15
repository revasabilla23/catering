@extends('layout.app')

@section('title', 'Laporan Konsumsi')
@section('header', 'Laporan Konsumsi')

@section('content')
<div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 mt-2">
    <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-8 border-b border-gray-300 dark:border-gray-600 pb-3">
        Laporan Konsumsi ({{ ucfirst($filter) }})
    </h2>

    <form method="GET" action="{{ route('hrga.report') }}" 
          class="flex flex-col sm:flex-row gap-4 sm:items-center mb-8 max-w-xl">
        <select name="filter" 
                class="w-full sm:w-40 border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700
                       focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
        </select>

        <input type="date" name="tanggal" 
               value="{{ $tanggal->toDateString() }}" 
               class="w-full sm:w-48 border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700
                      focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />

        <button type="submit" 
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold
                       px-6 py-2 rounded-md shadow-md transition duration-300">
            Tampilkan
        </button>
    </form>

    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-center">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 tracking-wider">Shift</th>
                    <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 tracking-wider">Jumlah Konsumsi</th>
                    <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 tracking-wider">Jumlah Pesanan</th>
                    <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 tracking-wider">Sisa</th>
                    <th class="px-6 py-3 font-semibold text-gray-600 dark:text-gray-300 tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($data as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-3 whitespace-nowrap text-gray-800 dark:text-gray-200">{{ $row['tanggal'] }}</td>
                        <td class="px-6 py-3 whitespace-nowrap text-gray-800 dark:text-gray-200">{{ $row['shift'] }}</td>
                        <td class="px-6 py-3 whitespace-nowrap font-medium text-green-600 dark:text-green-400">{{ $row['jumlah_konsumsi'] }}</td>
                        <td class="px-6 py-3 whitespace-nowrap font-medium text-blue-600 dark:text-blue-400">{{ $row['jumlah_pesanan'] }}</td>
                        <td class="px-6 py-3 whitespace-nowrap font-medium text-yellow-600 dark:text-yellow-400">{{ $row['sisa'] }}</td>
                        <td class="px-6 py-3 whitespace-nowrap">
                            <form method="GET" action="{{ route('hrga.download-konsumsi') }}" 
                                  class="flex items-center justify-center space-x-2">
                                <input type="hidden" name="tanggal" value="{{ $row['tanggal'] }}">
                                <input type="hidden" name="shift" value="{{ $row['shift'] }}">
                                <select name="format" 
                                        class="text-xs border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700
                                               focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                                    <option value="csv">CSV</option>
                                    <option value="excel">Excel</option>
                                    <option value="pdf">PDF</option>
                                </select>
                                <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold
                                               px-3 py-1 rounded-md shadow-sm transition duration-200">
                                    Unduh
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-gray-500 dark:text-gray-400 italic">
                            Tidak ada data yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection