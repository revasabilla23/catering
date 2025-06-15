@extends('layout.app')

@section('title', 'Manajemen Jadwal Pesanan')
@section('header', 'Jadwal Pesanan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Daftar Jadwal Pesanan</h2>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <form method="GET" action="{{ route('hrga.pesanan.index') }}" class="flex flex-wrap items-center gap-2">
            <input
                type="date"
                name="tanggal"
                value="{{ request('tanggal') }}"
                class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 px-3 py-2 rounded-md shadow-sm text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition"
            />
            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition"
            >
                Cari Tanggal
            </button>
            @if(request('tanggal'))
                <a href="{{ route('hrga.pesanan.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200 px-4 py-2 rounded-md font-medium transition"
                >
                    Reset
                </a>
            @endif
        </form>

        <a href="{{ route('hrga.pesanan.create') }}"
           class="inline-block bg-green-600 hover:bg-green-700 text-white text-center px-5 py-2 rounded-md font-semibold transition whitespace-nowrap"
        >
            Tambah Pesanan
        </a>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    @php
                        $headers = ['No', 'ID', 'Shift', 'Menu', 'Tanggal', 'Jumlah', 'Status', 'Verif At', 'Aksi'];
                    @endphp
                    @foreach ($headers as $header)
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($jadwalPesanan as $index => $pesanan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pesanan->IdPesanan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pesanan->Shift->namaShift ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pesanan->Menu->namaMenu ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pesanan->tanggalPesanan }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pesanan->JumlahPesanan }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                {{ $pesanan->statusPesanan == 1
                                    ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' }}">
                                {{ $pesanan->statusPesanan == 1 ? 'Verify' : 'Unverify' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $pesanan->VerifAt ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center whitespace-nowrap">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('hrga.pesanan.edit', $pesanan->IdPesanan) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm font-medium transition"
                                >
                                    View
                                </a>
                                <form action="{{ route('hrga.pesanan.destroy', $pesanan->IdPesanan) }}" method="POST" onsubmit="return confirm('Yakin hapus?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm font-medium transition"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-500 dark:text-gray-400 italic">Data pesanan tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection