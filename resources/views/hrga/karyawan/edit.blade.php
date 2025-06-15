@extends('layout.app')

@section('title', 'Manajemen Karyawan')
@section('header', 'Data Karyawan')

@section('content')
<div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded shadow dark:bg-gray-700 dark:border dark:border-gray-600">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 dark:text-gray-200">Data Karyawan</h2>

    <form action="{{ route('hrga.karyawan.update', $user->IdUsers) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('hrga.karyawan.form', ['submit' => 'Update', 'user' => $user])
    </form>

    {{-- Tombol Kirim Email --}}
    @if ($user->statusUsers !== 'aktif')
        <form action="{{ route('hrga.karyawan.send-email', $user->IdUsers) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition dark:bg-blue-700 dark:hover:bg-blue-600">
                Kirim Email Aktivasi
            </button>
        </form>
    @else
        <span class="inline-block mt-4 px-3 py-1 rounded bg-green-100 text-green-700 font-semibold dark:bg-green-800 dark:text-green-100">
            Status sudah aktif, email aktivasi sudah dikirim.
        </span>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif
    @endif
</div>
@endsection