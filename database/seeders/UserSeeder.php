<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_users')->insert([
            [
                'email' => 'hrga@example.com',
                'password' => Hash::make('password'),
                'role' => 'HRGA',
                'IdShift' => null,
                'statusUsers' => 'aktif',
                'create_at' => now(),
            ],
            [
                'email' => 'karyawan@example.com',
                'password' => Hash::make('password'),
                'role' => 'Karyawan',
                'IdShift' => 1,
                'statusUsers' => 'aktif',
                'create_at' => now(),
            ],
            [
                'email' => 'koki@example.com',
                'password' => Hash::make('password'),
                'role' => 'Koki',
                'IdShift' => null,
                'statusUsers' => 'aktif',
                'create_at' => now(),
            ],
        ]);
    }
}
