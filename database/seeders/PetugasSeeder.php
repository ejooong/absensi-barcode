<?php
// database/seeders/PetugasSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        Petugas::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
        ]);
    }
}