@extends('layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-light py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="container py-5">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="product-images">
                    <div class="main-image mb-3">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' .$product->images->where('is_primary', true)->first()->image_path ?? $product->images->first()->image_path) }}"
                                 class="img-fluid rounded shadow" alt="{{ $product->name }}" id="main-product-image">
                        @else
                            <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded shadow" alt="{{ $product->name }}">
                        @endif
                    </div>
                    @if($product->images->count() > 1)
                        <div class="thumbnail-images">
                            <div class="row g-2">
                                @foreach($product->images as $image)
                                    <div class="col-3">
                                        <img src="{{ asset('storage/' .$image->image_path) }}"
                                             class="img-thumbnail thumbnail-image"
                                             alt="{{ $product->name }}"
                                             onclick="document.getElementById('main-product-image').src='{{ asset('storage/' .$image->image_path) }}'">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <h1 class="fw-bold mb-2">{{ $product->name }}</h1>

                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex align-items-center me-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= round($product->getAverageRatingAttribute()) ? 'text-warning' : 'text-muted' }} me-1"></i>
                        @endfor
                        <span class="ms-2">{{ number_format($product->getAverageRatingAttribute(), 1) }}</span>
                    </div>
                    <span class="text-muted">|</span>
                    <span class="ms-3 text-muted">{{ $product->reviews->count() }} ulasan</span>
                </div>

                <div class="mb-4">
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="badge bg-secondary text-decoration-none">
                        {{ $product->category->name }}
                    </a>
                </div>

                <div class="mb-4">
                    @if($product->discount_price)
                        <p class="text-decoration-line-through text-muted mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <h2 class="fw-bold text-danger mb-0">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</h2>
                        <p class="text-success small">
                            <i class="fas fa-tag me-1"></i>
                            Hemat {{ number_format((($product->price - $product->discount_price) / $product->price) * 100, 0) }}%
                        </p>
                    @else
                        <h2 class="fw-bold mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
                    @endif
                </div>

                <div class="mb-4">
                    <h5 class="fw-bold">Deskripsi</h5>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>

                @if($product->stock > 0)
                    <div class="mb-4">
                        <p class="text-success mb-2"><i class="fas fa-check-circle me-2"></i> Stok tersedia ({{ $product->stock }})</p>

<form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <div class="input-group me-3" style="width: 140px;">
        <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">
            <i class="fas fa-minus"></i>
        </button>
        <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" aria-label="Quantity">
        <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <button type="submit" class="btn btn-primary flex-grow-1">
        <i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang
    </button>

