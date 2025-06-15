{{-- Notifikasi Error --}}
@if ($errors->any())
    <div class="p-4 mb-4 text-sm text-red-800 bg-red-100 border border-red-300 rounded-lg dark:bg-red-800 dark:text-red-100 dark:border-red-600">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Nama --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Nama</label>
    <input type="text" name="name" value="{{ old('name', optional($user->profile)->name) }}"
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
        required>
</div>

{{-- Tanggal Lahir --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Tanggal Lahir</label>
    <input type="date" name="tanggalLahir" value="{{ old('tanggalLahir', optional($user->profile)->tanggalLahir) }}"
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
        required>
</div>

{{-- NIK --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">NIK</label>
    <input type="text" name="nik" value="{{ old('nik', optional($user->profile)->nik) }}"
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
        required>
</div>

{{-- No Telepon --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">No. Telepon</label>
    <input type="text" name="noTelepon" value="{{ old('noTelepon', optional($user->profile)->noTelepon) }}"
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
        required>
</div>

{{-- Alamat --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Alamat</label>
    <input type="text" name="address" value="{{ old('address', optional($user->profile)->address) }}"
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
        required>
</div>

{{-- Foto --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Foto</label>
    <input type="file" name="foto"
        class="block w-full text-base file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 dark:file:bg-blue-900 dark:file:text-blue-200 dark:hover:file:bg-blue-800 transition"
        {{ !isset($user->IdUsers) ? 'required' : '' }}>

    @if (optional($user->profile)->foto)
        <div class="mt-3">
            <img src="{{ asset('storage/' . $user->profile->foto) }}" alt="Foto Karyawan"
                class="w-24 h-24 rounded-lg object-cover border shadow dark:border-gray-600">
        </div>
    @endif
</div>

{{-- Shift --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Shift</label>
    <select name="IdShift" required
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition">
        <option value="">-- Pilih Shift --</option>
        @foreach ($shifts as $shift)
            <option value="{{ $shift->IdShift }}"
                {{ old('IdShift', $user->IdShift ?? '') == $shift->IdShift ? 'selected' : '' }}>
                {{ $shift->namaShift }}
            </option>
        @endforeach
    </select>
</div>

{{-- Email --}}
<div class="mb-4">
    <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
        class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
        required>
</div>

{{-- Password --}}
@if (!isset($user->IdUsers))
    <div class="mb-4">
        <label class="block mb-1 text-base font-medium text-gray-700 dark:text-gray-200">Password</label>
        <input type="password" name="password"
            class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 transition"
            required>
    </div>
@endif

{{-- Tombol --}}
<div class="mt-6 flex justify-between items-center">
    <a href="{{ route('hrga.karyawan.index') }}"
        class="inline-flex items-center px-4 py-2 text-base font-medium bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
        Kembali
    </a>
    <button type="submit"
        class="inline-flex items-center px-4 py-2 text-base font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-md transition dark:bg-blue-700 dark:hover:bg-blue-600">
        {{ $submit }}
    </button>
</div>