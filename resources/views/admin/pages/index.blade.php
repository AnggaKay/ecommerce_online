@extends('layouts.admin')

@section('title', 'Kelola Halaman')

@section('page-title', 'Kelola Halaman')

@section('content')
<div class="card card-admin">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Halaman</h5>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Halaman
        </a>
    </div>
    <div class="card-body">
        @if($pages->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="mb-1">Belum ada halaman yang ditambahkan</p>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-sm btn-primary mt-3">Tambah Halaman Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Judul</th>
                            <th>Slug</th>
                            <th>Tanggal Dibuat</th>
                            <th width="100">Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $page->title }}</strong>
                                </td>
                                <td>{{ $page->slug }}</td>
                                <td>{{ $page->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.pages.toggle-active', $page) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $page->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $page->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?')">
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