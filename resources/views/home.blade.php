@extends('layouts.app-bootstrap')

@section('title', 'Beranda - Tabung Emas')

@section('content')
<!-- Hero Slider Section - Full Width -->
<div class="hero-slider-wrapper mb-5">
    <div id="heroCarousel" class="carousel slide hero-slider" data-bs-ride="carousel" data-bs-interval="2000" data-bs-pause="false">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1920&h=600&fit=crop" class="d-block w-100" alt="Investasi Emas">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="display-5 fw-bold mb-3">Investasi Emas untuk Masa Depan</h2>
                    <p class="lead mb-4">Platform tabungan emas terpercaya dengan harga transparan dan proses yang mudah.</p>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Daftar Sekarang</a>
                    @else
                    <a href="{{ route('nasabah.dashboard') }}" class="btn btn-light btn-lg">Dashboard Saya</a>
                    @endguest
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=1920&h=600&fit=crop" class="d-block w-100" alt="Tabungan Emas">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="display-5 fw-bold mb-3">Tabungan Emas yang Aman</h2>
                    <p class="lead mb-4">Mulai tabungan emas Anda dengan mudah dan aman. Investasi jangka panjang untuk masa depan yang lebih baik.</p>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Mulai Investasi</a>
                    @else
                    <a href="{{ route('nasabah.gold-saving') }}" class="btn btn-light btn-lg">Lihat Tabungan</a>
                    @endguest
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1606166188517-5ad47ea5e0a0?w=1920&h=600&fit=crop" class="d-block w-100" alt="Harga Real-time">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="display-5 fw-bold mb-3">Harga Emas Real-time</h2>
                    <p class="lead mb-4">Pantau harga emas terkini setiap saat. Informasi harga yang akurat untuk keputusan investasi terbaik.</p>
                    <a href="{{ route('nasabah.gold-price') }}" class="btn btn-gold btn-lg">Lihat Harga</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Harga Emas Section -->
@if($latestPrice)
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="gold-card">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3" style="font-size: 3rem;">⬆️</div>
                        <h5 class="card-title mb-3">Harga Beli</h5>
                        <h2 class="mb-2 fw-bold">Rp {{ number_format($latestPrice->buy_price, 0, ',', '.') }}</h2>
                        <small class="opacity-75">per gram</small>
                        <p class="mt-3 mb-0"><small class="opacity-75">Update: {{ $latestPrice->date->format('d M Y') }}</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card price-card price-sell h-100">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3" style="font-size: 3rem;">⬇️</div>
                        <h5 class="card-title mb-3">Harga Jual</h5>
                        <h2 class="mb-2 fw-bold text-danger">Rp {{ number_format($latestPrice->sell_price, 0, ',', '.') }}</h2>
                        <small class="text-muted">per gram</small>
                        <p class="mt-3 mb-0"><small class="text-muted">Update: {{ $latestPrice->date->format('d M Y') }}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Chart Section -->
