<nav class="navbar navbar-expand-lg navbar-dark professional-navbar">
    <div class="container">
        <a class="navbar-brand professional-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="brand-icon me-2">💰</div>
            <div class="brand-text">
                <span class="brand-name">Tabung Emas</span>
                <small class="brand-tagline d-none d-md-inline">Investasi Masa Depan</small>
            </div>
        </a>
        
        <button class="navbar-toggler professional-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto professional-nav">
                <li class="nav-item">
                    <a class="nav-link professional-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Beranda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link professional-nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                        <span class="nav-icon">📰</span>
                        <span class="nav-text">Artikel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link professional-nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <span class="nav-icon">📅</span>
                        <span class="nav-text">Event</span>
                    </a>
                </li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <span class="nav-icon">📊</span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <span class="nav-icon">👥</span>
                                <span class="nav-text">Nasabah</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                                <span class="nav-icon">💳</span>
                                <span class="nav-text">Transaksi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('admin.installments.*') ? 'active' : '' }}" href="{{ route('admin.installments.index') }}">
                                <span class="nav-icon">📅</span>
                                <span class="nav-text">Cicil Emas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('admin.pledges.*') ? 'active' : '' }}" href="{{ route('admin.pledges.index') }}">
                                <span class="nav-icon">🏦</span>
                                <span class="nav-text">Gadai Emas</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('nasabah.dashboard') ? 'active' : '' }}" href="{{ route('nasabah.dashboard') }}">
                                <span class="nav-icon">📊</span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('nasabah.gold-saving') ? 'active' : '' }}" href="{{ route('nasabah.gold-saving') }}">
                                <span class="nav-icon">💎</span>
                                <span class="nav-text">Tabungan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('nasabah.installments.*') ? 'active' : '' }}" href="{{ route('nasabah.installments.index') }}">
                                <span class="nav-icon">📅</span>
                                <span class="nav-text">Cicil Emas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('nasabah.pledges.*') ? 'active' : '' }}" href="{{ route('nasabah.pledges.index') }}">
                                <span class="nav-icon">🏦</span>
                                <span class="nav-text">Gadai Emas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link professional-nav-link {{ request()->routeIs('nasabah.profile.*') ? 'active' : '' }}" href="{{ route('nasabah.profile.edit') }}">
                                <span class="nav-icon">👤</span>
                                <span class="nav-text">Profile</span>
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
            
            <ul class="navbar-nav professional-nav-right">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link professional-user-dropdown dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.preventDefault();">
                            <div class="user-avatar me-2">👤</div>
                            <div class="user-info d-none d-md-block">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <small class="user-role">{{ auth()->user()->isAdmin() ? 'Admin' : 'Nasabah' }}</small>
                            </div>
                            <span class="dropdown-arrow ms-2 d-none d-md-inline">▼</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end professional-dropdown shadow-lg" aria-labelledby="navbarDropdown">
                            <li class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2">👤</div>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @if(auth()->user()->isNasabah())
                            <li>
                                <a class="dropdown-item professional-dropdown-item" href="{{ route('nasabah.profile.edit') }}">
                                    <span class="dropdown-icon">📝</span>
                                    <span>Profile Saya</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item professional-dropdown-item" href="{{ route('nasabah.gold-saving') }}">
                                    <span class="dropdown-icon">💎</span>
                                    <span>Tabunganku</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item professional-dropdown-item" href="{{ route('nasabah.installments.index') }}">
                                    <span class="dropdown-icon">📅</span>
                                    <span>Cicil Emas</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item professional-dropdown-item" href="{{ route('nasabah.pledges.index') }}">
                                    <span class="dropdown-icon">🏦</span>
                                    <span>Gadai Emas</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item professional-dropdown-item logout-btn w-100 text-start">
                                        <span class="dropdown-icon">🚪</span>
                                        <span class="fw-bold">Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link professional-nav-link" href="{{ route('login') }}">
                            <span class="nav-icon">🔑</span>
                            <span class="nav-text">Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-gold professional-register-btn ms-2" href="{{ route('register') }}">
                            <span class="me-1">✨</span>
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
