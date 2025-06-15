@extends('layout.app')

@section('title', 'Daftar Pesanan')
@section('header', 'Daftar Jadwal Pesanan Hari Ini')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 px-4 sm:px-6 lg:px-8">
    @foreach ($jadwal as $pesanan)
        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col justify-between p-6
                    dark:bg-gray-800 dark:border dark:border-gray-700"> {{-- Added dark mode classes here --}}
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4 dark:text-white"> {{-- Added dark mode class here --}}
                    Shift {{ $pesanan->Shift->namaShift }}
                </h2>
                <p class="text-gray-700 mb-2 dark:text-gray-300"> {{-- Added dark mode class here --}}
                    <span class="font-semibold">Tanggal:</span> 
                    {{ \Carbon\Carbon::parse($pesanan->tanggalPesanan)->format('d M Y') }}
                </p>
                <p class="text-gray-700 mb-1 dark:text-gray-300"> {{-- Added dark mode class here --}}
                    <span class="font-semibold">Menu:</span> {{ $pesanan->menu['namaMenu'] ?? '-' }}
                </p>
                <p class="text-gray-600 text-sm italic mb-6 dark:text-gray-400"> {{-- Added dark mode class here --}}
                    {{ $pesanan->menu['deskMenu'] ?? '' }}
                </p>
            </div>
            <a href="{{ route('koki.scanQr', $pesanan->IdPesanan) }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white text-center font-semibold rounded-lg px-6 py-3
                      transition-colors duration-300 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:ring-opacity-50
                      dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-500"> {{-- Added dark mode classes here --}}
                Scan QR
            </a>
        </div>
    @endforeach
</div>
@endsection