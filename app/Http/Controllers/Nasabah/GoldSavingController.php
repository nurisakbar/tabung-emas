<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use App\Models\GoldSaving;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoldSavingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $goldSaving = $user->goldSaving;
        $transactions = $user->transactions()->latest()->paginate(10);
        $latestPrice = GoldPrice::latest('date')->first();
        
        return view('nasabah.gold-saving', compact('goldSaving', 'transactions', 'latestPrice'));
    }

    public function create()
    {
        $latestPrice = GoldPrice::latest('date')->first();
        
        if (!$latestPrice) {
            return redirect()->route('nasabah.gold-saving')
                ->with('error', 'Harga emas belum tersedia. Silakan hubungi admin.');
        }
        
        return view('nasabah.buy-gold', compact('latestPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gold_amount' => ['required', 'numeric', 'min:0.0001'],
        ]);

        $user = auth()->user();
        $latestPrice = GoldPrice::latest('date')->first();

        if (!$latestPrice) {
            return redirect()->route('nasabah.gold-saving')
                ->with('error', 'Harga emas belum tersedia.');
        }

        $goldAmount = $request->gold_amount;
        $pricePerGram = $latestPrice->buy_price; // Harga beli dari nasabah
        $totalPrice = $goldAmount * $pricePerGram;

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transaction_type' => 'buy',
                'gold_amount' => $goldAmount,
                'price_per_gram' => $pricePerGram,
                'total_price' => round($totalPrice, 2),
                'transaction_date' => now()->toDateString(),
                'status' => 'completed',
                'notes' => $request->notes ?? null,
            ]);

            // Update or create gold saving
            $goldSaving = GoldSaving::firstOrCreate(
                ['user_id' => $user->id],
                ['total_gold' => 0]
            );

            $goldSaving->total_gold += $goldAmount;
            $goldSaving->last_transaction_date = now();
            $goldSaving->save();

            DB::commit();

            return redirect()->route('nasabah.gold-saving')
                ->with('success', 'Pembelian emas berhasil! Emas ' . number_format($goldAmount, 4) . ' gram telah ditambahkan ke tabungan Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('nasabah.gold-saving')
                ->with('error', 'Terjadi kesalahan saat memproses pembelian. Silakan coba lagi.');
        }
    }
}
