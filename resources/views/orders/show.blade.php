@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Detail Pesanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders') }}" class="text-decoration-none">Pesanan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Pesanan #{{ $order->id ?? '12345' }}</h5>
                        <span class="badge bg-success">Selesai</span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted">Tanggal Pesanan</h6>
                            <p class="mb-0">{{ $order->created_at ?? now()->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Metode Pembayaran</h6>
                            <p class="mb-0">Transfer Bank</p>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3" style="width: 60px; height: 60px;">
                                                <img src="https://via.placeholder.com/60" class="img-fluid rounded" alt="Product">
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Nasi Goreng Spesial</h6>
                                                <small class="text-muted">Pedas</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp 25.000</td>
                                    <td>2</td>
                                    <td class="text-end">Rp 50.000</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3" style="width: 60px; height: 60px;">
                                                <img src="https://via.placeholder.com/60" class="img-fluid rounded" alt="Product">
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Es Teh Manis</h6>
                                                <small class="text-muted">Large</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp 10.000</td>
                                    <td>2</td>
                                    <td class="text-end">Rp 20.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Informasi Pengiriman</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted">Alamat Pengiriman</h6>
                            <p class="mb-0">
                                <strong>John Doe</strong><br>
                                Jl. Makan Enak No. 123<br>
                                Kecamatan Kota, Jakarta<br>
                                12345<br>
                                +62 812 3456 7890
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Kurir</h6>
                            <p class="mb-1"><strong>GoSend</strong></p>
                            <p class="mb-0">Same Day Delivery</p>
                            <p class="mb-0">No. Resi: GS12345678</p>
                        </div>
                    </div>
                    
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-map-marker-alt me-2"></i> Lacak Pengiriman
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Ringkasan Pembayaran</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp 70.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon</span>
                        <span>- Rp 5.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ongkos Kirim</span>
                        <span>Rp 15.000</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total Pembayaran</span>
                        <span class="text-primary">Rp 80.000</span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-redo-alt me-2"></i> Pesan Lagi
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i> Unduh Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 