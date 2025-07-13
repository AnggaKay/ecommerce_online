@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('page-title', 'Kelola Produk')

@section('content')
<div class="card card-admin">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Produk</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Produk
        </a>
    </div>
    <div class="card-body">
        <!-- Filter and Search -->
        <div class="row mb-4">
            <div class="col-md-10">
                <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari produk..." name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <select name="category" class="form-select w-auto">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="form-select w-auto">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <p class="mb-1">Belum ada produk yang ditambahkan</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary mt-3">Tambah Produk Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="80">Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($product->images->where('is_primary', true)->first())
                                        <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}"
                                            alt="{{ $product->name }}" class="img-thumbnail" width="50">
                                    @else
                                        <img src="https://via.placeholder.com/50" alt="No Image" class="img-thumbnail">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    @if($product->discount_price)
                                        <span class="text-decoration-line-through text-muted">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span><br>
                                        <span class="text-danger">
                                            Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <form action="{{ route('admin.products.toggle-active', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
