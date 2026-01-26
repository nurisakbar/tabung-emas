<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use Illuminate\Http\Request;

class GoldPriceController extends Controller
{
    public function index()
    {
        $latestPrice = GoldPrice::latest('date')->first();
        $prices = GoldPrice::orderBy('date', 'asc')->take(90)->get(); // Ambil 90 hari terakhir untuk grafik yang lebih lengkap
        
        return view('nasabah.gold-price', compact('latestPrice', 'prices'));
    }
}
