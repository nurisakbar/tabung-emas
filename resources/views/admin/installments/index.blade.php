@extends('layouts.app-bootstrap')

@section('title', 'Manajemen Cicil Emas - Admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">📅 Manajemen Cicil Emas</h1>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Paket</h5>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h5 class="card-title text-muted">Aktif</h5>
                    <h2 class="mb-0 text-success">{{ $stats['active'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h5 class="card-title text-muted">Overdue</h5>
                    <h2 class="mb-0 text-danger">{{ $stats['overdue'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h5 class="card-title text-muted">Pending Verifikasi</h5>
                    <h2 class="mb-0 text-warning">{{ $stats['pending_verification'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.installments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari Nasabah</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nama atau email">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" 
                           class="form-control" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" 
                           class="form-control" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.installments.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Paket Cicilan</h5>
        </div>
        <div class="card-body">
            @if($plans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nasabah</th>
                                <th>Emas</th>
                                <th class="text-end">Total Harga</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Tanggal Mulai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                            <tr>
                                <td>#{{ $plan->id }}</td>
                                <td>
                                    <div>{{ $plan->user->name }}</div>
                                    <small class="text-muted">{{ $plan->user->email }}</small>
                                </td>
                                <td>{{ number_format($plan->gold_amount, 4) }} gram</td>
                                <td class="text-end">Rp {{ number_format($plan->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 100px;">
                                        <div class="progress-bar" 
                                             style="width: {{ $plan->calculateProgress() }}%">
                                            {{ number_format($plan->calculateProgress(), 0) }}%
                                        </div>
                                    </div>
                                    <small>{{ $plan->paid_installments }}/{{ $plan->total_installments }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $plan->status == 'active' ? 'success' : ($plan->status == 'completed' ? 'info' : ($plan->status == 'overdue' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($plan->status) }}
                                    </span>
                                </td>
                                <td>{{ $plan->start_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.installments.show', $plan->id) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $plans->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3" style="font-size: 3rem;">📭</div>
                    <p class="text-muted">Tidak ada paket cicilan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
