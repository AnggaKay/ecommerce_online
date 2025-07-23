@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Pesanan Saya</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile') }}" class="text-decoration-none">Profil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Riwayat Pesanan</h5>

                    @if($orders->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Invoice</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-end">Total</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="fw-medium">#{{ $order->invoice_number }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>
                                                @php
                                                    $statusClass = '';
                                                    switch ($order->status) {
                                                        case 'menunggu_pembayaran':
                                                            $statusClass = 'bg-warning text-dark';
                                                            break;
                                                        case 'menunggu_validasi':
                                                            $statusClass = 'bg-info text-dark';
                                                            break;
                                                        case 'diproses':
                                                            $statusClass = 'bg-primary';
                                                            break;
                                                        case 'dikirim':
                                                            $statusClass = 'bg-secondary';
                                                            break;
                                                        case 'selesai':
                                                            $statusClass = 'bg-success';
                                                            break;
                                                        case 'dibatalkan':
                                                            $statusClass = 'bg-danger';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-light text-dark';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                                            </td>
                                            <td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>

                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                            <h4 class="fw-bold">Anda Belum Memiliki Pesanan</h4>
                            <p class="text-muted">Semua pesanan yang Anda buat akan muncul di sini.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-store me-2"></i> Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
