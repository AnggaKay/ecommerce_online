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

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Item Keranjang</h5>
                    
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h4>Keranjang Belanja Kosong</h4>
                        <p class="text-muted">Anda belum menambahkan produk apapun ke keranjang</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-shopping-bag me-2"></i> Belanja Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Ringkasan Belanja</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Harga (0 item)</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim</span>
                        <span>Rp 0</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total Pembayaran</span>
                        <span class="text-primary">Rp 0</span>
                    </div>
                    
                    <button class="btn btn-primary w-100 py-2" disabled>
                        <i class="fas fa-credit-card me-2"></i> Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 