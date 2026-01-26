<?php

namespace Database\Seeders;

use App\Models\GoldPrice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GoldPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            return;
        }

        // Generate realistic gold prices for last 90 days with trend
        $baseBuyPrice = 950000; // Starting price
        $baseSellPrice = 930000; // Starting sell price
        
        $currentBuyPrice = $baseBuyPrice;
        $currentSellPrice = $baseSellPrice;
        
        // Generate prices for last 90 days (including weekends for daily data)
        for ($i = 89; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Create realistic trend: gradual increase with some volatility
            // Overall trend: slight upward (+0.1% per day on average)
            // Daily volatility: ±0.5% to ±1.5%
            
            // Base trend (slight upward)
            $trendFactor = 1.001; // 0.1% increase per day
            
            // Add some volatility (random but realistic)
            $volatility = (rand(-150, 150) / 10000); // ±1.5%
            
            // Occasional bigger moves (market events simulation)
            if (rand(1, 20) == 1) {
                $volatility += (rand(-300, 300) / 10000); // ±3% big move
            }
            
            // Weekend prices usually have less volatility (market closed)
            if ($date->dayOfWeek == 6 || $date->dayOfWeek == 0) {
                $volatility = $volatility * 0.3; // Less volatility on weekends
            }
            
            // Apply trend and volatility
            $currentBuyPrice = $currentBuyPrice * ($trendFactor + $volatility);
            $currentSellPrice = $currentBuyPrice - 20000; // Maintain spread
            
            // Ensure prices don't go too low or too high
            $currentBuyPrice = max(900000, min(1100000, $currentBuyPrice));
            $currentSellPrice = max(880000, min(1080000, $currentSellPrice));
            
            GoldPrice::firstOrCreate(
                ['date' => $date->format('Y-m-d')],
                [
                    'buy_price' => round($currentBuyPrice),
                    'sell_price' => round($currentSellPrice),
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
