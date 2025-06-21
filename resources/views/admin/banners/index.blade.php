@extends('layouts.admin')

@section('title', 'Kelola Banner')

@section('page-title', 'Kelola Banner')

@section('content')
<div class="card card-admin">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Banner</h5>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Banner
        </a>
    </div>
    <div class="card-body">
        @if($banners->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                <p class="mb-1">Belum ada banner yang ditambahkan</p>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-primary mt-3">Tambah Banner Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Gambar</th>
                            <th>Judul</th>
                            <th>Posisi</th>
                            <th>Periode</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="banner-list">
                        @foreach($banners as $banner)
                            <tr data-id="{{ $banner->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="img-thumbnail" style="max-height: 60px;">
                                </td>
                                <td>
                                    <strong>{{ $banner->title }}</strong>
                                    @if($banner->subtitle)
                                        <br><small class="text-muted">{{ $banner->subtitle }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->position == 'home_slider')
                                        <span class="badge bg-primary">Home Slider</span>
                                    @elseif($banner->position == 'home_banner')
                                        <span class="badge bg-success">Home Banner</span>
                                    @elseif($banner->position == 'category_banner')
                                        <span class="badge bg-info">Category Banner</span>
                                    @elseif($banner->position == 'sidebar_banner')
                                        <span class="badge bg-warning">Sidebar Banner</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $banner->position }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->start_date && $banner->end_date)
                                        {{ $banner->start_date->format('d M Y') }} - {{ $banner->end_date->format('d M Y') }}
                                    @elseif($banner->start_date)
                                        Dari {{ $banner->start_date->format('d M Y') }}
                                    @elseif($banner->end_date)
                                        Sampai {{ $banner->end_date->format('d M Y') }}
                                    @else
                                        <span class="text-muted">Tidak dibatasi</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.banners.toggle-active', $banner) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bannerList = document.getElementById('banner-list');
        
        if (bannerList) {
            new Sortable(bannerList, {
                animation: 150,
                ghostClass: 'bg-light',
                onEnd: function() {
                    const rows = bannerList.querySelectorAll('tr');
                    const orders = Array.from(rows).map(row => row.dataset.id);
                    
                    fetch('{{ route('admin.banners.update-order') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ orders: orders })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update row numbers
                            rows.forEach((row, index) => {
                                row.cells[0].textContent = index + 1;
                            });
                        }
                    });
                }
            });
        }
    });
</script>
@endpush 