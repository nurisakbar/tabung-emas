<?php

namespace App\Http\Controllers;

use App\Models\GoldPrice;
use App\Models\Event;
use App\Models\Article;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $latestPrice = GoldPrice::latest('date')->first();
        $events = Event::upcoming()->take(6)->get();
        $articles = Article::published()->latest('published_at')->take(9)->get();
        
        // Data untuk chart - ambil 30 hari terakhir (harian)
        $startDate = Carbon::now()->subDays(29); // 29 hari + hari ini = 30 hari
        $endDate = Carbon::now();
        
        // Ambil semua data yang ada dalam range 30 hari terakhir
        $existingPrices = GoldPrice::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy(function($price) {
                return $price->date->format('Y-m-d');
            });
        
        // Ambil data terakhir sebelum range untuk fallback
        $lastPriceBefore = GoldPrice::where('date', '<', $startDate->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->first();
        
        // Generate array untuk 30 hari terakhir
        $chartPrices = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            
            if ($existingPrices->has($dateKey)) {
                $price = $existingPrices[$dateKey];
                $chartPrices->push([
                    'date' => $dateKey,
                    'buy_price' => (float) $price->buy_price,
                    'sell_price' => (float) $price->sell_price,
                ]);
            } elseif ($lastPriceBefore) {
                // Jika tidak ada data untuk hari ini, gunakan data terakhir yang tersedia
                $chartPrices->push([
                    'date' => $dateKey,
                    'buy_price' => (float) $lastPriceBefore->buy_price,
                    'sell_price' => (float) $lastPriceBefore->sell_price,
                ]);
            } elseif ($existingPrices->count() > 0) {
                // Fallback ke data pertama yang ada
                $firstPrice = $existingPrices->first();
                $chartPrices->push([
                    'date' => $dateKey,
                    'buy_price' => (float) $firstPrice->buy_price,
                    'sell_price' => (float) $firstPrice->sell_price,
                ]);
            }
        }
        
        $chartPrices = $chartPrices->values();
        
        return view('home', compact('latestPrice', 'events', 'articles', 'chartPrices'));
    }
}
