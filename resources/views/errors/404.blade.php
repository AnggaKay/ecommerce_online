{{-- resources/views/errors/404.blade.php --}}

@extends('layouts.app') {{-- Gunakan layout utama Anda --}}

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 fw-bold">404</h1>
    <p class="h4 text-muted">Halaman Tidak Ditemukan</p>
    <p class="lead text-muted mb-4">
        Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.
    </p>
    <a href="/" class="btn btn-primary">Kembali ke Halaman Utama</a>
</div>
@endsection
