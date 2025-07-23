@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="card card-admin">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Pesanan</h5>
    </div>
    <div class="card-body">
        <!-- Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-md-10">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                    <div class="input-group flex-grow-1">
                        <input type="text" class="form-control" placeholder="Cari invoice atau nama pelanggan..." name="search" value="{{ request('search') }}">
                    </div>

                    {{-- FIX: Opsi filter status disesuaikan --}}
                    <select name="status" class="form-select" style="width: 200px;">
                        <option value="">Semua Status</option>
                        <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="menunggu_validasi" {{ request('status') == 'menunggu_validasi' ? 'selected' : '' }}>Menunggu Validasi</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="mb-1">Tidak ada pesanan yang cocok dengan filter Anda.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-admin table-hover">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="fw-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                        #{{ $order->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                {{-- FIX: Menggunakan kolom 'total' bukan 'total_amount' --}}
                                <td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    {{-- FIX: Tampilan status disesuaikan --}}
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
                                    <span class="badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
