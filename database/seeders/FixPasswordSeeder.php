<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FixPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->password = Hash::make('password');
            $user->save();
        }
    }
}
