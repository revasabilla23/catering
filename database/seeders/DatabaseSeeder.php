<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ShiftSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            ProfileSeeder::class,
            KaryawanSeeder::class,
            JadwalPesananSeeder::class,
            KonsumsiSeeder::class,
        ]);
    }
}
