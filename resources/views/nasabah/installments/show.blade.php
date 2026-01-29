@extends('layouts.app-bootstrap')

@section('title', 'Detail Paket Cicil Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nasabah.installments.index') }}">Cicil Emas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Paket #{{ $plan->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Plan Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Paket Cicil Emas #{{ $plan->id }}</h4>
                        <span class="badge bg-{{ $plan->status == 'active' ? 'success' : ($plan->status == 'completed' ? 'info' : ($plan->status == 'overdue' ? 'danger' : 'warning')) }} fs-6">
                            {{ ucfirst($plan->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Jumlah Emas</small>
                            <h5 class="mb-0">{{ number_format($plan->gold_amount, 4) }} gram</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Harga per gram (saat pembuatan)</small>
                            <h5 class="mb-0">Rp {{ number_format($plan->price_per_gram, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Total Harga</small>
                            <h5 class="mb-0">Rp {{ number_format($plan->total_price, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Down Payment</small>
                            <h5 class="mb-0">Rp {{ number_format($plan->down_payment, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Cicilan per {{ $plan->frequency == 'monthly' ? 'bulan' : 'minggu' }}</small>
                            <h5 class="mb-0">Rp {{ number_format($plan->installment_amount, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Progress</small>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $plan->calculateProgress() }}%"
                                     aria-valuenow="{{ $plan->calculateProgress() }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($plan->calculateProgress(), 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ $plan->paid_installments }} / {{ $plan->total_installments }} periode
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Mulai</small>
                            <p class="mb-0">{{ $plan->start_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Selesai</small>
                            <p class="mb-0">{{ $plan->end_date ? $plan->end_date->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Schedules -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📅 Jadwal Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Jatuh Tempo</th>
                                    <th class="text-end">Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plan->schedules as $schedule)
                                <tr>
                                    <td>#{{ $schedule->installment_number }}</td>
                                    <td>{{ $schedule->due_date->format('d M Y') }}</td>
                                    <td class="text-end">Rp {{ number_format($schedule->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $schedule->status == 'paid' ? 'success' : ($schedule->status == 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($schedule->status) }}
                                        </span>
                                        @if($schedule->late_fee > 0)
                                            <br><small class="text-danger">Denda: Rp {{ number_format($schedule->late_fee, 0, ',', '.') }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">💳 Riwayat Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($plan->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Metode</th>
                                        <th>Emas Dialokasikan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plan->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                        <td class="text-end">Rp {{ number_format($payment->payment_amount, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>{{ number_format($payment->allocated_gold, 4) }} gram</td>
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
                    @if($plan->status == 'active' && $nextSchedule)
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('nasabah.installments.payment', $plan->id) }}" class="btn btn-gold">
                                💳 Bayar Cicilan
                            </a>
                        </div>
                        <div class="alert alert-info">
                            <small><strong>Pembayaran Berikutnya:</strong><br>
                            {{ $nextSchedule->due_date->format('d M Y') }}<br>
                            Rp {{ number_format($nextSchedule->amount, 0, ',', '.') }}</small>
                        </div>
                    @endif

                    @if($plan->status == 'active' && !$plan->isCompleted())
                        <div class="d-grid gap-2 mb-3">
                            <a href="{{ route('nasabah.installments.early-payment', $plan->id) }}" class="btn btn-outline-success">
                                ⚡ Pelunasan Lebih Cepat
                            </a>
                        </div>
                    @endif

                    @if($plan->canBeCancelled())
                        <div class="d-grid gap-2">
                            <a href="{{ route('nasabah.installments.cancel', $plan->id) }}" class="btn btn-outline-danger">
                                ❌ Batalkan Paket
                            </a>
                        </div>
                    @endif

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('nasabah.installments.index') }}" class="btn btn-outline-secondary">
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
                        <h5 class="mb-0 text-success">Rp {{ number_format($plan->getRemainingAmount(), 0, ',', '.') }}</h5>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Total Emas yang Akan Didapat</small>
                        <h5 class="mb-0">{{ number_format($plan->gold_amount, 4) }} gram</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