</form>
                    </div>
                @else
                    <div class="mb-4">
                        <p class="text-danger mb-3"><i class="fas fa-times-circle me-2"></i> Stok habis</p>
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="fas fa-bell me-2"></i> Beri tahu saya ketika tersedia
                        </button>
                    </div>
                @endif

                <hr class="my-4">

                <div class="d-flex flex-wrap">
                    <button type="button" class="btn btn-outline-secondary me-3 mb-2">
                        <i class="far fa-heart me-2"></i> Tambah ke Wishlist
                    </button>
                    <button type="button" class="btn btn-outline-secondary mb-2">
                        <i class="fas fa-share-alt me-2"></i> Bagikan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="bg-light py-5">
        <div class="container">
            <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
                        Detail Produk
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">
                        Ulasan ({{ $product->reviews->count() }})
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="productTabContent">
                <!-- Details Tab -->
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3">Informasi Produk</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%">Kategori</td>
                                            <td>{{ $product->category->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Stok</td>
                                            <td>{{ $product->stock }}</td>
                                        </tr>
                                        <tr>
                                            <td>Berat</td>
                                            <td>{{ $product->weight ?? 'N/A' }} gram</td>
                                        </tr>
                                        @if($product->preparation_time)
                                        <tr>
                                            <td>Waktu Persiapan</td>
                                            <td>{{ $product->preparation_time }} menit</td>
                                        </tr>
                                        @endif
                                        @if($product->expiry_date)
                                        <tr>
                                            <td>Tanggal Kadaluarsa</td>
                                            <td>{{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>Perlu Refrigerasi</td>
                                            <td>{{ $product->requires_refrigeration ? 'Ya' : 'Tidak' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    @if($product->ingredients)
                                    <h5 class="fw-bold mb-3">Bahan-bahan</h5>
                                    <p class="text-muted">{{ $product->ingredients }}</p>
                                    @endif

                                    @if($product->nutritional_info)
                                    <h5 class="fw-bold mb-3 mt-4">Informasi Nutrisi</h5>
                                    <p class="text-muted">{{ $product->nutritional_info }}</p>
                                    @endif

                                    @if($product->allergen_info)
                                    <h5 class="fw-bold mb-3 mt-4">Informasi Alergen</h5>
                                    <p class="text-muted">{{ $product->allergen_info }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                                    <h2 class="display-4 fw-bold">{{ number_format($product->getAverageRatingAttribute(), 1) }}</h2>
                                    <div class="mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($product->getAverageRatingAttribute()) ? 'text-warning' : 'text-muted' }} me-1 fa-lg"></i>
                                        @endfor
                                    </div>
                                    <p class="text-muted">Berdasarkan {{ $product->reviews->count() }} ulasan</p>
                                </div>
                                <div class="col-lg-8">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-3" style="width: 40px;">{{ $i }} <i class="fas fa-star text-warning"></i></div>
                                            <div class="progress flex-grow-1" style="height: 10px;">
                                                @php
                                                    $percentage = $product->reviews->count() > 0
                                                        ? ($product->reviews->where('rating', $i)->count() / $product->reviews->count()) * 100
                                                        : 0;
                                                @endphp
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%"
                                                    aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="ms-3" style="width: 40px;">{{ $product->reviews->where('rating', $i)->count() }}</div>
                                        </div>
                                    @endfor
                                    <div class="mt-4">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                            <i class="fas fa-edit me-2"></i> Tulis Ulasan
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Review List -->
                            @if($product->reviews->count() > 0)
                                <div class="review-list mt-4">
                                    @foreach($product->reviews as $review)
                                        <div class="review-item mb-4">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="avatar me-3">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($review->user->name ?? 'Anonymous', 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                                            @endfor
                                                        </div>
                                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mb-0 ms-5 ps-2">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                                    <h5>Belum ada ulasan</h5>
                                    <p class="text-muted">Jadilah yang pertama memberikan ulasan untuk produk ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="container py-5">
            <h3 class="fw-bold mb-4">Produk Terkait</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-6">
                        <div class="card h-100">
                            <div class="position-relative">
                                @if($relatedProduct->images->count() > 0)
                                    <img src="{{ asset('storage/' .$relatedProduct->images->where('is_primary', true)->first()->image_path ?? $relatedProduct->images->first()->image_path) }}"
                                         class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $relatedProduct->name }}">
                                @endif

                                @if($relatedProduct->discount_price)
                                    <div class="position-absolute top-0 end-0 bg-danger text-white m-2 px-2 py-1 rounded-pill">
                                        <small>SALE</small>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($relatedProduct->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        @if($relatedProduct->discount_price)
                                            <p class="text-decoration-line-through text-muted mb-0"><small>Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</small></p>
                                            <p class="fw-bold text-danger mb-0">Rp {{ number_format($relatedProduct->discount_price, 0, ',', '.') }}</p>
                                        @else
                                            <p class="fw-bold mb-0">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        <span>{{ number_format($relatedProduct->getAverageRatingAttribute(), 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-secondary flex-grow-1">Detail</a>
                                    <button type="button" class="btn btn-sm btn-primary flex-grow-1">
                                        <i class="fas fa-cart-plus me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Tulis Ulasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="mb-3 text-center">
                            <p class="mb-2">Rating</p>
                            <div class="rating-stars">
                                <div class="d-flex justify-content-center">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="visually-hidden">
                                        <label for="star{{ $i }}" class="star-label">
                                            <i class="far fa-star fa-lg mx-1 star-icon"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Quantity increment/decrement
        function incrementQuantity() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.getAttribute('max'));
            let value = parseInt(input.value);
            if (value < max) {
                input.value = value + 1;
            }
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
            }
        }

        // Star rating
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-label');

            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const starValue = this.getAttribute('for').replace('star', '');
                    highlightStars(starValue);
                });

                star.addEventListener('click', function() {
                    const starValue = this.getAttribute('for').replace('star', '');
                    document.getElementById('star' + starValue).checked = true;
                    highlightStars(starValue);
                });
            });

            const ratingContainer = document.querySelector('.rating-stars');
            ratingContainer.addEventListener('mouseout', function() {
                const checkedStar = document.querySelector('input[name="rating"]:checked');
                if (checkedStar) {
                    highlightStars(checkedStar.value);
                } else {
                    resetStars();
                }
            });

            function highlightStars(value) {
                resetStars();
                for (let i = value; i >= 1; i--) {
                    const starIcon = document.querySelector(`label[for="star${i}"] i`);
                    starIcon.classList.remove('far');
                    starIcon.classList.add('fas');
                    starIcon.classList.add('text-warning');
                }
            }

            function resetStars() {
                stars.forEach(star => {
                    const icon = star.querySelector('i');
                    icon.classList.remove('fas', 'text-warning');
                    icon.classList.add('far');
                });
            }
        });
    </script>
@endsection
