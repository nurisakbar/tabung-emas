@extends('layouts.app-bootstrap')

@section('title', 'Pelunasan Lebih Cepat - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.installments.index') }}">Cicil Emas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.installments.show', $plan->id) }}">Detail Paket</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pelunasan Lebih Cepat</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">⚡ Pelunasan Lebih Cepat</h3>
                </div>
                <div class="card-body p-4">
                    <!-- Plan Info -->
                    <div class="alert alert-info mb-4">
                        <h6 class="mb-2">Paket Cicil Emas #{{ $plan->id }}</h6>
                        <p class="mb-1"><strong>Jumlah Emas:</strong> {{ number_format($plan->gold_amount, 4) }} gram</p>
                        <p class="mb-0"><strong>Total Harga:</strong> Rp {{ number_format($plan->total_price, 0, ',', '.') }}</p>
                    </div>

                    <div class="alert alert-success mb-4">
                        <h6 class="mb-2">Sisa yang Harus Dibayar</h6>
                        <h3 class="mb-0 text-success">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</h3>
                        <small>Dengan membayar jumlah ini, semua cicilan tersisa akan dilunasi dan emas akan langsung dialokasikan ke tabungan Anda.</small>
                    </div>

                    <form method="POST" action="{{ route('nasabah.installments.early-payment.process', $plan->id) }}">
                        @csrf

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
                            <small>⚠️ Pembayaran akan diverifikasi oleh admin terlebih dahulu sebelum emas dialokasikan ke tabungan Anda.</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <span class="me-2">✅</span> Konfirmasi Pelunasan
                            </button>
                            <a href="{{ route('nasabah.installments.show', $plan->id) }}" class="btn btn-outline-secondary">
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
    const referenceNumberField = document.getElementById('referenceNumberField');
    const referenceNumberInput = document.getElementById('reference_number');

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

        if (paymentMethodSelect.value === 'transfer') {
            referenceNumberField.style.display = 'block';
        }
    }
});
</script>
@endpush
@endsection
