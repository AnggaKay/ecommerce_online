@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Pesanan Saya</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-wrapper mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF6B35&color=fff&size=128" alt="{{ Auth::user()->name }}" class="rounded-circle img-fluid">
                        </div>
                        <h4 class="fw-bold">{{ Auth::user()->name }}</h4>
                        <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-user-cog me-2"></i> Pengaturan Profil
                        </a>
                        <a href="{{ route('orders') }}" class="list-group-item list-group-item-action active border-0">
                            <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-heart me-2"></i> Wishlist
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-map-marker-alt me-2"></i> Alamat
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 text-danger" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Daftar Pesanan</h5>
                    
                    @if(count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id ?? '12345' }}</td>
                                            <td>{{ $order->created_at ?? now()->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge bg-success">Selesai</span>
                                            </td>
                                            <td>Rp {{ number_format($order->total ?? 150000, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id ?? 1) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                            <h4>Belum Ada Pesanan</h4>
                            <p class="text-muted">Anda belum memiliki riwayat pesanan</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-shopping-bag me-2"></i> Belanja Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 