@extends('layouts.app-bootstrap')

@section('title', 'Profile')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">👤 Profile Saya</h1>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card price-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Profile</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('nasabah.profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">No. Telepon</label>
                            <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="4" placeholder="Masukkan alamat lengkap">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gold">💾 Update Profile</button>
                            <a href="{{ route('nasabah.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card price-card">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 4rem;">👤</div>
                    <h5 class="fw-bold">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2"><strong>Status:</strong> 
                            <span class="badge bg-success">Aktif</span>
                        </p>
                        <p class="mb-0"><strong>Member sejak:</strong><br>
                            <small class="text-muted">{{ auth()->user()->created_at->format('d M Y') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
