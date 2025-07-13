@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Keranjang Belanja</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Item Keranjang ({{ $cart ? $cart->total_items : 0 }})</h5>

                    {{-- Cek apakah keranjang ada isinya --}}
                    @if($cart && $cart->cartItems->isNotEmpty())
                        @foreach($cart->cartItems as $item)
                            <div class="row mb-4 border-bottom pb-3">
                                <div class="col-md-2">
                                    @php
                                        $image = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first();
                                    @endphp
                                    @if($image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded" alt="{{ $item->product->name }}">
                                    @else
                                    <img src="https://via.placeholder.com/100x100?text=No+Image" class="img-fluid rounded" alt="{{ $item->product->name }}">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-bold">{{ $item->product->name }}</h6>
                                    <p class="text-muted small mb-1">Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form action="{{ route('cart.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h6 class="fw-bold">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Tampilkan ini jika keranjang kosong --}}
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h4>Keranjang Belanja Kosong</h4>
                            <p class="text-muted">Anda belum menambahkan produk apapun ke keranjang</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-shopping-bag me-2"></i> Belanja Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Ringkasan Belanja</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Harga ({{ $cart ? $cart->total_items : 0 }} item)</span>
                        <span>Rp {{ number_format($cart->subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon</span>
                        <span class="text-success">- Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim</span>
                        <span>(Dihitung di checkout)</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total Pembayaran</span>
                        <span class="text-primary">Rp {{ number_format($cart->subtotal ?? 0, 0, ',', '.') }}</span>
                    </div>

                    {{-- Tombol Checkout akan aktif jika ada item di keranjang --}}
                    <a href="{{ ($cart && $cart->cartItems->isNotEmpty()) ? route('checkout.index') : '#' }}"
   class="btn btn-primary w-100 py-2 {{ (!$cart || $cart->cartItems->isEmpty()) ? 'disabled' : '' }}">
    <i class="fas fa-credit-card me-2"></i> Lanjutkan ke Checkout
</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
