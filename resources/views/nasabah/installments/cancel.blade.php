@extends('layouts.app-bootstrap')

@section('title', 'Batalkan Paket - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.installments.index') }}">Cicil Emas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.installments.show', $plan->id) }}">Detail Paket</a></li>
            <li class="breadcrumb-item active" aria-current="page">Batalkan Paket</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">❌ Batalkan Paket Cicilan</h3>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-warning mb-4">
                        <h6 class="mb-2">⚠️ Peringatan</h6>
                        <p class="mb-0">Anda akan membatalkan paket cicilan ini. Pastikan Anda yakin dengan keputusan ini.</p>
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="mb-3">Informasi Paket</h6>
                            <p class="mb-2"><strong>Paket #{{ $plan->id }}</strong></p>
                            <p class="mb-2"><strong>Jumlah Emas:</strong> {{ number_format($plan->gold_amount, 4) }} gram</p>
                            <p class="mb-2"><strong>Total Harga:</strong> Rp {{ number_format($plan->total_price, 0, ',', '.') }}</p>
                            <p class="mb-0"><strong>Status:</strong> {{ ucfirst($plan->status) }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('nasabah.installments.cancel.process', $plan->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="reason" class="form-label fw-bold">
                                Alasan Pembatalan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" 
                                      name="reason" 
                                      rows="5" 
                                      placeholder="Jelaskan alasan Anda membatalkan paket cicilan ini" 
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <span class="me-2">❌</span> Konfirmasi Pembatalan
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
@endsection
