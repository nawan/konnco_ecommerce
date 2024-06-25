<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@admin.com',
            'no_hp' => '081111111112',
            'jenis_kelamin' => 'PRIA',
            'alamat' => 'bantul',
            'is_admin' => 'YES',
            'foto' => 'mbjsnvlksvbsdkvnlskvnsvn',
            'password' => Hash::make('19091991'),
        ]);
    }
}
