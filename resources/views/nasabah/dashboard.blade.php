@extends('layouts.app-bootstrap')

@section('title', 'Dashboard Nasabah')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Dashboard</h1>
        <span class="text-muted">Selamat datang, {{ auth()->user()->name }}!</span>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="gold-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">💎 Total Tabungan Emas</h5>
                        <span style="font-size: 2rem;">💰</span>
                    </div>
                    <h2 class="mb-2 fw-bold">
                        {{ $goldSaving ? number_format($goldSaving->total_gold, 4) : '0.0000' }} <small class="fs-5">gram</small>
                    </h2>
                    @if($goldSaving && $goldSaving->last_transaction_date)
                        <small class="opacity-75">Update terakhir: {{ $goldSaving->last_transaction_date->format('d M Y') }}</small>
                    @else
                        <small class="opacity-75">Belum ada transaksi</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card price-card h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">📊 Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('nasabah.gold-price') }}" class="btn btn-outline-primary">Lihat Harga Emas</a>
                        <a href="{{ route('nasabah.gold-saving') }}" class="btn btn-outline-success">Lihat Tabungan</a>
                        <a href="{{ route('nasabah.installments.index') }}" class="btn btn-outline-warning">Cicil Emas</a>
                        <a href="{{ route('nasabah.pledges.index') }}" class="btn btn-outline-info">Gadai Emas</a>
                        <a href="{{ route('nasabah.profile.edit') }}" class="btn btn-outline-secondary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <div class="mb-2" style="font-size: 2.5rem;">📅</div>
                    <h6 class="fw-bold">Cicil Emas</h6>
                    <p class="text-muted small mb-2">Beli emas secara cicilan</p>
                    <a href="{{ route('nasabah.installments.index') }}" class="btn btn-sm btn-warning">Lihat Paket</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <div class="mb-2" style="font-size: 2.5rem;">🏦</div>
                    <h6 class="fw-bold">Gadai Emas</h6>
                    <p class="text-muted small mb-2">Gadaikan emas untuk pinjaman</p>
                    <a href="{{ route('nasabah.pledges.index') }}" class="btn btn-sm btn-info">Pelajari Lebih</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <div class="mb-2" style="font-size: 2.5rem;">📈</div>
                    <h6 class="fw-bold">Harga Emas</h6>
                    <p class="text-muted small mb-2">Lihat pergerakan harga</p>
                    <a href="{{ route('nasabah.gold-price') }}" class="btn btn-sm btn-primary">Lihat Harga</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📋 Transaksi Terakhir</h5>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Jumlah Emas</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->isBuy() ? 'success' : 'danger' }}">
                                                {{ $transaction->isBuy() ? 'Beli' : 'Jual' }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($transaction->gold_amount, 4) }} gram</td>
                                        <td>Rp {{ number_format($transaction->price_per_gram, 0, ',', '.') }}</td>
                                        <td><strong>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></td>
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
                        <div class="text-center mt-3">
                            <a href="{{ route('nasabah.gold-saving') }}" class="btn btn-outline-primary">Lihat Semua Transaksi</a>
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
