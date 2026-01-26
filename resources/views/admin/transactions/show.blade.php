@extends('layouts.app-bootstrap')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Detail Transaksi</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Transaksi</h5>
                    <p><strong>Tanggal:</strong> {{ $transaction->transaction_date->format('d M Y') }}</p>
                    <p><strong>Nasabah:</strong> {{ $transaction->user->name }}</p>
                    <p><strong>Tipe:</strong> 
                        <span class="badge bg-{{ $transaction->isBuy() ? 'success' : 'danger' }}">
                            {{ $transaction->isBuy() ? 'Beli' : 'Jual' }}
                        </span>
                    </p>
                    <p><strong>Jumlah Emas:</strong> {{ number_format($transaction->gold_amount, 4) }} gram</p>
                    <p><strong>Harga per gram:</strong> Rp {{ number_format($transaction->price_per_gram, 0, ',', '.') }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </p>
                    @if($transaction->notes)
                        <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
