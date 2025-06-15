<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['Paket A', 'Nasi putih, Ayam goreng, Sambal, Lalapan, Air mineral'],
            ['Paket B', 'Nasi putih, Ikan goreng, Tumis kangkung, Tahu, Teh manis'],
            ['Paket C', 'Nasi uduk, Telur balado, Tempe orek, Acar, Air mineral'],
            ['Paket D', 'Nasi kuning, Ayam suwir, Perkedel, Kerupuk, Jus jeruk'],
            ['Paket E', 'Nasi putih, Daging rendang, Sayur lodeh, Kerupuk, Air mineral'],
            ['Paket F', 'Nasi liwet, Ayam bakar, Sambal terasi, Urap, Es teh'],
            ['Paket G', 'Nasi putih, Lele goreng, Sayur asem, Tempe, Air mineral'],
            ['Paket H', 'Nasi goreng, Telur ceplok, Acar timun, Kerupuk, Teh manis'],
            ['Paket I', 'Nasi putih, Soto ayam, Perkedel kentang, Jeruk, Air mineral'],
            ['Paket J', 'Nasi putih, Ayam rica-rica, Tumis buncis, Tahu, Jus jambu'],
        ];

        foreach ($menus as $menu) {
            Menu::create([
                'namaMenu' => $menu[0],
                'deskMenu' => $menu[1],
            ]);
        }
    }
}
