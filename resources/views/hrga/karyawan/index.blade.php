@extends('layout.app')

@section('title', 'Manajemen Karyawan')
@section('header', 'Daftar Karyawan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2">
    <h2 class="text-3xl font-semibold text-gray-900 dark:text-white mb-8 tracking-wide">Daftar Karyawan</h2>

    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <form action="{{ route('hrga.karyawan.index') }}" method="GET" class="w-full sm:w-auto">
            <div class="flex space-x-3">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama karyawan..."
                    value="{{ request('search') }}"
                    class="border border-gray-300 rounded-md px-4 py-2 w-full sm:w-64 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                />
                <button 
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold shadow-md transition duration-200"
                >
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('hrga.karyawan.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md font-semibold shadow-md transition duration-200 flex items-center justify-center dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="flex space-x-4">
            <a href="{{ route('hrga.karyawan.create') }}" 
               class="inline-block bg-green-600 text-white text-center px-6 py-2 rounded-md font-semibold shadow-md hover:bg-green-700 transition duration-200">
                Tambah Karyawan
            </a>

            <form action="{{ route('hrga.karyawan.rotate-shift') }}" method="POST" 
                  onsubmit="return confirm('Yakin ingin memutar shift semua karyawan sekarang?')">
                @csrf
                <button 
                    type="submit" 
                    class="bg-purple-700 hover:bg-purple-900 text-white text-center  font-semibold py-2 px-6 rounded-md shadow-md transition duration-200"
                >
                    Putar Shift Manual
                </button>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">NIK</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Tanggal Lahir</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Shift</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">No. Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider max-w-xs dark:text-gray-300">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider dark:text-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($karyawan as $index => $k)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium dark:text-gray-200">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if (!empty($k->profile?->foto) && file_exists(public_path('storage/' . $k->profile->foto)))
                                <img 
                                    src="{{ asset('storage/' . $k->profile->foto) }}" 
                                    alt="Foto {{ $k->profile->name }}" 
                                    class="w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm dark:border-gray-600"
                                >
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $k->profile->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-normal max-w-xs text-sm text-gray-700 break-words dark:text-gray-300">{{ $k->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $k->profile->nik ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                            {{ $k->profile?->tanggalLahir ? \Carbon\Carbon::parse($k->profile->tanggalLahir)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $k->shift->namaShift ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">{{ $k->profile->noTelepon ?? '-' }}</td>
                        <td class="px-6 py-4 max-w-xs truncate text-sm text-gray-700 dark:text-gray-300" title="{{ $k->profile->address ?? '-' }}">
                            {{ $k->profile->address ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($k->statusUsers === 'aktif')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('hrga.karyawan.edit', $k->IdUsers) }}"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                    View
                                </a>

                                <form action="{{ route('hrga.karyawan.destroy', $k->IdUsers) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?');"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            TIdUsersak ada data karyawan ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection