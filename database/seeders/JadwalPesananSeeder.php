<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalPesanan;
use Carbon\Carbon;

class JadwalPesananSeeder extends Seeder
{
    public function run(): void
    {
        // Anggap ada 10 menu ID: 1-10
        $menuShifts = [
            1 => [1, 2, 3], // Shift 1 hanya pakai menu 1, 2, 3
            2 => [4, 5, 6], // Shift 2 menu 4, 5, 6
            3 => [7, 8, 9, 10], // Shift 3 menu 7–10
        ];

        $tanggalMulai = Carbon::today();
        $totalInsert = 0;

        for ($i = 0; $i < 7; $i++) {
            $tanggal = $tanggalMulai->copy()->addDays($i);

            for ($shift = 1; $shift <= 3; $shift++) {
                if ($totalInsert >= 20) break 2; // stop di 20 total data

                $menuList = $menuShifts[$shift];
                $menuId = $menuList[array_rand($menuList)];

                JadwalPesanan::create([
                    'IdShift' => $shift,
                    'IdMenu' => $menuId,
                    'tanggalPesanan' => $tanggal,
                    'JumlahPesanan' => rand(50, 150),
                    'statusPesanan' => false,
                    'VerifAt' => null,
                ]);

                $totalInsert++;
            }
        }
    }
}
