@extends('layouts.app-bootstrap')

@section('title', $article->title . ' - Tabung Emas')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Artikel</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
                </ol>
            </nav>
            
            <article>
                @if($article->image)
                <img src="{{ $article->image }}" class="img-fluid rounded mb-4" alt="{{ $article->title }}" style="max-height: 400px; width: 100%; object-fit: cover;">
                @endif
                
                <div class="mb-3">
                    @if($article->is_featured)
                    <span class="badge bg-warning mb-2">Featured</span>
                    @endif
                    <h1 class="fw-bold mb-3">{{ $article->title }}</h1>
                    <div class="d-flex align-items-center text-muted mb-3">
                        @if($article->author)
                        <span class="me-3">✍️ {{ $article->author }}</span>
                        @endif
                        @if($article->published_at)
                        <span class="me-3">📅 {{ $article->published_at->format('d M Y') }}</span>
                        @endif
                        <span>👁️ {{ $article->views }} views</span>
                    </div>
                </div>
                
                <div class="article-content">
                    <p class="lead text-muted mb-4">{{ $article->excerpt }}</p>
                    <div class="content-body">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
            </article>
            
            <div class="mt-5 pt-4 border-top">
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">← Kembali ke Daftar Artikel</a>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card price-card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Artikel Terkait</h5>
                </div>
                <div class="card-body">
                    @if($relatedArticles->count() > 0)
                        @foreach($relatedArticles as $related)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="fw-bold">
                                <a href="{{ route('articles.show', $related->slug) }}" class="text-decoration-none text-dark">
                                    {{ $related->title }}
                                </a>
                            </h6>
                            <small class="text-muted">{{ $related->published_at->format('d M Y') }}</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Tidak ada artikel terkait.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
