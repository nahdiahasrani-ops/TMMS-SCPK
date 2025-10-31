<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin TMMS', 'password' => Hash::make('12345678'), 'role' => 1]
        );
        User::updateOrCreate(
            ['email' => 'atasan@gmail.com'],
            ['name' => 'Atasan TMMS', 'password' => Hash::make('12345678'), 'role' => 2]
        );
    }
}
