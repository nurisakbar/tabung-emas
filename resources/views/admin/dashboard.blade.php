@extends('layouts.app-bootstrap')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Dashboard Admin</h1>
        <span class="text-muted">Selamat datang, {{ auth()->user()->name }}!</span>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="mb-2" style="font-size: 2rem;">👥</div>
                <div class="stats-number">{{ $totalNasabah }}</div>
                <h6 class="text-muted mt-2 mb-0">Total Nasabah</h6>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="mb-2" style="font-size: 2rem;">📊</div>
                <div class="stats-number">{{ $totalTransactions }}</div>
                <h6 class="text-muted mt-2 mb-0">Total Transaksi</h6>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="mb-2" style="font-size: 2rem;">⬆️</div>
                <div class="stats-number text-success">{{ $totalBuy }}</div>
                <h6 class="text-muted mt-2 mb-0">Pembelian</h6>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="mb-2" style="font-size: 2rem;">⬇️</div>
                <div class="stats-number text-danger">{{ $totalSell }}</div>
                <h6 class="text-muted mt-2 mb-0">Penjualan</h6>
            </div>
        </div>
    </div>

    @if($latestPrice)
    <div class="row">
        <div class="col-md-6">
            <div class="gold-card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">💰 Harga Emas Terkini</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="price-buy bg-white bg-opacity-20 rounded p-3 mb-2">
                                <small class="d-block mb-2">Harga Beli</small>
                                <h4 class="mb-0 fw-bold">Rp {{ number_format($latestPrice->buy_price, 0, ',', '.') }}</h4>
                                <small>/gram</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="price-sell bg-white bg-opacity-20 rounded p-3 mb-2">
                                <small class="d-block mb-2">Harga Jual</small>
                                <h4 class="mb-0 fw-bold">Rp {{ number_format($latestPrice->sell_price, 0, ',', '.') }}</h4>
                                <small>/gram</small>
                            </div>
                        </div>
                    </div>
                    <small class="d-block mt-3 text-center opacity-75">{{ $latestPrice->date->format('d M Y') }}</small>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.gold-prices.index') }}" class="btn btn-light btn-sm">Kelola Harga</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card price-card h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">⚡ Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Kelola Nasabah</a>
                        <a href="{{ route('admin.gold-prices.create') }}" class="btn btn-outline-success">Tambah Harga Emas</a>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-info">Lihat Transaksi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
