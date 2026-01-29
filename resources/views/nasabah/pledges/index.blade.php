@extends('layouts.app-bootstrap')

@section('title', 'Gadai Emas - Tabung Emas')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">🏦 Gadai Emas Saya</h1>
        <a href="{{ route('nasabah.pledges.create') }}" class="btn btn-gold">
            <span class="me-2">➕</span> Ajukan Gadai Baru
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('nasabah.pledges.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Filter Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('nasabah.pledges.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Pledges List -->
    @if($pledges->count() > 0)
        <div class="row">
            @foreach($pledges as $pledge)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Gadai #{{ $pledge->id }}</h6>
                            <span class="badge bg-{{ $pledge->status == 'active' ? 'success' : ($pledge->status == 'paid' ? 'info' : ($pledge->status == 'overdue' ? 'danger' : 'warning')) }}">
                                {{ ucfirst($pledge->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Emas yang Digadaikan</small>
                            <h5 class="mb-0">{{ number_format($pledge->gold_amount, 4) }} gram</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Jumlah Pinjaman</small>
                            <h6 class="mb-0">Rp {{ number_format($pledge->loan_amount, 0, ',', '.') }}</h6>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Progress Pembayaran</small>
                            <div class="progress" style="height: 20px;">
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
                        @if($pledge->end_date)
                        <div class="mb-3">
                            <small class="text-muted">Jatuh Tempo</small>
                            <p class="mb-0">
                                <strong>{{ \Carbon\Carbon::parse($pledge->end_date)->format('d M Y') }}</strong>
                            </p>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-grid gap-2">
                            <a href="{{ route('nasabah.pledges.show', $pledge->id) }}" class="btn btn-outline-primary btn-sm">
                                Detail
                            </a>
                            @if($pledge->status == 'active' && !$pledge->isPaid())
                            <a href="{{ route('nasabah.pledges.payment', $pledge->id) }}" class="btn btn-gold btn-sm">
                                Bayar Cicilan
                            </a>
                            @endif
                            @if($pledge->isPaid())
                            <a href="{{ route('nasabah.pledges.redeem', $pledge->id) }}" class="btn btn-success btn-sm">
                                Tebus Emas
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $pledges->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-3" style="font-size: 4rem;">📭</div>
                <h5>Belum Ada Gadai Emas</h5>
                <p class="text-muted">Gadaikan emas Anda untuk mendapatkan pinjaman tunai dengan proses yang mudah.</p>
                <a href="{{ route('nasabah.pledges.create') }}" class="btn btn-gold">
                    <span class="me-2">➕</span> Ajukan Gadai Emas
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
