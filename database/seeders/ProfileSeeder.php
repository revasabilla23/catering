<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user berdasarkan email
        $karyawan = User::where('email', 'karyawan@example.com')->first();
        $koki = User::where('email', 'koki@example.com')->first();
        $hrga = User::where('email', 'hrga@example.com')->first();

        DB::table('tb_profile')->insert([
            [
                'IdUsers' => $karyawan->IdUsers,
                'name' => 'Budi Karyawan',
                'gender' => 'L',
                'nik' => '1234567890123456',
                'tanggalLahir' => '1995-05-05',
                'address' => 'Jl. Karyawan No.1',
                'foto' => 'default.jpg',
                'noTelepon' => '628123456789',
            ],
            [
                'IdUsers' => $koki->IdUsers,
                'name' => 'Susi Koki',
                'gender' => 'P',
                'nik' => '2345678901234567',
                'tanggalLahir' => '1990-03-10',
                'address' => 'Jl. Dapur No.2',
                'foto' => 'default.jpg',
                'noTelepon' => '628223456789',
            ],
            [
                'IdUsers' => $hrga->IdUsers,
                'name' => 'Ani HRGA',
                'gender' => 'P',
                'nik' => '3456789012345678',
                'tanggalLahir' => '1988-07-20',
                'address' => 'Jl. Admin No.3',
                'foto' => 'default.jpg',
                'noTelepon' => '628323456789',
            ],
        ]);
    }
}