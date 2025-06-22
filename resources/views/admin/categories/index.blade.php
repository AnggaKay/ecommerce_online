@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('page-title', 'Kelola Kategori')

@section('content')
<div class="card card-admin">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Kategori</h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Kategori
        </a>
    </div>
    <div class="card-body">
        @if($categories->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <p class="mb-1">Belum ada kategori yang ditambahkan</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary mt-3">Tambah Kategori Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-admin table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="80">Gambar</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Produk</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" width="50">
                                    @else
                                        <img src="https://via.placeholder.com/50x50.png?text=N/A" alt="No Image" class="img-thumbnail">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    <form action="{{ route('admin.categories.toggle-active', $category) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm w-100 {{ $category->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            {{-- Tombol Hapus dengan Peningkatan UX --}}
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                {{ $category->products_count > 0 ? 'disabled' : '' }}
                                                title="{{ $category->products_count > 0 ? 'Kategori ini memiliki produk. Tidak dapat dihapus.' : 'Hapus Kategori' }}">
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

            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
