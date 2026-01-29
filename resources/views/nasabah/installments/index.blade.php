@extends('layouts.app-bootstrap')

@section('title', 'Cicil Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">📅 Paket Cicil Emas Saya</h1>
        <a href="{{ route('nasabah.installments.create') }}" class="btn btn-gold">
            <span class="me-2">➕</span> Buat Paket Baru
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('nasabah.installments.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Filter Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('nasabah.installments.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Plans List -->
    @if($plans->count() > 0)
        <div class="row">
            @foreach($plans as $plan)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Paket #{{ $plan->id }}</h6>
                            <span class="badge bg-{{ $plan->status == 'active' ? 'success' : ($plan->status == 'completed' ? 'info' : ($plan->status == 'overdue' ? 'danger' : 'warning')) }}">
                                {{ ucfirst($plan->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Jumlah Emas</small>
                            <h5 class="mb-0">{{ number_format($plan->gold_amount, 4) }} gram</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Total Harga</small>
                            <h6 class="mb-0">Rp {{ number_format($plan->total_price, 0, ',', '.') }}</h6>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Progress</small>
                            <div class="progress" style="height: 20px;">
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
                        @php
                            $nextSchedule = $plan->schedules()->where('status', 'pending')->orderBy('due_date')->first();
                        @endphp
                        @if($nextSchedule)
                        <div class="mb-3">
                            <small class="text-muted">Pembayaran Berikutnya</small>
                            <p class="mb-0">
                                <strong>{{ $nextSchedule->due_date->format('d M Y') }}</strong><br>
                                <small>Rp {{ number_format($nextSchedule->amount, 0, ',', '.') }}</small>
                            </p>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-grid gap-2">
                            <a href="{{ route('nasabah.installments.show', $plan->id) }}" class="btn btn-outline-primary btn-sm">
                                Detail
                            </a>
                            @if($plan->status == 'active' && $nextSchedule)
                            <a href="{{ route('nasabah.installments.payment', $plan->id) }}" class="btn btn-gold btn-sm">
                                Bayar Cicilan
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $plans->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-3" style="font-size: 4rem;">📭</div>
                <h5>Belum Ada Paket Cicilan</h5>
                <p class="text-muted">Mulai cicil emas Anda sekarang untuk investasi masa depan yang lebih baik.</p>
                <a href="{{ route('nasabah.installments.create') }}" class="btn btn-gold">
                    <span class="me-2">➕</span> Buat Paket Cicilan Baru
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
