@extends('layout.app')

@section('title', 'Karyawan')
@section('header', 'Dashboard Karyawan')

@section('content')
<div class="max-w-sm mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Kartu QR --}}
    <div class="rounded-2xl shadow-lg p-6 text-center"
         :class="darkMode ? 'bg-gray-800' : 'bg-white'">
        <h2 class="text-xl font-semibold mb-4"
            :class="darkMode ? 'text-white' : 'text-gray-800'">QR Token Anda</h2>

        {{-- QR Code --}}
        <div id="qrcode" class="flex justify-center mb-4"></div>
        
        {{-- QR Info --}}
        <div id="qr-code" class="border rounded-lg p-4 text-sm mb-4"
             :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-300' : 'bg-gray-50 border-gray-300 text-gray-700'">
            Memuat QR...
        </div>
        
        <p class="text-sm" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">QR code ini diperbarui otomatis setiap 15 detik.</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fetchQrToken() {
        $.ajax({
            url: '{{ route('karyawan.generateQrAjax') }}',
            method: 'GET',
            success: function(data) {
                $('#qr-code').html(`
                    <div class="break-all text-sm text-left">
                        <p><span class="font-semibold">Token:</span><br>${data.token}</p>
                        <p class="mt-2"><span class="font-semibold">Expired:</span><br>${data.expired}</p>
                    </div>
                `);
            },
            error: function() {
                $('#qr-code').html(`<span class="text-red-500">Gagal memuat QR token.</span>`);
            }
        });
    }

    function updateQr() {
        $.ajax({
            url: "{{ route('karyawan.generateQrAjax') }}",
            method: "GET",
            success: function (response) {
                $('#qrcode').empty();
                new QRCode(document.getElementById("qrcode"), {
                    text: response.token,
                    width: 180,
                    height: 180,
                    // Menyesuaikan warna QR code berdasarkan tema
                    colorDark : document.body.classList.contains('dark') ? "#ffffff" : "#000000",
                    colorLight : document.body.classList.contains('dark') ? "#2d3748" : "#ffffff"
                });
            }
        });
    }

    $(document).ready(function () {
        fetchQrToken();
        updateQr();
        setInterval(() => {
            fetchQrToken();
            updateQr();
        }, 15000);

        // Menambahkan listener untuk perubahan tema agar QR Code ikut berubah warna
        new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
                if (mutation.attributeName === 'class' && mutation.target.tagName === 'BODY') {
                    updateQr(); // Panggil updateQr setiap kali class body berubah (tema gelap/terang)
                }
            });
        }).observe(document.body, { attributes: true });
    });
</script>
@endsection