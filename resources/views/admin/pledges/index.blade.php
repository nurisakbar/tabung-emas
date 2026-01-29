@extends('layouts.app-bootstrap')

@section('title', 'Manajemen Gadai Emas - Admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">🏦 Manajemen Gadai Emas</h1>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Gadai</h5>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h5 class="card-title text-muted">Pending</h5>
                    <h2 class="mb-0 text-warning">{{ $stats['pending'] }}</h2>
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
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pledges.index') }}" class="row g-3">
                <div class="col-md-4">
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
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.pledges.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Pledges Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Gadai Emas</h5>
        </div>
        <div class="card-body">
            @if($pledges->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nasabah</th>
                                <th>Emas</th>
                                <th class="text-end">Pinjaman</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Jatuh Tempo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pledges as $pledge)
                            <tr>
                                <td>#{{ $pledge->id }}</td>
                                <td>
                                    <div>{{ $pledge->user->name }}</div>
                                    <small class="text-muted">{{ $pledge->user->email }}</small>
                                </td>
                                <td>{{ number_format($pledge->gold_amount, 4) }} gram</td>
                                <td class="text-end">Rp {{ number_format($pledge->loan_amount, 0, ',', '.') }}</td>
                                <td>
                                    <div class="progress" style="height: 20px; width: 100px;">
                                        <div class="progress-bar" 
                                             style="width: {{ $pledge->calculateProgress() }}%">
                                            {{ number_format($pledge->calculateProgress(), 0) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pledge->status == 'active' ? 'success' : ($pledge->status == 'paid' ? 'info' : ($pledge->status == 'overdue' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($pledge->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($pledge->end_date)
                                        {{ \Carbon\Carbon::parse($pledge->end_date)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pledges.show', $pledge->id) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $pledges->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3" style="font-size: 3rem;">📭</div>
                    <p class="text-muted">Tidak ada gadai emas.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
