@extends('layouts.app-bootstrap')

@section('title', $event->title . ' - Tabung Emas')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Event</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($event->title, 30) }}</li>
                </ol>
            </nav>
            
            <article>
                @if($event->image)
                <img src="{{ $event->image }}" class="img-fluid rounded mb-4" alt="{{ $event->title }}" style="max-height: 400px; width: 100%; object-fit: cover;">
                @endif
                
                <div class="mb-3">
                    <span class="badge bg-{{ $event->status === 'upcoming' ? 'success' : ($event->status === 'ongoing' ? 'warning' : 'secondary') }} mb-2">
                        {{ $event->status === 'upcoming' ? 'Akan Datang' : ($event->status === 'ongoing' ? 'Sedang Berlangsung' : 'Selesai') }}
                    </span>
                    @if($event->is_featured)
                    <span class="badge bg-warning mb-2">Featured</span>
                    @endif
                    <h1 class="fw-bold mb-3">{{ $event->title }}</h1>
                    
                    <div class="card price-card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2" style="font-size: 1.5rem;">📆</span>
                                        <div>
                                            <small class="text-muted d-block">Tanggal</small>
                                            <strong>{{ $event->event_date->format('d F Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @if($event->event_time)
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2" style="font-size: 1.5rem;">🕐</span>
                                        <div>
                                            <small class="text-muted d-block">Waktu</small>
                                            <strong>{{ $event->event_time }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($event->location)
                                <div class="col-md-12 mt-3">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2" style="font-size: 1.5rem;">📍</span>
                                        <div>
                                            <small class="text-muted d-block">Lokasi</small>
                                            <strong>{{ $event->location }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="article-content">
                    <h4 class="fw-bold mb-3">Deskripsi Event</h4>
                    <div class="content-body">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </article>
            
            <div class="mt-5 pt-4 border-top">
                <a href="{{ route('events.index') }}" class="btn btn-outline-primary">← Kembali ke Daftar Event</a>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card price-card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Event Lainnya</h5>
                </div>
                <div class="card-body">
                    @if($relatedEvents->count() > 0)
                        @foreach($relatedEvents as $related)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="fw-bold">
                                <a href="{{ route('events.show', $related->id) }}" class="text-decoration-none text-dark">
                                    {{ $related->title }}
                                </a>
                            </h6>
                            <small class="text-muted">{{ $related->event_date->format('d M Y') }}</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Tidak ada event lainnya.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
