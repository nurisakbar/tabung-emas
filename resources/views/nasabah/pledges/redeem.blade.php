@extends('layouts.app-bootstrap')

@section('title', 'Tebus Emas - Gadai Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.pledges.index') }}">Gadai Emas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.pledges.show', $pledge->id) }}">Detail Gadai</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tebus Emas</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h3 class="mb-0">🔓 Tebus Emas</h3>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-success mb-4">
                        <h6 class="mb-2">✅ Gadai Emas Sudah Lunas</h6>
                        <p class="mb-1"><strong>Gadai #{{ $pledge->id }}</strong></p>
                        <p class="mb-1"><strong>Emas yang Digadaikan:</strong> {{ number_format($pledge->gold_amount, 4) }} gram</p>
                        <p class="mb-0"><strong>Total Pembayaran:</strong> Rp {{ number_format($pledge->paid_amount, 0, ',', '.') }}</p>
                    </div>

                    <div class="alert alert-info mb-4">
                        <h6 class="mb-2">💡 Informasi</h6>
                        <p class="mb-0">Setelah proses tebus, emas {{ number_format($pledge->gold_amount, 4) }} gram akan dikembalikan ke tabungan emas Anda dan dapat dijual atau ditarik kembali.</p>
                    </div>

                    <form method="POST" action="{{ route('nasabah.pledges.redeem.process', $pledge->id) }}">
                        @csrf

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">Konfirmasi Tebus Emas</h6>
                                <p class="mb-2"><strong>Apakah Anda yakin ingin menebus emas ini?</strong></p>
                                <p class="mb-0 text-muted">Emas akan dikembalikan ke tabungan Anda setelah proses selesai.</p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <span class="me-2">✅</span> Konfirmasi Tebus Emas
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
@endsection
