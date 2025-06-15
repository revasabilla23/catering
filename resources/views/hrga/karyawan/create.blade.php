@extends('layout.app')

@section('title', 'Manajemen Karyawan')
@section('header', 'Tambah Karyawan')

@section('content')
    <div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded shadow dark:bg-gray-700 dark:border dark:border-gray-600">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 dark:text-gray-200">Form Tambah Karyawan</h2>
        <form action="{{ route('hrga.karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('hrga.karyawan.form', ['submit' => 'Simpan', 'user' => new \App\Models\User()])
        </form>
    </div>
@endsection