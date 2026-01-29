@extends('layouts.app-bootstrap')

@section('title', 'Detail Gadai Emas - Admin')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pledges.index') }}">Gadai Emas</a></li>
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
                            <small class="text-muted">Nasabah</small>
                            <h6 class="mb-0">{{ $pledge->user->name }}</h6>
                            <small class="text-muted">{{ $pledge->user->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Emas yang Digadaikan</small>
                            <h5 class="mb-0">{{ number_format($pledge->gold_amount, 4) }} gram</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted">Jumlah Pinjaman</small>
                            <h5 class="mb-0">Rp {{ number_format($pledge->loan_amount, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Progress Pembayaran</small>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar" 
                                     style="width: {{ $pledge->calculateProgress() }}%">
                                    {{ number_format($pledge->calculateProgress(), 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                Rp {{ number_format($pledge->paid_amount, 0, ',', '.') }} / Rp {{ number_format($pledge->total_amount, 0, ',', '.') }}
                            </small>
                        </div>
                    </div>
                    @if($pledge->status == 'pending')
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.pledges.approve', $pledge->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui pengajuan gadai emas ini?')">
                                ✓ Setujui
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            ✗ Tolak
                        </button>
                    </div>
                    @endif
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
                                        <th>Verifikasi</th>
                                        <th>Aksi</th>
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
                                                <form method="POST" action="{{ route('admin.pledges.payments.verify', $payment->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi pembayaran ini?')">
                                                        ✓ Verifikasi
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal{{ $payment->id }}">
                                                    ✗ Tolak
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Reject Payment Modal -->
                                    <div class="modal fade" id="rejectPaymentModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.pledges.payments.reject', $payment->id) }}">
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
                        <h5 class="mb-0 text-success">Rp {{ number_format($pledge->getRemainingAmount(), 0, ',', '.') }}</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Emas</small>
                        <h5 class="mb-0">{{ number_format($pledge->gold_amount, 4) }} gram</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Cicilan per Bulan</small>
                        <h5 class="mb-0">Rp {{ number_format($pledge->getMonthlyInstallment(), 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.pledges.index') }}" class="btn btn-outline-secondary">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Pledge Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.pledges.reject', $pledge->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan Gadai</h5>
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
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
