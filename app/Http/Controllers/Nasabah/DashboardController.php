<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $goldSaving = $user->goldSaving;
        $recentTransactions = $user->transactions()->latest()->take(5)->get();
        
        return view('nasabah.dashboard', compact('goldSaving', 'recentTransactions'));
    }
}
