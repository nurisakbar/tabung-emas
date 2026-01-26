@extends('layouts.app-bootstrap')

@section('title', 'Artikel & Berita - Tabung Emas')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">📰 Artikel & Berita</h1>
    
    @if($articles->count() > 0)
        <div class="row g-4">
            @foreach($articles as $article)
            <div class="col-md-4 col-sm-6">
                <div class="card service-card article-card h-100">
                    @if($article->image)
                    <img src="{{ $article->image }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span style="font-size: 3rem;">📰</span>
                    </div>
                    @endif
                    <div class="card-body">
                        @if($article->is_featured)
                        <span class="badge bg-warning mb-2">Featured</span>
                        @endif
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none text-dark">
                                {{ $article->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ Str::limit($article->excerpt, 120) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                @if($article->author)
                                ✍️ {{ $article->author }}
                                @endif
                                @if($article->published_at)
                                • {{ $article->published_at->format('d M Y') }}
                                @endif
                            </small>
                            <small class="text-muted">👁️ {{ $article->views }}</small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-primary">Baca Selengkapnya →</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-4">
            {{ $articles->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <div class="mb-2" style="font-size: 3rem;">📭</div>
            <h5>Belum ada artikel</h5>
            <p class="mb-0">Artikel akan ditampilkan di sini setelah dipublikasikan.</p>
        </div>
    @endif
</div>
@endsection
