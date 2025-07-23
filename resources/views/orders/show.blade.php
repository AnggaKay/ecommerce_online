@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('styles')
<style>
    .summary-card { position: sticky; top: 20px; }
    .item-image { width: 60px; height: 60px; object-fit: cover; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Detail Pesanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $order->invoice_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Order Items Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold mb-0">Invoice #{{ $order->invoice_number }}</h5>
                        @php
                            $statusClass = '';
                            switch ($order->status) {
                                case 'menunggu_pembayaran': $statusClass = 'bg-warning text-dark'; break;
                                case 'menunggu_validasi': $statusClass = 'bg-info text-dark'; break;
                                case 'diproses': $statusClass = 'bg-primary'; break;
                                case 'dikirim': $statusClass = 'bg-secondary'; break;
                                case 'selesai': $statusClass = 'bg-success'; break;
                                case 'dibatalkan': $statusClass = 'bg-danger'; break;
                                default: $statusClass = 'bg-light text-dark';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }} fs-6">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                    </div>

                    <h6 class="mb-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->image_url ?? 'https://placehold.co/60x60/e2e8f0/e2e8f0' }}" class="img-fluid rounded item-image me-3" alt="{{ $item->product_name }}">
                                            <div>
                                                <h6 class="mb-1 fw-medium">{{ $item->product_name }}</h6>
                                                <small class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">x{{ $item->quantity }}</td>
                                    <td class="text-end fw-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Information Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Informasi Pengiriman</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Alamat Pengiriman</h6>
                            <p class="mb-0">
                                <strong>{{ $order->name }}</strong><br>
                                {{ $order->phone }}<br>
                                {{ $order->address }}
                            </p>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <h6 class="text-muted">Kurir</h6>
                            <p class="mb-0">
                                <strong>{{ strtoupper($order->shipping_courier) }}</strong> - {{ $order->shipping_service }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Payment Summary Card -->
            <div class="card border-0 shadow-sm summary-card">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Ringkasan Pembayaran</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span>
                        <span class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    @if($order->status == 'menunggu_pembayaran')
                        <a href="{{ route('orders.payment', $order->id) }}" class="btn btn-primary w-100">Lanjutkan Pembayaran</a>
                    @endif

                    @if($order->payment_proof)
                        <div class="mt-3">
                            <h6 class="text-muted mb-2">Bukti Pembayaran:</h6>
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
