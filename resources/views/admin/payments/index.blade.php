@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Pembayaran</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Order</th>
                            <th>Tgl. Pembayaran</th>
                            <th>Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $payment->order_id) }}">#{{ $payment->order->id }}</a></td>
                                <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                                <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'completed' => 'success',
                                            'failed' => 'danger',
                                            'refunded' => 'info',
                                        ][$payment->status] ?? 'light';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
