<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoldPriceController extends Controller
{
    public function index()
    {
        $prices = GoldPrice::latest('date')->paginate(20);
        return view('admin.gold-prices.index', compact('prices'));
    }

    public function create()
    {
        return view('admin.gold-prices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'unique:gold_prices,date'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['created_by'] = Auth::id();
        GoldPrice::create($validated);

        return redirect()->route('admin.gold-prices.index')->with('success', 'Harga emas berhasil ditambahkan.');
    }

    public function edit(GoldPrice $goldPrice)
    {
        return view('admin.gold-prices.edit', compact('goldPrice'));
    }

    public function update(Request $request, GoldPrice $goldPrice)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', 'unique:gold_prices,date,' . $goldPrice->id],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
        ]);

        $goldPrice->update($validated);

        return redirect()->route('admin.gold-prices.index')->with('success', 'Harga emas berhasil diupdate.');
    }
}
