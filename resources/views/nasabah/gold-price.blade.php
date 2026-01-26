@extends('layouts.app-bootstrap')

@section('title', 'Harga Emas')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">💰 Harga Emas</h1>
    
    @if($latestPrice)
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="gold-card">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 3rem;">⬆️</div>
                    <h5 class="card-title mb-3">Harga Beli</h5>
                    <h2 class="mb-2 fw-bold">Rp {{ number_format($latestPrice->buy_price, 0, ',', '.') }}</h2>
                    <small class="opacity-75">per gram</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card price-card price-sell h-100">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 3rem;">⬇️</div>
                    <h5 class="card-title mb-3">Harga Jual</h5>
                    <h2 class="mb-2 fw-bold text-danger">Rp {{ number_format($latestPrice->sell_price, 0, ',', '.') }}</h2>
                    <small class="text-muted">per gram</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📈 Riwayat Harga (30 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-end">Harga Beli</th>
                                    <th class="text-end">Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prices as $price)
                                <tr>
                                    <td>{{ $price->date->format('d M Y') }}</td>
                                    <td class="text-end"><strong>Rp {{ number_format($price->buy_price, 0, ',', '.') }}</strong></td>
                                    <td class="text-end"><strong class="text-danger">Rp {{ number_format($price->sell_price, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="alert alert-info text-center py-4">
            <div class="mb-2" style="font-size: 3rem;">📊</div>
            <h5>Belum ada data harga emas</h5>
            <p class="mb-0">Data harga akan ditampilkan setelah admin mengupdate harga emas.</p>
        </div>
    @endif
</div>
@endsection
