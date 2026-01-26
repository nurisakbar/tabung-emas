@extends('layouts.app-bootstrap')

@section('title', 'Tabungan Emas')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">💎 Tabungan Emas Saya</h1>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="gold-card">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 4rem;">💰</div>
                    <h5 class="card-title mb-3">Total Tabungan Emas</h5>
                    <h1 class="mb-2 fw-bold">
                        {{ $goldSaving ? number_format($goldSaving->total_gold, 4) : '0.0000' }} <small class="fs-4">gram</small>
                    </h1>
                    @if($goldSaving && $goldSaving->last_transaction_date)
                        <small class="opacity-75">Update terakhir: {{ $goldSaving->last_transaction_date->format('d M Y') }}</small>
                    @else
                        <small class="opacity-75">Mulai tabungan emas Anda sekarang!</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card price-card h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">⚡ Aksi Cepat</h5>
                    @if($latestPrice)
                        <div class="mb-3">
                            <p class="text-muted mb-2"><small>Harga Beli Saat Ini:</small></p>
                            <h4 class="text-success fw-bold mb-3">Rp {{ number_format($latestPrice->buy_price, 0, ',', '.') }}/gram</h4>
                        </div>
                    @endif
                    <div class="d-grid gap-2">
                        <a href="{{ route('nasabah.gold-saving.buy') }}" class="btn btn-gold btn-lg">
                            <span class="me-2">💰</span> Beli Emas
                        </a>
                        <a href="{{ route('nasabah.gold-price') }}" class="btn btn-outline-primary">
                            <span class="me-2">📈</span> Lihat Harga Emas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📋 Riwayat Transaksi</h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Jumlah Emas</th>
                                        <th class="text-end">Harga/gram</th>
                                        <th class="text-end">Total</th>
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
                                        <td><strong>{{ number_format($transaction->gold_amount, 4) }} gram</strong></td>
                                        <td class="text-end">Rp {{ number_format($transaction->price_per_gram, 0, ',', '.') }}</td>
                                        <td class="text-end"><strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></td>
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
                        <div class="text-center py-5">
                            <div class="mb-3" style="font-size: 3rem;">📭</div>
                            <p class="text-muted">Belum ada transaksi.</p>
                            <a href="{{ route('nasabah.gold-price') }}" class="btn btn-gold">Lihat Harga Emas</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
