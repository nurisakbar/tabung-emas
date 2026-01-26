@extends('layouts.app-bootstrap')

@section('title', 'Event & Promo - Tabung Emas')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">📅 Event & Promo</h1>
    
    @if($upcomingEvents->count() > 0)
    <div class="mb-5">
        <h2 class="mb-4">Event Mendatang</h2>
        <div class="row g-4">
            @foreach($upcomingEvents as $event)
            <div class="col-md-4 col-sm-6">
                <div class="card service-card event-card h-100">
                    @if($event->image)
                    <img src="{{ $event->image }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span style="font-size: 3rem;">📅</span>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-success mb-2">{{ $event->status === 'upcoming' ? 'Akan Datang' : ucfirst($event->status) }}</span>
                        @if($event->is_featured)
                        <span class="badge bg-warning mb-2">Featured</span>
                        @endif
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none text-dark">
                                {{ $event->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                        <div class="d-flex align-items-center text-muted small mb-2">
                            <span class="me-3">📆 {{ $event->event_date->format('d M Y') }}</span>
                            @if($event->event_time)
                            <span>🕐 {{ $event->event_time }}</span>
                            @endif
                        </div>
                        @if($event->location)
                        <p class="text-muted small mb-3">📍 {{ $event->location }}</p>
                        @endif
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-primary">Lihat Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if($pastEvents->count() > 0)
    <div class="mt-5">
        <h2 class="mb-4">Event Sebelumnya</h2>
        <div class="row g-4">
            @foreach($pastEvents as $event)
            <div class="col-md-4 col-sm-6">
                <div class="card service-card event-card h-100">
                    @if($event->image)
                    <img src="{{ $event->image }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span style="font-size: 3rem;">📅</span>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2">Selesai</span>
                        @if($event->is_featured)
                        <span class="badge bg-warning mb-2">Featured</span>
                        @endif
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('events.show', $event->id) }}" class="text-decoration-none text-dark">
                                {{ $event->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                        <div class="d-flex align-items-center text-muted small mb-2">
                            <span class="me-3">📆 {{ $event->event_date->format('d M Y') }}</span>
                        </div>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-outline-secondary">Lihat Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if($upcomingEvents->count() == 0 && $pastEvents->count() == 0)
    <div class="alert alert-info text-center py-5">
        <div class="mb-2" style="font-size: 3rem;">📭</div>
        <h5>Belum ada event</h5>
        <p class="mb-0">Event akan ditampilkan di sini setelah ditambahkan.</p>
    </div>
    @endif
</div>
@endsection
