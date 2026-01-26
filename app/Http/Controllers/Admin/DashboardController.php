<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\GoldPrice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNasabah = User::where('role', 'nasabah')->count();
        $totalTransactions = Transaction::count();
        $totalBuy = Transaction::where('transaction_type', 'buy')->count();
        $totalSell = Transaction::where('transaction_type', 'sell')->count();
        $latestPrice = GoldPrice::latest('date')->first();
        
        return view('admin.dashboard', compact('totalNasabah', 'totalTransactions', 'totalBuy', 'totalSell', 'latestPrice'));
    }
}
