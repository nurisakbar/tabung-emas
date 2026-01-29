<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\GoldPrice;
use App\Models\GoldSaving;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nasabahs = User::where('role', 'nasabah')->get();
        $latestPrice = GoldPrice::latest('date')->first();

        if ($nasabahs->isEmpty() || !$latestPrice) {
            return;
        }

        // Create some sample transactions for each nasabah
        foreach ($nasabahs as $nasabah) {
            $totalGoldBought = 0;
            $totalGoldSold = 0;
            
            // Create 3-5 buy transactions in the past
            $buyCount = rand(3, 5);
            for ($i = 0; $i < $buyCount; $i++) {
                $daysAgo = rand(1, 60);
                $date = Carbon::now()->subDays($daysAgo);
                
                // Get price for that date or use latest price
                $priceForDate = GoldPrice::where('date', '<=', $date->format('Y-m-d'))
                    ->latest('date')
                    ->first() ?? $latestPrice;

                $goldAmount = rand(1, 10) / 10; // 0.1 to 1 gram
                $totalPrice = $goldAmount * $priceForDate->buy_price;
                $totalGoldBought += $goldAmount;

                Transaction::create([
                    'user_id' => $nasabah->id,
                    'transaction_type' => 'buy',
                    'gold_amount' => $goldAmount,
                    'price_per_gram' => $priceForDate->buy_price,
                    'total_price' => round($totalPrice, 2),
                    'transaction_date' => $date->format('Y-m-d'),
                    'status' => 'completed',
                    'notes' => 'Pembelian emas - Seeder',
                ]);
            }

            // Create 0-2 sell transactions (optional)
            if (rand(0, 1)) {
                $daysAgo = rand(1, 30);
                $date = Carbon::now()->subDays($daysAgo);
                
                $priceForDate = GoldPrice::where('date', '<=', $date->format('Y-m-d'))
                    ->latest('date')
                    ->first() ?? $latestPrice;

                $goldAmount = rand(1, 5) / 10; // 0.1 to 0.5 gram
                $totalPrice = $goldAmount * $priceForDate->sell_price;
                $totalGoldSold += $goldAmount;

                Transaction::create([
                    'user_id' => $nasabah->id,
                    'transaction_type' => 'sell',
                    'gold_amount' => $goldAmount,
                    'price_per_gram' => $priceForDate->sell_price,
                    'total_price' => round($totalPrice, 2),
                    'transaction_date' => $date->format('Y-m-d'),
                    'status' => 'completed',
                    'notes' => 'Penjualan emas - Seeder',
                ]);
            }

            // Update gold saving based on transactions
            $netGold = $totalGoldBought - $totalGoldSold;
            if ($netGold > 0) {
                $goldSaving = GoldSaving::firstOrCreate(
                    ['user_id' => $nasabah->id],
                    ['total_gold' => 0, 'last_transaction_date' => now()]
                );
                
                $goldSaving->total_gold = $netGold;
                $goldSaving->last_transaction_date = now();
                $goldSaving->save();
            }
        }
    }
}
