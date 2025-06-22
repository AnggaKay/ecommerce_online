@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-4 text-gray-800">Detail Pembayaran</h1>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID Transaksi Gateway</th>
                            <td>{{ $payment->payment_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>ID Order Terkait</th>
                            <td><a href="{{ route('admin.orders.show', $payment->order_id) }}">#{{ $payment->order->id }}</a></td>
                        </tr>
                        <tr>
                            <th>Pelanggan</th>
                            <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td class="font-weight-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Status Saat Ini</th>
                            <td>
                                @php
                                    $statusClass = ['pending' => 'warning', 'completed' => 'success', 'failed' => 'danger', 'refunded' => 'info'][$payment->status] ?? 'light';
                                @endphp
                                <span class="badge bg-{{ $statusClass }} fs-6">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>{{ $payment->created_at->format('d F Y, H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibayar</th>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d F Y, H:i:s') : 'Belum dibayar' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.updateStatus', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Ubah status pembayaran menjadi:</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $payment->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                    <hr>
                    @if($payment->status === 'failed')
                        <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data pembayaran yang gagal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Hapus Data Pembayaran</button>
                        </form>
                    @endif
                </div>
            </div>
             <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Raw Payload dari Gateway</h6>
                </div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto; background-color: #f8f9fa;">
                    <pre><code class="language-json">{{ json_encode($payment->payment_details, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
