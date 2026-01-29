<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GoldSaving;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NasabahUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nasabahs = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'phone' => '081234567891',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'phone' => '081234567892',
                'address' => 'Jl. Thamrin No. 789, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'phone' => '081234567893',
                'address' => 'Jl. Gatot Subroto No. 321, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Rudi Hartono',
                'email' => 'rudi@example.com',
                'password' => Hash::make('password'),
                'role' => 'nasabah',
                'phone' => '081234567894',
                'address' => 'Jl. Kebon Jeruk No. 654, Jakarta',
                'status' => 'active',
            ],
        ];

        foreach ($nasabahs as $nasabahData) {
            $user = User::firstOrCreate(
                ['email' => $nasabahData['email']],
                $nasabahData
            );

            // Create gold saving for each nasabah with some initial gold
            if (!$user->goldSaving) {
                GoldSaving::create([
                    'user_id' => $user->id,
                    'total_gold' => rand(5, 50) / 10, // Random between 0.5 to 5 grams
                    'last_transaction_date' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
