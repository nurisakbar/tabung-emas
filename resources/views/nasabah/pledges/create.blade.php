@extends('layouts.app-bootstrap')

@section('title', 'Ajukan Gadai Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.pledges.index') }}">Gadai Emas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajukan Gadai</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">🏦 Ajukan Gadai Emas</h3>
                </div>
                <div class="card-body p-4">
                    @if($latestPrice)
                        <div class="alert alert-info mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <strong>Harga Jual Saat Ini:</strong>
                                    <h4 class="mb-0 text-success">Rp {{ number_format($latestPrice->sell_price, 0, ',', '.') }}/gram</h4>
                                    <small class="text-muted">Update: {{ $latestPrice->date->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning mb-4">
                            <strong>💡 Informasi:</strong>
                            <ul class="mb-0">
                                <li>Nilai taksiran: 85% dari harga jual emas</li>
                                <li>Maksimal pinjaman: Sesuai nilai taksiran</li>
                                <li>Biaya admin: Rp 50.000</li>
                                <li>Jasa simpan: 1.5% per bulan dari pinjaman</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-2">💎 Emas Tersedia di Tabungan</h6>
                                    <h4 class="mb-0">{{ number_format($goldSaving->total_gold, 4) }} gram</h4>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('nasabah.pledges.store') }}" id="pledgeForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="gold_amount" class="form-label fw-bold">
                                        Jumlah Emas yang Digadaikan (gram) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control form-control-lg @error('gold_amount') is-invalid @enderror" 
                                           id="gold_amount" 
                                           name="gold_amount" 
                                           step="0.0001"
                                           min="0.0001"
                                           max="{{ $goldSaving->total_gold }}"
                                           value="{{ old('gold_amount') }}" 
                                           required 
                                           autofocus>
                                    @error('gold_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maksimal: {{ number_format($goldSaving->total_gold, 4) }} gram</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="loan_amount" class="form-label fw-bold">
                                        Jumlah Pinjaman (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control form-control-lg @error('loan_amount') is-invalid @enderror" 
                                           id="loan_amount" 
                                           name="loan_amount" 
                                           step="1000"
                                           min="0"
                                           value="{{ old('loan_amount') }}" 
                                           required>
                                    @error('loan_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maksimal sesuai nilai taksiran</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="duration_months" class="form-label fw-bold">
                                    Jangka Waktu (bulan) <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg @error('duration_months') is-invalid @enderror" 
                                        id="duration_months" 
                                        name="duration_months" 
                                        required>
                                    <option value="">Pilih Jangka Waktu</option>
                                    <option value="1" {{ old('duration_months') == '1' ? 'selected' : '' }}>1 Bulan</option>
                                    <option value="3" {{ old('duration_months') == '3' ? 'selected' : '' }}>3 Bulan</option>
                                    <option value="6" {{ old('duration_months') == '6' ? 'selected' : '' }}>6 Bulan</option>
                                    <option value="12" {{ old('duration_months') == '12' ? 'selected' : '' }}>12 Bulan</option>
                                </select>
                                @error('duration_months')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">Catatan (Opsional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Tambahkan catatan untuk pengajuan gadai ini">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Calculation Summary -->
                            <div class="card bg-light mb-4" id="summaryCard" style="display: none;">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">📊 Ringkasan Gadai Emas</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Jumlah Emas:</strong></p>
                                            <p class="mb-2"><strong>Harga jual per gram:</strong></p>
                                            <p class="mb-2"><strong>Nilai Taksiran:</strong></p>
                                            <p class="mb-2"><strong>Jumlah Pinjaman:</strong></p>
                                            <p class="mb-2"><strong>Biaya Admin:</strong></p>
                                            <p class="mb-2"><strong>Jasa Simpan:</strong></p>
                                            <p class="mb-0"><strong>Total yang Harus Dibayar:</strong></p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <p class="mb-2" id="summary-gold">0.0000 gram</p>
                                            <p class="mb-2">Rp <span id="summary-price">{{ number_format($latestPrice->sell_price, 0, ',', '.') }}</span></p>
                                            <p class="mb-2">Rp <span id="summary-appraisal">0</span></p>
                                            <p class="mb-2">Rp <span id="summary-loan">0</span></p>
                                            <p class="mb-2">Rp <span id="summary-admin">50.000</span></p>
                                            <p class="mb-2">Rp <span id="summary-storage">0</span></p>
                                            <p class="mb-0 fw-bold text-success fs-5">Rp <span id="summary-total">0</span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-0"><strong>Cicilan per Bulan:</strong></p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <p class="mb-0 fw-bold">Rp <span id="summary-monthly">0</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-gold btn-lg">
                                    <span class="me-2">✅</span> Ajukan Gadai Emas
                                </button>
                                <a href="{{ route('nasabah.pledges.index') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning text-center">
                            <div class="mb-2" style="font-size: 3rem;">⚠️</div>
                            <h5>Harga Emas Belum Tersedia</h5>
                            <p class="mb-0">Silakan hubungi admin untuk mengupdate harga emas terlebih dahulu.</p>
                            <a href="{{ route('nasabah.pledges.index') }}" class="btn btn-primary mt-3">Kembali</a>
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
    const loanAmountInput = document.getElementById('loan_amount');
    const durationSelect = document.getElementById('duration_months');
    const sellPrice = {{ $latestPrice ? $latestPrice->sell_price : 0 }};
    const summaryCard = document.getElementById('summaryCard');

    function calculateSummary() {
        const goldAmount = parseFloat(goldAmountInput.value) || 0;
        const loanAmount = parseFloat(loanAmountInput.value) || 0;
        const duration = parseInt(durationSelect.value) || 0;

        if (goldAmount > 0 && sellPrice > 0) {
            const appraisalValue = goldAmount * sellPrice * 0.85;
            const maxLoan = appraisalValue;
            const actualLoan = Math.min(loanAmount, maxLoan);
            const adminFee = 50000;
            const storageFeeRate = 1.5;
            const totalStorageFee = (actualLoan * storageFeeRate / 100) * duration;
            const totalAmount = actualLoan + adminFee + totalStorageFee;
            const monthlyInstallment = duration > 0 ? totalAmount / duration : 0;

            document.getElementById('summary-gold').textContent = goldAmount.toFixed(4) + ' gram';
            document.getElementById('summary-appraisal').textContent = appraisalValue.toLocaleString('id-ID');
            document.getElementById('summary-loan').textContent = actualLoan.toLocaleString('id-ID');
            document.getElementById('summary-storage').textContent = totalStorageFee.toLocaleString('id-ID');
            document.getElementById('summary-total').textContent = totalAmount.toLocaleString('id-ID');
            document.getElementById('summary-monthly').textContent = monthlyInstallment.toLocaleString('id-ID');

            // Update max loan amount
            if (loanAmountInput.value == '' || parseFloat(loanAmountInput.value) > maxLoan) {
                loanAmountInput.setAttribute('max', Math.floor(maxLoan));
            }

            summaryCard.style.display = 'block';
        } else {
            summaryCard.style.display = 'none';
        }
    }

    if (goldAmountInput && loanAmountInput && durationSelect) {
        goldAmountInput.addEventListener('input', calculateSummary);
        loanAmountInput.addEventListener('input', calculateSummary);
        durationSelect.addEventListener('change', calculateSummary);
    }
});
</script>
@endpush
@endsection
