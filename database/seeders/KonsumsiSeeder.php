<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Konsumsi;
use App\Models\JadwalPesanan;
use App\Models\User;
use Carbon\Carbon;

class KonsumsiSeeder extends Seeder
{
    public function run(): void
    {
        $tanggalList = [
            Carbon::today()->toDateString(),
            Carbon::tomorrow()->toDateString()
        ];

        foreach ($tanggalList as $tanggal) {
            // Ambil semua jadwal pesanan pada tanggal tersebut
            $jadwals = JadwalPesanan::whereDate('tanggalPesanan', $tanggal)->get();

            foreach ($jadwals as $jadwal) {
                // Ambil semua user sesuai shift dari jadwal
                $users = User::where('role', 'Karyawan')->where('IdShift', $jadwal->IdShift)->get();

                foreach ($users as $user) {
                    // Hindari duplikasi data konsumsi
                    $already = Konsumsi::where('IdUsers', $user->IdUsers)
                        ->whereDate('tanggalKonsumsi', $tanggal)
                        ->exists();

                    if (!$already) {
                        Konsumsi::create([
                            'IdUsers' => $user->IdUsers,
                            'IdShift' => $user->IdShift,
                            'IdPesanan' => $jadwal->IdPesanan,
                            'tanggalKonsumsi' => $tanggal,
                            'statusQr' => 'berhasil',
                            'waktuScan' => null,
                        ]);
                    }
                }
            }
        }
    }
}
