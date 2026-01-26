<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user')->latest();
        
        if ($request->type) {
            $query->where('transaction_type', $request->type);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $transactions = $query->paginate(20);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user');
        return view('admin.transactions.show', compact('transaction'));
    }
}