@if(isset($chartPrices) && $chartPrices->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center section-title mb-4">📈 Grafik Harga Emas Harian (30 Hari Terakhir)</h2>
        <div class="row">
            <div class="col-12">
                <div class="card price-card">
                    <div class="card-body p-4">
                        <div class="chart-container">
                            <canvas id="goldPriceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Tunggu sampai Chart.js tersedia
function initChart() {
    // Pastikan Chart.js tersedia
    if (typeof Chart === 'undefined') {
        console.log('Waiting for Chart.js to load...');
        setTimeout(initChart, 100);
        return;
    }
    
    const ctx = document.getElementById('goldPriceChart');
    if (!ctx) {
        console.error('Chart canvas element not found');
        return;
    }
    
    // Deteksi mobile - HARUS didefinisikan SEBELUM digunakan
    const isMobile = window.innerWidth < 768;
    
    const chartData = @json($chartPrices);
    
    // Pastikan data diurutkan berdasarkan tanggal
    const sortedData = chartData.sort((a, b) => new Date(a.date) - new Date(b.date));
    
    const labels = sortedData.map(item => {
        const date = new Date(item.date + 'T00:00:00');
        // Format: "24 Jan" untuk desktop, "24/01" untuk mobile
        if (isMobile) {
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' });
        }
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    
    const buyPrices = sortedData.map(item => parseFloat(item.buy_price));
    const sellPrices = sortedData.map(item => parseFloat(item.sell_price));
    
    // Konfigurasi untuk mobile dan desktop
    const chartConfig = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Harga Beli',
                    data: buyPrices,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: isMobile ? 2 : 3,
                    pointHoverRadius: isMobile ? 4 : 5,
                },
                {
                    label: 'Harga Jual',
                    data: sellPrices,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: isMobile ? 2 : 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: isMobile ? 2 : 3,
                    pointHoverRadius: isMobile ? 4 : 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: isMobile ? 1.5 : 2.5,
            devicePixelRatio: 2, // Untuk retina displays
            animation: {
                duration: 0 // Disable animation untuk performa lebih baik
            },
            plugins: {
                legend: {
                    display: true,
                    position: isMobile ? 'bottom' : 'top',
                    labels: {
                        usePointStyle: true,
                        padding: isMobile ? 10 : 15,
                        font: {
                            size: isMobile ? 11 : 14,
                            weight: 'bold'
                        },
                        boxWidth: isMobile ? 10 : 12,
                        boxHeight: isMobile ? 10 : 12
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    padding: isMobile ? 8 : 12,
                    titleFont: {
                        size: isMobile ? 11 : 13
                    },
                    bodyFont: {
                        size: isMobile ? 10 : 12
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            // Format lebih pendek untuk mobile
                            if (isMobile) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'k';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        },
                        font: {
                            size: isMobile ? 9 : 12
                        },
                        padding: isMobile ? 5 : 10
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: isMobile ? 7 : 10
                        },
                        maxRotation: isMobile ? 90 : 60,
                        minRotation: isMobile ? 90 : 60,
                        autoSkip: false, // Tampilkan semua tanggal
                        maxTicksLimit: 30, // Maksimal 30 ticks
                        padding: isMobile ? 2 : 5,
                        callback: function(value, index) {
                            // Tampilkan semua label tanggal
                            if (labels[index]) {
                                return labels[index];
                            }
                            return '';
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    };
    
    try {
        const chart = new Chart(ctx, chartConfig);
        console.log('Chart created successfully');
        
        // Handle resize untuk responsive
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Chart.js akan otomatis resize jika responsive: true
                chart.resize();
            }, 250);
        });
    } catch (error) {
        console.error('Error creating chart:', error);
    }
}

// Jalankan saat DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChart);
} else {
    // DOM sudah ready
    initChart();
}
</script>
@endpush
@endif

<!-- Services Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center section-title">Layanan Kami</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card card p-4 text-center">
                    <div class="service-icon">
                        <span>💎</span>
                    </div>
                    <h4 class="fw-bold mb-3">Tabungan Emas</h4>
                    <p class="text-muted">Mulai tabungan emas Anda dengan mudah dan aman. Investasi jangka panjang untuk masa depan yang lebih baik.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card card p-4 text-center">
                    <div class="service-icon">
                        <span>📊</span>
                    </div>
                    <h4 class="fw-bold mb-3">Harga Real-time</h4>
                    <p class="text-muted">Pantau harga emas terkini setiap saat. Informasi harga yang akurat dan terupdate untuk keputusan investasi terbaik.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card card p-4 text-center">
                    <div class="service-icon">
                        <span>⚡</span>
                    </div>
                    <h4 class="fw-bold mb-3">Transaksi Mudah</h4>
                    <p class="text-muted">Beli dan jual emas dengan proses yang cepat dan transparan. Tanpa ribet, investasi emas jadi lebih mudah.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">Mengapa Pilih Kami?</h2>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="mb-3">🔒</div>
                    <h5 class="fw-bold">Aman & Terpercaya</h5>
                    <p class="text-muted small mb-0">Data dan transaksi Anda terlindungi dengan sistem keamanan terbaik</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="mb-3">📱</div>
                    <h5 class="fw-bold">Akses Mudah</h5>
                    <p class="text-muted small mb-0">Akses kapan saja, di mana saja melalui smartphone atau komputer</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="mb-3">💼</div>
                    <h5 class="fw-bold">Transparan</h5>
                    <p class="text-muted small mb-0">Harga dan informasi transaksi yang jelas dan transparan</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="mb-3">🚀</div>
                    <h5 class="fw-bold">Proses Cepat</h5>
                    <p class="text-muted small mb-0">Transaksi cepat dan mudah tanpa proses yang rumit</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
@if($events->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center section-title">📅 Event & Promo</h2>
        <div class="row g-4">
            @foreach($events as $event)
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
        <div class="text-center mt-4">
            <a href="{{ route('events.index') }}" class="btn btn-outline-primary">Lihat Semua Event →</a>
        </div>
    </div>
</section>
@endif

<!-- Articles Section -->
@if($articles->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">📰 Artikel & Berita</h2>
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
                        <div class="d-flex justify-content-between align-items-center mt-auto mb-3">
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
                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-primary">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">Lihat Semua Artikel →</a>
        </div>
    </div>
</section>
@endif

@guest
<!-- CTA Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="gold-card p-5">
                    <h3 class="mb-3">Siap Memulai Investasi Emas?</h3>
                    <p class="mb-4">Daftar sekarang dan dapatkan akses penuh ke semua fitur tabungan emas kami</p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">Daftar Gratis</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Sudah Punya Akun? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endguest
@endsection
