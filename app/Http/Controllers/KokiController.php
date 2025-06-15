<?php
namespace App\Http\Controllers;

use App\Models\JadwalPesanan;
use App\Models\QrToken;
use App\Models\Konsumsi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KokiController extends Controller
{
    // Dashboard (web only)
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // Ambil semua jadwal pesanan hari ini
        $pesananHariIni = JadwalPesanan::whereDate('tanggalPesanan', $today)->get();

        // Total jumlah pesanan dari semua shift
        $totalPesanan = $pesananHariIni->sum('JumlahPesanan');

        // Total yang sudah scan QR (konsumsi hari ini)
        $totalScan = Konsumsi::whereDate('tanggalKonsumsi', $today)->count();

        // Sisa pesanan
        $sisaPesanan = $totalPesanan - $totalScan;

        // Daftar konsumsi (scan QR)
        $karyawanScan = Konsumsi::with(['user.profile', 'shift'])
        ->whereDate('tanggalKonsumsi', Carbon::today())
        ->latest('waktuScan')
        ->get();


        return view('koki.dashboard', compact(
            'totalPesanan', 'totalScan', 'sisaPesanan', 'karyawanScan'
        ));
    }

    // Pesanan (web only)
    public function pesanan()
    {
        $today = Carbon::now()->toDateString();
        $jadwal = JadwalPesanan::whereDate('tanggalPesanan', $today)->with('Shift')->get();
        return view('koki.pesanan', compact('jadwal'));
    }

    // Scan QR (web)
    public function scanQr($idPesanan)
    {
        return view('koki.scanqr', compact('idPesanan'));
    }

    // Proses Scan (API dan web)
    public function processScan(Request $request)
    {
        $token = $request->input('token');
        $idPesanan = $request->input('idPesanan');

        $qrToken = QrToken::where('token', $token)
            ->where('expired', '>', Carbon::now())
            ->first();

        if (!$qrToken) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        $alreadyScanned = Konsumsi::where('IdUsers', $qrToken->IdUsers)
            ->where('IdPesanan', $idPesanan)
            ->exists();

        if ($alreadyScanned) {
            return response()->json(['success' => false, 'message' => 'Pengguna sudah melakukan scan.']);
        }

        Konsumsi::create([
            'IdUsers' => $qrToken->IdUsers,
            'IdShift' => $qrToken->user->IdShift,
            'IdPesanan' => $idPesanan,
            'tanggalKonsumsi' => Carbon::now()->toDateString(),
            'statusQr' => 'berhasil',
            'waktuScan' => Carbon::now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Scan berhasil.']);
    }

    // Monitor hasil scan (web dan api)
    public function monitorScan($idPesanan)
    {
        $scans = Konsumsi::with('user.profile')
            ->where('IdPesanan', $idPesanan)
            ->latest('waktuScan')
            ->get();

        return response()->json($scans);
    }
}
