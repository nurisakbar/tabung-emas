@extends('layouts.app-bootstrap')

@section('title', 'Beli Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.gold-saving') }}">Tabungan Emas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Beli Emas</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card price-card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">💰 Beli Emas</h3>
                </div>
                <div class="card-body p-4">
                    @if($latestPrice)
                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Harga Beli Saat Ini:</strong>
                                    <h4 class="mb-0 text-success">Rp {{ number_format($latestPrice->buy_price, 0, ',', '.') }}/gram</h4>
                                    <small class="text-muted">Update: {{ $latestPrice->date->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('nasabah.gold-saving.buy.store') }}" id="buyGoldForm">
                            @csrf

                            <div class="mb-4">
                                <label for="gold_amount" class="form-label fw-bold">
                                    Jumlah Emas (gram) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('gold_amount') is-invalid @enderror" 
                                       id="gold_amount" 
                                       name="gold_amount" 
                                       step="0.0001"
                                       min="0.0001"
                                       value="{{ old('gold_amount') }}" 
                                       required 
                                       autofocus
                                       placeholder="Masukkan jumlah emas (contoh: 1.5)">
                                @error('gold_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum pembelian: 0.0001 gram</small>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">Catatan (Opsional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Tambahkan catatan untuk transaksi ini (opsional)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Summary -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Ringkasan Pembelian</h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-2"><strong>Jumlah Emas:</strong></p>
                                            <p class="mb-2"><strong>Harga per gram:</strong></p>
                                            <p class="mb-0"><strong>Total Pembayaran:</strong></p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <p class="mb-2" id="summary-gold">0.0000 gram</p>
                                            <p class="mb-2">Rp <span id="summary-price">{{ number_format($latestPrice->buy_price, 0, ',', '.') }}</span></p>
                                            <p class="mb-0 fw-bold text-success fs-5">Rp <span id="summary-total">0</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gold btn-lg">
                                    <span class="me-2">✅</span> Konfirmasi Pembelian
                                </button>
                                <a href="{{ route('nasabah.gold-saving') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning text-center">
                            <div class="mb-2" style="font-size: 3rem;">⚠️</div>
                            <h5>Harga Emas Belum Tersedia</h5>
                            <p class="mb-0">Silakan hubungi admin untuk mengupdate harga emas terlebih dahulu.</p>
                            <a href="{{ route('nasabah.gold-saving') }}" class="btn btn-primary mt-3">Kembali</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const goldAmountInput = document.getElementById('gold_amount');
    const pricePerGram = {{ $latestPrice ? $latestPrice->buy_price : 0 }};
    const summaryGold = document.getElementById('summary-gold');
    const summaryTotal = document.getElementById('summary-total');

    if (goldAmountInput && pricePerGram > 0) {
        goldAmountInput.addEventListener('input', function() {
            const goldAmount = parseFloat(this.value) || 0;
            const totalPrice = goldAmount * pricePerGram;

            summaryGold.textContent = goldAmount.toFixed(4) + ' gram';
            summaryTotal.textContent = totalPrice.toLocaleString('id-ID');
        });
    }
});
</script>
@endpush
@endsection
