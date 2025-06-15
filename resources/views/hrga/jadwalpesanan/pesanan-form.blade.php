@php
    $isEdit = isset($pesanan);
@endphp

<form action="{{ $isEdit ? route('hrga.pesanan.update', $pesanan->IdPesanan) : route('hrga.pesanan.store') }}" 
      method="POST" 
      class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg space-y-2 dark:bg-gray-700 dark:border dark:border-gray-600">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- Shift --}}
    <div>
        <label for="IdShift" class="block text-base font-semibold text-gray-700 mb-2 dark:text-gray-200">Shift</label>
        <select name="IdShift" id="IdShift" required
                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 dark:focus:border-blue-400 dark:focus:ring-blue-400 @error('IdShift') border-red-500 @enderror">
            <option value="" disabled {{ old('IdShift', $isEdit ? $pesanan->IdShift : '') == '' ? 'selected' : '' }}>Pilih Shift</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->IdShift }}"
                        {{ old('IdShift', $isEdit ? $pesanan->IdShift : '') == $shift->IdShift ? 'selected' : '' }}>
                    {{ $shift->namaShift }}
                </option>
            @endforeach
        </select>
        @error('IdShift')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Menu --}}
    <div>
        <label for="IdMenu" class="block text-base font-semibold text-gray-700 mb-2 dark:text-gray-200">Menu</label>
        <select name="IdMenu" id="IdMenu" required
                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 dark:focus:border-blue-400 dark:focus:ring-blue-400 @error('IdMenu') border-red-500 @enderror">
            <option value="" disabled {{ old('IdMenu', $isEdit ? $pesanan->IdMenu : '') == '' ? 'selected' : '' }}>Pilih Menu</option>
            @foreach ($menus as $menu)
                <option value="{{ $menu->IdMenu }}"
                        {{ old('IdMenu', $isEdit ? $pesanan->IdMenu : '') == $menu->IdMenu ? 'selected' : '' }}>
                    {{ $menu->namaMenu }}
                </option>
            @endforeach
        </select>
        @error('IdMenu')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tanggal Pesanan --}}
    <div>
        <label for="tanggalPesanan" class="block text-base font-semibold text-gray-700 mb-2 dark:text-gray-200">Tanggal Pesanan</label>
        <input type="date" name="tanggalPesanan" id="tanggalPesanan" required
               value="{{ old('tanggalPesanan', $isEdit ? $pesanan->tanggalPesanan : '') }}"
               class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 dark:focus:border-blue-400 dark:focus:ring-blue-400 @error('tanggalPesanan') border-red-500 @enderror">
        @error('tanggalPesanan')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Jumlah Pesanan --}}
    <div>
        <label for="JumlahPesanan" class="block text-base font-semibold text-gray-700 mb-2 dark:text-gray-200">Jumlah Pesanan</label>
        <input type="number" name="JumlahPesanan" id="JumlahPesanan" required min="1"
               value="{{ old('JumlahPesanan', $isEdit ? $pesanan->JumlahPesanan : '') }}"
               class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 dark:focus:border-blue-400 dark:focus:ring-blue-400 @error('JumlahPesanan') border-red-500 @enderror">
        @error('JumlahPesanan')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Status Pesanan --}}
    <div>
        <label for="statusPesanan" class="block text-base font-semibold text-gray-700 mb-2 dark:text-gray-200">Status Pesanan</label>
        <select name="statusPesanan" id="statusPesanan" required
                class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition dark:bg-gray-800 dark:border-gray-600 dark:text-gray-50 dark:focus:border-blue-400 dark:focus:ring-blue-400 @error('statusPesanan') border-red-500 @enderror">
            <option value="0" {{ old('statusPesanan', $isEdit ? $pesanan->statusPesanan : '') == '0' ? 'selected' : '' }}>Unverify</option>
            <option value="1" {{ old('statusPesanan', $isEdit ? $pesanan->statusPesanan : '') == '1' ? 'selected' : '' }}>Verify</option>
        </select>
        @error('statusPesanan')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex justify-end gap-3 pt-4 border-t mt-6 dark:border-gray-600">
        <a href="{{ route('hrga.pesanan.index') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded hover:bg-gray-300 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
            Batal
        </a>
        <button type="submit" 
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition dark:bg-blue-700 dark:hover:bg-blue-600">
            {{ $isEdit ? 'Update' : 'Simpan' }}
        </button>
    </div>
</form>