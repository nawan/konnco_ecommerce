<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'user customer',
            'email' => 'user@customer.com',
            'no_hp' => '081111111111',
            'jenis_kelamin' => 'PRIA',
            'alamat' => 'bantul',
            'is_admin' => 'NO',
            'foto' => 'mbjsnvlksvbsdkvnlskvnsvn',
            'password' => Hash::make('19091991'),
        ]);
    }
}
