@extends('layout.app')

@section('title', 'Scan QR')
@section('header', 'Scan QR Karyawan')

@section('content')
<style>
  #preview > video {
    /* Mengubah video kamera menjadi mode mirror */
    transform: scaleX(-1) !important; 
    /* Memastikan video memenuhi container */
    width: 100% !important; 
    height: 100% !important;
    object-fit: cover; /* Video akan menutupi seluruh area tanpa distorsi, memotong jika diperlukan */
  }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-4 sm:px-6 lg:px-8">

    <section class="rounded-xl shadow-md p-6 flex flex-col"
              :class="darkMode ? 'bg-gray-800' : 'bg-white'">
        <h3 class="text-xl font-semibold mb-6 border-b pb-2"
            :class="darkMode ? 'text-white border-gray-600' : 'text-gray-900 border-gray-200'">Kamera Scanner</h3>
        <div id="preview" class="w-full rounded-lg border aspect-video bg-gray-50 overflow-hidden"
              :class="darkMode ? 'border-gray-600 bg-gray-700' : 'border-gray-300'"></div>

        {{-- Hasil Scan Kamera --}}
        <div id="result" class="mt-4 text-green-700 font-semibold text-base min-h-[1.5rem]"
              :class="darkMode ? 'text-green-400' : 'text-green-700'"></div>

        {{-- Input Manual Token --}}
        <form id="manualTokenForm" class="mt-6 space-y-3">
            <label for="manualToken" class="block text-sm font-medium"
                   :class="darkMode ? 'text-gray-300' : 'text-gray-700'">Masukkan Token QR Manual</label>
            <input type="text" id="manualToken" name="manualToken" 
                   class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-blue-300" 
                   :class="darkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-400'"
                   placeholder="Contoh: abcd1234">
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                Proses Token
            </button>
        </form>
    </section>

    <section class="rounded-xl shadow-md p-6 flex flex-col"
              :class="darkMode ? 'bg-gray-800' : 'bg-white'">
        <h3 class="text-xl font-semibold mb-6 border-b pb-2"
            :class="darkMode ? 'text-white border-gray-600' : 'text-gray-900 border-gray-200'">Hasil Scan</h3>
        <div id="scan-list" 
              class="flex-1 overflow-y-auto max-h-[24rem] border rounded-lg p-4 text-sm space-y-3 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
              :class="darkMode ? 'bg-gray-700 border-gray-600 text-gray-300 scrollbar-thumb-gray-600 scrollbar-track-gray-800' : 'bg-gray-50 border-gray-300 text-gray-700'">
            Memuat data...
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
const scanner = new Html5Qrcode("preview");
// qrbox dihapus dari konfigurasi untuk membuat kamera menjadi full frame
const config = { fps: 10 }; 
const idPesanan = "{{ $idPesanan }}";

function startScanner() {
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            scanner.start(
                { deviceId: cameras[0].id }, 
                config,
                (decodedText, decodedResult) => {
                    scanner.stop();
                    processToken(decodedText);
                },
                error => {
                    console.warn("Scan error:", error);
                }
            ).catch(err => {
                console.error("Gagal akses kamera:", err);
            });
        } else {
            alert("Tidak ada kamera terdeteksi.");
        }
    }).catch(err => {
        console.error("Gagal dapatkan kamera:", err);
    });
}

function processToken(token) {
    document.getElementById("result").innerText = `Token: ${token} sedang diproses...`;

    $.post("{{ route('koki.processScan') }}", {
        _token: "{{ csrf_token() }}",
        token: token,
        idPesanan: idPesanan
    }, function(res) {
        document.getElementById("result").innerText = res.message;
        loadScanList();
        // Memulai kembali scanner setelah 2 detik
        setTimeout(() => startScanner(), 2000);
    });
}

function loadScanList() {
    $.get(`/koki/dashboard/scan/monitor/${idPesanan}`, function(data) {
        let html = '';
        if (data.length === 0) {
            html = `<div class="italic" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Belum ada data scan.</div>`;
        } else {
            data.forEach(scan => {
                html += `<div class="pb-3 last:border-none" :class="darkMode ? 'border-gray-600' : 'border-gray-200'">
                    <div class="font-semibold" :class="darkMode ? 'text-white' : 'text-gray-900'">${scan.user.profile?.name || 'Unknown'}</div>
                    <div class="text-xs" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">${scan.waktuScan}</div>
                </div>`;
            });
        }
        $('#scan-list').html(html);
    });
}

$(document).ready(function() {
    startScanner();
    loadScanList();
    // Memuat daftar scan setiap 5 detik
    setInterval(loadScanList, 5000);

    // Form Token Manual
    $('#manualTokenForm').on('submit', function(e) {
        e.preventDefault();
        const token = $('#manualToken').val().trim();
        if (token) {
            processToken(token);
            $('#manualToken').val(''); // Mengosongkan input setelah diproses
        }
    });
});
</script>
@endsection