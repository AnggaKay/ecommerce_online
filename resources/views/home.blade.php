@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="overlay"></div>
        <div class="position-absolute w-100 h-100" style="z-index: -1;">
            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Food Background" class="w-100 h-100" style="object-fit: cover;">
        </div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-lg-7" data-aos="fade-right" data-aos-delay="200">
                    <h1 class="display-4 fw-bold text-white mb-4">Belanja Makanan Segar & Lezat</h1>
                    <p class="lead text-white mb-4">Antar langsung ke rumah Anda dengan kualitas terbaik. Nikmati kemudahan berbelanja makanan online.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="/products" class="btn btn-primary btn-lg px-4 py-2">Belanja Sekarang</a>
                        {{-- <a href="/categories" class="btn btn-outline-light btn-lg px-4 py-2">Lihat Kategori</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="fas fa-truck text-white"></i>
                        </div>
                        <h4>Pengiriman Cepat</h4>
                        <p class="text-muted">Makanan segar diantar langsung ke rumah Anda dalam waktu singkat</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="fas fa-leaf text-white"></i>
                        </div>
                        <h4>Kualitas Premium</h4>
                        <p class="text-muted">Hanya bahan makanan berkualitas terbaik yang kami sediakan</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <h4>Pembayaran Aman</h4>
                        <p class="text-muted">Transaksi aman dengan berbagai metode pembayaran</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold">Kategori Makanan</h2>
                    <p class="text-muted">Temukan berbagai pilihan makanan lezat sesuai kategori favorit Anda.</p>
                </div>
                <div class="col-lg-6 text-lg-end" data-aos="fade-left">
                    <a href="/categories" class="btn btn-outline-primary">Lihat Semua Kategori</a>
                </div>
            </div>

            <div class="row g-4">
                @if(count($categories) > 0)
                    @foreach($categories as $category)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="category-card">
                                <img src="{{ $category->image ? asset($category->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80' }}" class="w-100" alt="{{ $category->name }}">
                                <div class="category-overlay">
                                    <h5 class="mb-2">{{ $category->name }}</h5>
                                    <a href="/categories/{{ $category->slug }}" class="btn btn-sm btn-light">Lihat Produk</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-5" data-aos="fade-up">
                        <div class="py-5">
                            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                            <h3 class="h5">Belum ada kategori</h3>
                            <p class="text-muted">Kategori makanan akan segera tersedia.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold">Produk Unggulan</h2>
                    <p class="text-muted">Pilihan makanan terbaik dan terpopuler dari kami.</p>
                </div>
                <div class="col-lg-6 text-lg-end" data-aos="fade-left">
                    <a href="/products" class="btn btn-outline-primary">Lihat Semua Produk</a>
                </div>
            </div>

            <div class="row g-4">
                @if(count($featuredProducts) > 0)
                    @foreach($featuredProducts as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="card product-card">
                                <div class="position-relative">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . ($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path)) }}" class="card-img-top" alt="{{ $product->name }}">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80" class="card-img-top" alt="{{ $product->name }}">
                                    @endif

                                    @if($product->discount_price)
                                        <div class="position-absolute top-0 end-0 bg-primary text-white m-2 px-3 py-1 rounded-pill">
                                            <small>SALE</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            @if($product->discount_price)
                                                <p class="product-price-old mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                <p class="product-price mb-0">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                                            @else
                                                <p class="product-price mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-warning me-1"></i>
                                            <span>{{ number_format($product->getAverageRatingAttribute(), 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        <button type="button" class="btn btn-sm btn-primary flex-grow-1">
                                            <i class="fas fa-cart-plus me-1"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-5" data-aos="fade-up">
                        <div class="py-5">
                            <i class="fas fa-shopping-basket fa-3x text-muted mb-3"></i>
                            <h3 class="h5">Belum ada produk unggulan</h3>
                            <p class="text-muted">Produk unggulan akan segera tersedia.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row mb-5 text-center">
                <div class="col-lg-6 mx-auto" data-aos="fade-up">
                    <h2 class="fw-bold">Apa Kata Pelanggan</h2>
                    <p class="text-muted">Pengalaman berbelanja dari pelanggan setia kami.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card testimonial-card">
                        <div class="d-flex mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-warning me-1"></i>
                            @endfor
                        </div>
                        <p class="card-text mb-4">"Makanan yang saya pesan selalu segar dan lezat. Pengiriman juga cepat dan tepat waktu. Sangat merekomendasikan layanan ini!"</p>
                        <div class="d-flex align-items-center mt-auto">
                            <div class="testimonial-avatar me-3">
                                <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Customer" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0">Siti Rahayu</h6>
                                <small class="text-muted">Jakarta</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card testimonial-card">
                        <div class="d-flex mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-warning me-1"></i>
                            @endfor
                        </div>
                        <p class="card-text mb-4">"Kualitas makanan sangat terjamin dan pilihan produknya lengkap. Proses pemesanan mudah dan customer service sangat membantu."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <div class="testimonial-avatar me-3">
                                <img src="https://randomuser.me/api/portraits/men/44.jpg" alt="Customer" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0">Budi Santoso</h6>
                                <small class="text-muted">Bandung</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card testimonial-card">
                        <div class="d-flex mb-3">
                            @for($i = 0; $i < 4; $i++)
                                <i class="fas fa-star text-warning me-1"></i>
                            @endfor
                            <i class="fas fa-star text-muted"></i>
                        </div>
                        <p class="card-text mb-4">"Saya sangat puas dengan layanan Food E-commerce. Makanan selalu datang dalam kondisi baik dan sesuai dengan deskripsi produk di website."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <div class="testimonial-avatar me-3">
                                <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Customer" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0">Dewi Lestari</h6>
                                <small class="text-muted">Surabaya</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="fw-bold">Siap untuk menikmati makanan lezat?</h2>
                    <p class="lead mb-0">Daftar sekarang dan dapatkan diskon 10% untuk pemesanan pertama Anda.</p>
                </div>
                <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                    <a href="/register" class="btn btn-light btn-lg">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection
