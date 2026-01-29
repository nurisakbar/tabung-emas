@extends('layouts.app-bootstrap')

@section('title', 'Buat Paket Cicil Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.installments.index') }}">Cicil Emas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Paket Baru</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">📅 Buat Paket Cicil Emas</h3>
                </div>
                <div class="card-body p-4">
                    @if($latestPrice)
                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <strong>Harga Beli Saat Ini:</strong>
                                    <h4 class="mb-0 text-success">Rp {{ number_format($latestPrice->buy_price, 0, ',', '.') }}/gram</h4>
                                    <small class="text-muted">Update: {{ $latestPrice->date->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('nasabah.installments.store') }}" id="installmentForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-4">
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
                                           autofocus>
                                    @error('gold_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum: 0.0001 gram</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="tenor" class="form-label fw-bold">
                                        Tenor (bulan) <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('tenor') is-invalid @enderror" 
                                            id="tenor" 
                                            name="tenor" 
                                            required>
                                        <option value="">Pilih Tenor</option>
                                        <option value="3" {{ old('tenor') == '3' ? 'selected' : '' }}>3 Bulan</option>
                                        <option value="6" {{ old('tenor') == '6' ? 'selected' : '' }}>6 Bulan</option>
                                        <option value="12" {{ old('tenor') == '12' ? 'selected' : '' }}>12 Bulan</option>
                                        <option value="24" {{ old('tenor') == '24' ? 'selected' : '' }}>24 Bulan</option>
                                    </select>
                                    @error('tenor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="frequency" class="form-label fw-bold">
                                        Frekuensi Pembayaran <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('frequency') is-invalid @enderror" 
                                            id="frequency" 
                                            name="frequency" 
                                            required>
                                        <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                    </select>
                                    @error('frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="down_payment" class="form-label fw-bold">
                                        Down Payment (Opsional)
                                    </label>
                                    <input type="number" 
                                           class="form-control form-control-lg @error('down_payment') is-invalid @enderror" 
                                           id="down_payment" 
                                           name="down_payment" 
                                           step="0.01"
                                           min="0"
                                           value="{{ old('down_payment', 0) }}">
                                    @error('down_payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Kosongkan jika tidak ada DP</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">Catatan (Opsional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Tambahkan catatan untuk paket cicilan ini">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Calculation Summary -->
                            <div class="card bg-light mb-4" id="summaryCard" style="display: none;">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">📊 Ringkasan Paket Cicilan</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Jumlah Emas:</strong></p>
                                            <p class="mb-2"><strong>Harga per gram:</strong></p>
                                            <p class="mb-2"><strong>Total Harga:</strong></p>
                                            <p class="mb-2"><strong>Down Payment:</strong></p>
                                            <p class="mb-2"><strong>Sisa yang Dicicil:</strong></p>
                                            <p class="mb-2"><strong>Cicilan per {{ $latestPrice ? 'periode' : 'bulan' }}:</strong></p>
                                            <p class="mb-0"><strong>Total Periode:</strong></p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <p class="mb-2" id="summary-gold">0.0000 gram</p>
                                            <p class="mb-2">Rp <span id="summary-price">{{ number_format($latestPrice->buy_price, 0, ',', '.') }}</span></p>
                                            <p class="mb-2">Rp <span id="summary-total">0</span></p>
                                            <p class="mb-2">Rp <span id="summary-dp">0</span></p>
                                            <p class="mb-2">Rp <span id="summary-remaining">0</span></p>
                                            <p class="mb-2 fw-bold text-success fs-5">Rp <span id="summary-installment">0</span></p>
                                            <p class="mb-0"><span id="summary-periods">0</span> periode</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gold btn-lg">
                                    <span class="me-2">✅</span> Buat Paket Cicilan
                                </button>
                                <a href="{{ route('nasabah.installments.index') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning text-center">
                            <div class="mb-2" style="font-size: 3rem;">⚠️</div>
                            <h5>Harga Emas Belum Tersedia</h5>
                            <p class="mb-0">Silakan hubungi admin untuk mengupdate harga emas terlebih dahulu.</p>
                            <a href="{{ route('nasabah.installments.index') }}" class="btn btn-primary mt-3">Kembali</a>
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
    const tenorSelect = document.getElementById('tenor');
    const frequencySelect = document.getElementById('frequency');
    const downPaymentInput = document.getElementById('down_payment');
    const pricePerGram = {{ $latestPrice ? $latestPrice->buy_price : 0 }};
    const summaryCard = document.getElementById('summaryCard');

    function calculateSummary() {
        const goldAmount = parseFloat(goldAmountInput.value) || 0;
        const tenor = parseInt(tenorSelect.value) || 0;
        const frequency = frequencySelect.value;
        const downPayment = parseFloat(downPaymentInput.value) || 0;

        if (goldAmount > 0 && tenor > 0 && pricePerGram > 0) {
            const totalPrice = goldAmount * pricePerGram;
            const priceAfterDP = totalPrice - downPayment;
            const adminFee = 0; // Can be configured later
            const totalWithFee = priceAfterDP + adminFee;

            // Calculate periods
            let totalPeriods = tenor;
            if (frequency === 'weekly') {
                totalPeriods = tenor * 4; // Approximate
            }

            const installmentAmount = totalPeriods > 0 ? totalWithFee / totalPeriods : 0;

            // Update summary
            document.getElementById('summary-gold').textContent = goldAmount.toFixed(4) + ' gram';
            document.getElementById('summary-total').textContent = totalPrice.toLocaleString('id-ID');
            document.getElementById('summary-dp').textContent = downPayment.toLocaleString('id-ID');
            document.getElementById('summary-remaining').textContent = priceAfterDP.toLocaleString('id-ID');
            document.getElementById('summary-installment').textContent = installmentAmount.toLocaleString('id-ID');
            document.getElementById('summary-periods').textContent = totalPeriods;

            summaryCard.style.display = 'block';
        } else {
            summaryCard.style.display = 'none';
        }
    }

    if (goldAmountInput && tenorSelect && frequencySelect) {
        goldAmountInput.addEventListener('input', calculateSummary);
        tenorSelect.addEventListener('change', calculateSummary);
        frequencySelect.addEventListener('change', calculateSummary);
        downPaymentInput.addEventListener('input', calculateSummary);
    }
});
</script>
@endpush
@endsection
