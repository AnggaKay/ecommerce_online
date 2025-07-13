<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SUAK Market') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="fas fa-shop me-2"></i>
                SUAK Market
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="/products">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about*') ? 'active' : '' }}" href="/about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="/contact">Kontak</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link cart-badge" href="{{ route('cart') }}">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                             @if(isset($cartItemCount) && $cartItemCount > 0)
            <span class="badge rounded-pill bg-primary position-absolute top-0 start-100 translate-middle">
                {{ $cartItemCount }}
                <span class="visually-hidden">items in cart</span>
            </span>
        @endif
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="fas fa-user me-1"></i> Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profil Saya
                                </a>
                                <a class="dropdown-item" href="{{ route('orders') }}">
                                    <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Panel Admin
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4 d-flex align-items-center">
                        <i class="fas fa-utensils me-2"></i>
                        Food E-commerce
                    </h5>
                    <p class="text-active">Belanja makanan segar dan lezat secara online dengan mudah dan cepat. Kami mengirimkan kualitas terbaik langsung ke pintu Anda.</p>
                    <div class="mt-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="/"><i class="fas fa-angle-right me-2"></i>Home</a></li>
                        <li><a href="/products"><i class="fas fa-angle-right me-2"></i>Produk</a></li>
                        {{-- <li><a href="/categories"><i class="fas fa-angle-right me-2"></i>Kategori</a></li> --}}
                        <li><a href="/about"><i class="fas fa-angle-right me-2"></i>Tentang Kami</a></li>
                        <li><a href="/contact"><i class="fas fa-angle-right me-2"></i>Kontak</a></li>
                    </ul>
                </div>
                {{-- <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Kategori Populer</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Makanan Siap Saji</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Makanan Sehat</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Dessert</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Minuman</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Snack</a></li>
                    </ul>
                </div> --}}
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                                <span>Jl. Makanan Enak No. 123, Jakarta</span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-phone me-3 mt-1"></i>
                                <span>+62 123 4567 890</span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-envelope me-3 mt-1"></i>
                                <span>info@foodecommerce.com</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Politeknik Negeri Lampung. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>
