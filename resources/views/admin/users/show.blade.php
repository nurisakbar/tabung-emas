@extends('layouts.app-bootstrap')

@section('title', 'Detail Nasabah')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Detail Nasabah: {{ $user->name }}</h1>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Nasabah</h5>
                    <p><strong>Nama:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Telepon:</strong> {{ $user->phone ?? '-' }}</p>
                    <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tabungan Emas</h5>
                    @if($goldSaving)
                        <p><strong>Total:</strong> {{ number_format($goldSaving->total_gold, 4) }} gram</p>
                        <p><strong>Update Terakhir:</strong> {{ $goldSaving->last_transaction_date ? $goldSaving->last_transaction_date->format('d M Y') : '-' }}</p>
                    @else
                        <p class="text-muted">Belum ada tabungan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h3>Riwayat Transaksi</h3>
            @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Jumlah Emas</th>
                                <th>Harga/gram</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->isBuy() ? 'success' : 'danger' }}">
                                        {{ $transaction->isBuy() ? 'Beli' : 'Jual' }}
                                    </span>
                                </td>
                                <td>{{ number_format($transaction->gold_amount, 4) }} gram</td>
                                <td>Rp {{ number_format($transaction->price_per_gram, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            @else
                <p class="text-muted">Belum ada transaksi.</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
