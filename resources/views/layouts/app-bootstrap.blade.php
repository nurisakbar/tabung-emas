<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Tabung Emas'))</title>

    <!-- Bootstrap 5 CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="overflow-x: hidden; max-width: 100vw;">
    <div class="min-vh-100 d-flex flex-column" style="max-width: 100%; overflow-x: hidden;">
        @include('layouts.navigation-bootstrap')

        <!-- Page Content -->
        <main class="flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer mt-auto">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-lg-3 mb-4 mb-md-0">
                        <h5>💰 Tabung Emas</h5>
                        <p class="mb-3">Platform tabungan emas terpercaya untuk investasi masa depan Anda. Investasi yang aman, mudah, dan transparan.</p>
                        
                        <!-- Social Media -->
                        <h6 class="mb-3" style="color: #f5d020; font-weight: 600;">Ikuti Kami</h6>
                        <div class="social-media-icons">
                            <a href="https://facebook.com/tabungemas" target="_blank" rel="noopener noreferrer" class="social-icon social-facebook" title="Facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="https://instagram.com/tabungemas" target="_blank" rel="noopener noreferrer" class="social-icon social-instagram" title="Instagram">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="https://twitter.com/tabungemas" target="_blank" rel="noopener noreferrer" class="social-icon social-twitter" title="Twitter">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="https://youtube.com/@tabungemas" target="_blank" rel="noopener noreferrer" class="social-icon social-youtube" title="YouTube">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                            <a href="https://linkedin.com/company/tabungemas" target="_blank" rel="noopener noreferrer" class="social-icon social-linkedin" title="LinkedIn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2 mb-4 mb-md-0">
                        <h5>Layanan</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('home') }}">Beranda</a></li>
                            @guest
                            <li class="mb-2"><a href="{{ route('register') }}">Daftar</a></li>
                            <li class="mb-2"><a href="{{ route('login') }}">Login</a></li>
                            @else
                            <li class="mb-2"><a href="{{ route('nasabah.dashboard') }}">Dashboard</a></li>
                            <li class="mb-2"><a href="{{ route('nasabah.gold-price') }}">Harga Emas</a></li>
                            <li class="mb-2"><a href="{{ route('nasabah.gold-saving') }}">Tabungan Emas</a></li>
                            @endguest
                        </ul>
                    </div>
                    <div class="col-md-3 col-lg-3 mb-4 mb-md-0">
                        <h5>Kontak</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">📧 Email: info@tabungemas.com</li>
                            <li class="mb-2">📱 Telp: 0800-1234-5678</li>
                            <li class="mb-2">📍 Alamat: Jakarta, Indonesia</li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-lg-4">
                        <h5>📱 Download Aplikasi</h5>
                        <p class="text-white small mb-3">Dapatkan aplikasi Tabung Emas di smartphone Anda</p>
                        <div class="app-store-links">
                            <a href="https://play.google.com/store/apps/details?id=com.tabungemas.app" target="_blank" rel="noopener noreferrer" class="app-store-link app-playstore" title="Download di Google Play Store">
                                <div class="app-store-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L16.81,15.12L14.54,12.85L16.81,10.81L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                                    </svg>
                                </div>
                                <div class="app-store-text">
                                    <small>Download di</small>
                                    <strong>Google Play</strong>
                                </div>
                            </a>
                            <a href="https://apps.apple.com/app/tabung-emas/id123456789" target="_blank" rel="noopener noreferrer" class="app-store-link app-appstore" title="Download di App Store">
                                <div class="app-store-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15-.38-2.35-.25-3.11z"/>
                                    </svg>
                                </div>
                                <div class="app-store-text">
                                    <small>Download di</small>
                                    <strong>App Store</strong>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="mb-0">&copy; {{ date('Y') }} Tabung Emas. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
</body>
</html>
