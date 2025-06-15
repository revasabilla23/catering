<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Shift;

class RotateShiftWeekly extends Command
{
    protected $signature = 'shift:rotate-weekly';
    protected $description = 'Putar shift karyawan setiap minggu';

    public function handle()
    {
        $shifts = Shift::orderBy('namaShift')->get();

        $shiftMap = [
            'Shift A' => 'Shift C',
            'Shift C' => 'Shift B',
            'Shift B' => 'Shift A',
        ];

        $shiftNameToId = $shifts->pluck('IdShift', 'namaShift');

        $karyawans = User::where('role', 'Karyawan')->get();

        foreach ($karyawans as $karyawan) {
            $currentShiftName = $karyawan->shift->namaShift ?? null;

            if (!$currentShiftName || !isset($shiftMap[$currentShiftName])) {
                continue;
            }

            $nextShiftName = $shiftMap[$currentShiftName];
            $karyawan->IdShift = $shiftNameToId[$nextShiftName];
            $karyawan->save();
        }

        $this->info('Shift karyawan berhasil diputar secara otomatis.');
    }
}