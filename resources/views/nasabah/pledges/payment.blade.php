@extends('layouts.app-bootstrap')

@section('title', 'Bayar Cicilan - Gadai Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.pledges.index') }}">Gadai Emas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.pledges.show', $pledge->id) }}">Detail Gadai</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bayar Cicilan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">💳 Bayar Cicilan Gadai</h3>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <h6 class="mb-2">Gadai Emas #{{ $pledge->id }}</h6>
                        <p class="mb-1"><strong>Emas:</strong> {{ number_format($pledge->gold_amount, 4) }} gram</p>
                        <p class="mb-0"><strong>Pinjaman:</strong> Rp {{ number_format($pledge->loan_amount, 0, ',', '.') }}</p>
                    </div>

                    <div class="alert alert-warning mb-4">
                        <h6 class="mb-2">Informasi Pembayaran</h6>
                        <p class="mb-1"><strong>Sisa yang Harus Dibayar:</strong> Rp {{ number_format($remainingAmount, 0, ',', '.') }}</p>
                        <p class="mb-0"><strong>Cicilan per Bulan:</strong> Rp {{ number_format($monthlyInstallment, 0, ',', '.') }}</p>
                    </div>

                    <form method="POST" action="{{ route('nasabah.pledges.payment.process', $pledge->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="payment_type" class="form-label fw-bold">
                                Tipe Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('payment_type') is-invalid @enderror" 
                                    id="payment_type" 
                                    name="payment_type" 
                                    required>
                                <option value="installment" {{ old('payment_type') == 'installment' ? 'selected' : '' }}>Cicilan</option>
                                <option value="full_payment" {{ old('payment_type') == 'full_payment' ? 'selected' : '' }}>Pelunasan</option>
                            </select>
                            @error('payment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_amount" class="form-label fw-bold">
                                Jumlah Pembayaran <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg @error('payment_amount') is-invalid @enderror" 
                                   id="payment_amount" 
                                   name="payment_amount" 
                                   step="0.01"
                                   min="0.01"
                                   max="{{ $remainingAmount }}"
                                   value="{{ old('payment_amount', $monthlyInstallment) }}" 
                                   required>
                            @error('payment_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Maksimal: Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="payment_date" class="form-label fw-bold">
                                Tanggal Pembayaran <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" 
                                   name="payment_date" 
                                   value="{{ old('payment_date', date('Y-m-d')) }}" 
                                   required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_method" class="form-label fw-bold">
                                Metode Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" 
                                    name="payment_method" 
                                    required>
                                <option value="">Pilih Metode</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4" id="referenceNumberField" style="display: none;">
                            <label for="reference_number" class="form-label fw-bold">
                                Nomor Referensi <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('reference_number') is-invalid @enderror" 
                                   id="reference_number" 
                                   name="reference_number" 
                                   value="{{ old('reference_number') }}" 
                                   placeholder="Masukkan nomor referensi pembayaran">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">Catatan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Tambahkan catatan untuk pembayaran ini">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <small>⚠️ Pembayaran akan diverifikasi oleh admin terlebih dahulu sebelum dicatat.</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-gold btn-lg">
                                <span class="me-2">✅</span> Kirim Pembayaran
                            </button>
                            <a href="{{ route('nasabah.pledges.show', $pledge->id) }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentTypeSelect = document.getElementById('payment_type');
    const paymentAmountInput = document.getElementById('payment_amount');
    const referenceNumberField = document.getElementById('referenceNumberField');
    const referenceNumberInput = document.getElementById('reference_number');
    const remainingAmount = {{ $remainingAmount }};

    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'transfer') {
                referenceNumberField.style.display = 'block';
                referenceNumberInput.setAttribute('required', 'required');
            } else {
                referenceNumberField.style.display = 'none';
                referenceNumberInput.removeAttribute('required');
            }
        });
    }

    if (paymentTypeSelect && paymentAmountInput) {
        paymentTypeSelect.addEventListener('change', function() {
            if (this.value === 'full_payment') {
                paymentAmountInput.value = remainingAmount;
                paymentAmountInput.setAttribute('readonly', 'readonly');
            } else {
                paymentAmountInput.removeAttribute('readonly');
                paymentAmountInput.value = {{ $monthlyInstallment }};
            }
        });
    }
});
</script>
@endpush
@endsection
