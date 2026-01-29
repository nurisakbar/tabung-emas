@extends('layouts.app-bootstrap')

@section('title', 'Detail Paket Cicil Emas - Admin')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.installments.index') }}">Cicil Emas</a></li>
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
                            <small class="text-muted">Nasabah</small>
                            <h6 class="mb-0">{{ $plan->user->name }}</h6>
                            <small class="text-muted">{{ $plan->user->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Jumlah Emas</small>
                            <h5 class="mb-0">{{ number_format($plan->gold_amount, 4) }} gram</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Total Harga</small>
                            <h5 class="mb-0">Rp {{ number_format($plan->total_price, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Progress</small>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" 
                                     style="width: {{ $plan->calculateProgress() }}%">
                                    {{ number_format($plan->calculateProgress(), 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ $plan->paid_installments }} / {{ $plan->total_installments }} periode
                            </small>
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
                                    <th class="text-end">Dibayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plan->schedules as $schedule)
                                <tr>
                                    <td>#{{ $schedule->installment_number }}</td>
                                    <td>{{ $schedule->due_date->format('d M Y') }}</td>
                                    <td class="text-end">Rp {{ number_format($schedule->amount, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($schedule->paid_amount, 0, ',', '.') }}</td>
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
                                        <th>Referensi</th>
                                        <th>Emas</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plan->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                        <td class="text-end">Rp {{ number_format($payment->payment_amount, 0, ',', '.') }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>{{ $payment->reference_number ?? '-' }}</td>
                                        <td>{{ number_format($payment->allocated_gold, 4) }} gram</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status == 'verified' ? 'success' : ($payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->verifier)
                                                <small>{{ $payment->verifier->name }}</small><br>
                                                <small class="text-muted">{{ $payment->verified_at->format('d M Y H:i') }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->status == 'pending')
                                                <form method="POST" action="{{ route('admin.installments.payments.verify', $payment->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')">
                                                        ✓ Verifikasi
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payment->id }}">
                                                    ✗ Tolak
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.installments.payments.reject', $payment->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="notes" rows="3" required placeholder="Jelaskan alasan penolakan"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
            <!-- Summary -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Sisa yang Harus Dibayar</small>
                        <h5 class="mb-0 text-success">Rp {{ number_format($plan->getRemainingAmount(), 0, ',', '.') }}</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Total Emas</small>
                        <h5 class="mb-0">{{ number_format($plan->gold_amount, 4) }} gram</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Cicilan per {{ $plan->frequency == 'monthly' ? 'bulan' : 'minggu' }}</small>
                        <h5 class="mb-0">Rp {{ number_format($plan->installment_amount, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.installments.index') }}" class="btn btn-outline-secondary">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
