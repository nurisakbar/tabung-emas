@extends('layouts.app-bootstrap')

@section('title', 'Kelola Harga Emas')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kelola Harga Emas</h1>
        <a href="{{ route('admin.gold-prices.create') }}" class="btn btn-primary">Tambah Harga Baru</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                <tr>
                    <td>{{ $price->date->format('d M Y') }}</td>
                    <td>Rp {{ number_format($price->buy_price, 0, ',', '.') }}/gram</td>
                    <td>Rp {{ number_format($price->sell_price, 0, ',', '.') }}/gram</td>
                    <td>{{ $price->creator->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.gold-prices.edit', $price) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-3">
        {{ $prices->links() }}
    </div>
</div>
@endsection
