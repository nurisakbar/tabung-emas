@extends('layouts.app-bootstrap')

@section('title', 'Edit Harga Emas')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Harga Emas</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.gold-prices.update', $goldPrice) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $goldPrice->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="buy_price" class="form-label">Harga Beli (per gram)</label>
                            <input type="number" step="0.01" class="form-control @error('buy_price') is-invalid @enderror" id="buy_price" name="buy_price" value="{{ old('buy_price', $goldPrice->buy_price) }}" required>
                            @error('buy_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="sell_price" class="form-label">Harga Jual (per gram)</label>
                            <input type="number" step="0.01" class="form-control @error('sell_price') is-invalid @enderror" id="sell_price" name="sell_price" value="{{ old('sell_price', $goldPrice->sell_price) }}" required>
                            @error('sell_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.gold-prices.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
