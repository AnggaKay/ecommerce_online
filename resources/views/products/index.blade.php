@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="fw-bold">Produk Kami</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Produk</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 text-lg-end mt-3 mt-lg-0" data-aos="fade-left">
                    <p class="text-muted mb-0">Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold">Kategori</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <a href="{{ route('products.index') }}" class="text-decoration-none d-flex align-items-center {{ !request('category') ? 'fw-bold text-primary' : 'text-dark' }}">
                                    <i class="fas fa-th-large me-2"></i> Semua Kategori
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li class="list-group-item border-0 px-0">
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                                       class="text-decoration-none d-flex align-items-center {{ request('category') == $category->id ? 'fw-bold text-primary' : 'text-dark' }}">
                                        <i class="fas fa-angle-right me-2"></i> {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold">Filter Harga</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="mb-3">
                                <label for="min_price" class="form-label">Harga Minimum</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="min_price" name="min_price" 
                                           value="{{ request('min_price') }}" placeholder="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="max_price" class="form-label">Harga Maksimum</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="max_price" name="max_price" 
                                           value="{{ request('max_price') }}" placeholder="1000000">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i> Terapkan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 fw-bold">Rating</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @for($i = 5; $i >= 1; $i--)
                                <a href="{{ route('products.index', array_merge(request()->except('rating'), ['rating' => $i])) }}" 
                                   class="list-group-item border-0 px-0 d-flex align-items-center {{ request('rating') == $i ? 'fw-bold text-primary' : 'text-dark' }}">
                                    @for($j = 1; $j <= 5; $j++)
                                        <i class="fas fa-star {{ $j <= $i ? 'text-warning' : 'text-muted' }} me-1"></i>
                                    @endfor
                                    <span class="ms-2">{{ $i }}+ bintang</span>
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9" data-aos="fade-up">
                <!-- Sort Options -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0 fw-bold">Daftar Produk</h4>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sort me-1"></i>
                            {{ request('sort') ? ucfirst(str_replace('_', ' ', request('sort'))) : 'Urutkan' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item" href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}">
                                <i class="fas fa-calendar-alt me-2"></i> Terbaru
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}">
                                <i class="fas fa-sort-amount-down-alt me-2"></i> Harga: Rendah ke Tinggi
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}">
                                <i class="fas fa-sort-amount-up me-2"></i> Harga: Tinggi ke Rendah
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'popularity'])) }}">
                                <i class="fas fa-fire me-2"></i> Popularitas
                            </a></li>
                        </ul>
                    </div>
                </div>

                <!-- Products -->
                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration % 3 * 100 }}">
                            <div class="card product-card">
                                <div class="position-relative">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset($product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path) }}" 
                                             class="card-img-top" alt="{{ $product->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}">
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
                    @empty
                        <div class="col-12 text-center py-5" data-aos="fade-up">
                            <div class="py-5">
                                <i class="fas fa-shopping-basket fa-3x text-muted mb-3"></i>
                                <h3 class="h5">Tidak ada produk yang ditemukan</h3>
                                <p class="text-muted">Coba ubah filter pencarian Anda.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center" data-aos="fade-up">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection 