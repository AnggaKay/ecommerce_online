@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Detail Produk: {{ $product->name }}</h1>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Produk
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Gambar Produk
                </div>
                <div class="card-body">
                    @if($product->images->isNotEmpty())
                        @php
                            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        @endphp
                        <img src="{{ asset('storage/' . $primaryImage->image_path) }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">

                        @if($product->images->count() > 1)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail" width="80" alt="Thumbnail">
                            @endforeach
                        </div>
                        @endif
                    @else
                        <p class="text-muted">Tidak ada gambar untuk produk ini.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header">
                    Informasi Produk
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Nama Produk</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $product->category->name }}</td>
                        </tr>
                         <tr>
                            <th>Harga</th>
                            <td>
                                @if($product->discount_price)
                                    <s class="text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</s>
                                    <strong class="text-danger ms-2">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</strong>
                                @else
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>{{ $product->stock }} unit</td>
                        </tr>
                        <tr>
                            <th>Berat</th>
                            <td>{{ $product->weight ?? '-' }} gram</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{!! nl2br(e($product->description)) !!}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                {!! $product->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>' !!}
                                {!! $product->is_featured ? '<span class="badge bg-info">Unggulan</span>' : '' !!}
                                {!! $product->requires_refrigeration ? '<span class="badge bg-primary">Perlu Pendingin</span>' : '' !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
