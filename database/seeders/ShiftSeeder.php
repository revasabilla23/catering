<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_shift')->insert([
            ['namaShift' => 'Shift A', 'start' => '07:00:00', 'end' => '15:00:00'],
            ['namaShift' => 'Shift B', 'start' => '15:00:00', 'end' => '23:00:00'],
            ['namaShift' => 'Shift C', 'start' => '23:00:00', 'end' => '07:00:00'],
        ]);
    }
}