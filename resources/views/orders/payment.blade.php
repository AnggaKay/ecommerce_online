@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@push('styles')
<style>
    .payment-instruction-card { border-left: 4px solid #0d6efd; }
    .bank-logo { max-height: 25px; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="text-center mb-4">
                <h1 class="fw-bold">Selesaikan Pembayaran Anda</h1>
                <p class="text-muted">Pesanan Anda telah dibuat. Segera lakukan pembayaran agar pesanan dapat diproses.</p>
            </div>

            <!-- Detail Pesanan -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Invoice #{{ $order->invoice_number }}</h5>
                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold fs-5">Total Tagihan</span>
                        <span class="fw-bold fs-5 text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-muted small mt-2 mb-0">Pastikan Anda mentransfer sesuai dengan jumlah yang tertera hingga 3 digit terakhir.</p>
                </div>
            </div>

            <!-- Instruksi Pembayaran -->
            <div class="card shadow-sm mb-4 payment-instruction-card">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3">Instruksi Transfer Bank</h5>
                    <p>Silakan transfer ke salah satu rekening berikut:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="bank-logo me-2">
                                <span class="fw-bold">Bank Central Asia (BCA)</span>
                            </div>
                            <div>
                                <span class="me-2">123-456-7890</span>
                                <span class="text-muted">(a.n. SUAK Market)</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                             <div>
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" class="bank-logo me-2">
                                <span class="fw-bold">Bank Mandiri</span>
                            </div>
                            <div>
                                <span class="me-2">098-765-4321</span>
                                <span class="text-muted">(a.n. SUAK Market)</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Form Upload Bukti -->
            <div class="card shadow-sm">
    <div class="card-body p-4">
        <h5 class="card-title fw-bold mb-3">Unggah Bukti Pembayaran</h5>

        {{-- PERUBAHAN DI BARIS INI: 'order.submit_payment_proof' menjadi 'orders.submit_payment_proof' --}}
        <form action="{{ route('orders.submit_payment_proof') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="mb-3">
                <label for="payment_proof" class="form-label">Pilih file gambar (JPG, PNG)</label>
                <input class="form-control @error('payment_proof') is-invalid @enderror" type="file" id="payment_proof" name="payment_proof" required>
                @error('payment_proof')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="fas fa-upload me-2"></i>Konfirmasi Pembayaran
            </button>
        </form>

    </div>
</div>

        </div>
    </div>
</div>
@endsection
