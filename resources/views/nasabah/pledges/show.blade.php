@extends('layouts.app-bootstrap')

@section('title', 'Detail Gadai Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.pledges.index') }}">Gadai Emas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Gadai #{{ $pledge->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Pledge Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Gadai Emas #{{ $pledge->id }}</h4>
                        <span class="badge bg-{{ $pledge->status == 'active' ? 'success' : ($pledge->status == 'paid' ? 'info' : ($pledge->status == 'overdue' ? 'danger' : 'warning')) }} fs-6">
                            {{ ucfirst($pledge->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Emas yang Digadaikan</small>
                            <h5 class="mb-0">{{ number_format($pledge->gold_amount, 4) }} gram</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Harga jual per gram (saat pengajuan)</small>
                            <h5 class="mb-0">Rp {{ number_format($pledge->price_per_gram, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Nilai Taksiran</small>
                            <h5 class="mb-0">Rp {{ number_format($pledge->appraisal_value, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Jumlah Pinjaman</small>
                            <h5 class="mb-0">Rp {{ number_format($pledge->loan_amount, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Total yang Harus Dibayar</small>
                            <h5 class="mb-0">Rp {{ number_format($pledge->total_amount, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Progress Pembayaran</small>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $pledge->calculateProgress() }}%"
                                     aria-valuenow="{{ $pledge->calculateProgress() }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($pledge->calculateProgress(), 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                Rp {{ number_format($pledge->paid_amount, 0, ',', '.') }} / Rp {{ number_format($pledge->total_amount, 0, ',', '.') }}
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Mulai</small>
                            <p class="mb-0">
                                @if($pledge->start_date)
                                    {{ \Carbon\Carbon::parse($pledge->start_date)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Jatuh Tempo</small>
                            <p class="mb-0">
                                @if($pledge->end_date)
                                    {{ \Carbon\Carbon::parse($pledge->end_date)->format('d M Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">💳 Riwayat Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($pledge->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pledge->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->payment_type == 'full_payment' ? 'success' : 'info' }}">
                                                {{ $payment->payment_type == 'full_payment' ? 'Pelunasan' : 'Cicilan' }}
                                            </span>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($payment->payment_amount, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status == 'verified' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Belum ada pembayaran</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">⚡ Aksi</h5>
                </div>
                <div class="card-body">
                    @if($pledge->status == 'active' && !$pledge->isPaid())
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('nasabah.pledges.payment', $pledge->id) }}" class="btn btn-gold">
                                💳 Bayar Cicilan
                            </a>
                        </div>
                    @endif

                    @if($pledge->isPaid())
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('nasabah.pledges.redeem', $pledge->id) }}" class="btn btn-success">
                                🔓 Tebus Emas
                            </a>
                        </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('nasabah.pledges.index') }}" class="btn btn-outline-secondary">
                            ← Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Sisa yang Harus Dibayar</small>
                        <h5 class="mb-0 text-success">Rp {{ number_format($pledge->getRemainingAmount(), 0, ',', '.') }}</h5>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Cicilan per Bulan</small>
                        <h5 class="mb-0">Rp {{ number_format($pledge->getMonthlyInstallment(), 0, ',', '.') }}</h5>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Emas yang Digadaikan</small>
                        <h5 class="mb-0">{{ number_format($pledge->gold_amount, 4) }} gram</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
