@extends('layouts.app-bootstrap')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Daftar Transaksi</h1>
    
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="buy" {{ request('type') === 'buy' ? 'selected' : '' }}>Beli</option>
                        <option value="sell" {{ request('type') === 'sell' ? 'selected' : '' }}>Jual</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nasabah</th>
                    <th>Tipe</th>
                    <th>Jumlah Emas</th>
                    <th>Harga/gram</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>
                        <span class="badge bg-{{ $transaction->isBuy() ? 'success' : 'danger' }}">
                            {{ $transaction->isBuy() ? 'Beli' : 'Jual' }}
                        </span>
                    </td>
                    <td>{{ number_format($transaction->gold_amount, 4) }} gram</td>
                    <td>Rp {{ number_format($transaction->price_per_gram, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-primary">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
