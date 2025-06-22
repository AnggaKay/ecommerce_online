@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stats -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-admin h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="stat-card-icon bg-primary-light text-primary p-3 rounded">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-1 text-gray">Total Pesanan</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_orders'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-admin h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="stat-card-icon bg-success-light text-success p-3 rounded">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-1 text-gray">Total Pendapatan</h6>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-admin h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="stat-card-icon bg-warning-light text-warning p-3 rounded">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-1 text-gray">Total Produk</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_products'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-admin h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="stat-card-icon bg-info-light text-info p-3 rounded">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-1 text-gray">Total Pengguna</h6>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_users'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-admin">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pendapatan Bulanan</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        6 Bulan Terakhir
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">6 Bulan Terakhir</a></li>
                        <li><a class="dropdown-item" href="#">1 Tahun Terakhir</a></li>
                        <li><a class="dropdown-item" href="#">Semua Waktu</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-xl-8 col-lg-7">
        <div class="card card-admin">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pesanan Terbaru</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-admin mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info">Diproses</span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-primary">Dikirim</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada pesanan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users and Top Products -->
    <div class="col-xl-4 col-lg-5">
        <!-- Recent Users -->
        <div class="card card-admin mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pengguna Baru</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($recentUsers as $user)
                        <li class="list-group-item px-4 py-3 d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span>{{ substr($user->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-0">
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                        </li>
                    @empty
                        <li class="list-group-item px-4 py-3 text-center">Belum ada pengguna baru</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Top Products -->
        <div class="card card-admin">
            <div class="card-header">
                <h5 class="mb-0">Produk Terlaris</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-admin mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $product->total_sold }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">Belum ada data penjualan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const revenueChart = new Chart(
            document.getElementById('revenueChart'),
            {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyRevenue['labels']) !!},
                    datasets: [{
                        label: 'Pendapatan',
                        data: {!! json_encode($monthlyRevenue['data']) !!},
                        backgroundColor: 'rgba(255, 107, 53, 0.2)',
                        borderColor: 'rgba(255, 107, 53, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(255, 107, 53, 1)',
                        pointBorderColor: '#fff',
                        pointRadius: 4,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            padding: 10,
                            titleColor: '#fff',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyColor: '#fff',
                            bodyFont: {
                                size: 14
                            },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            }
        );

        // Fade in cards
        const cards = document.querySelectorAll('.card-admin');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('fade-in');
            }, index * 100);
        });
    });
</script>
@endpush
