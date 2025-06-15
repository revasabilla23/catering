<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $genders = ['L', 'P']; // Laki-laki, Perempuan
        $shifts = [1, 2, 3]; // Shift IDs, pastikan shift 1, 2, 3 sudah ada di tabel shifts

        $counter = 1;

        foreach ($shifts as $shiftId) {
            for ($i = 0; $i < 30; $i++) {
                $name = 'Karyawan ' . $counter;

                // Simpan ke tb_users
                $user = User::create([
                    'email' => 'karyawan' . $counter . '@example.com',
                    'password' => Hash::make('password'), // default password
                    'role' => 'Karyawan',
                    'IdShift' => $shiftId,
                    'statusUsers' => 'aktif',
                    'create_at' => Carbon::now(),
                ]);

                // Simpan ke tb_profile
                Profile::create([
                    'IdUsers' => $user->IdUsers,
                    'name' => $name,
                    'gender' => $genders[array_rand($genders)],
                    'nik' => '32750' . str_pad((string) $counter, 8, '0', STR_PAD_LEFT),
                    'tanggalLahir' => Carbon::parse('1990-01-01')->addDays(rand(0, 10000)),
                    'address' => 'Alamat Karyawan ' . $counter,
                    'foto' => 'default.png',
                    'noTelepon' => '08' . rand(1000000000, 9999999999),
                ]);

                $counter++;
            }
        }
    }
}
