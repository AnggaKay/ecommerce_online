@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Profil Saya</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-wrapper mx-auto mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=FF6B35&color=fff&size=128" alt="{{ $user->name }}" class="rounded-circle img-fluid">
                        </div>
                        <h4 class="fw-bold">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action active border-0">
                            <i class="fas fa-user-cog me-2"></i> Pengaturan Profil
                        </a>
                        <a href="{{ route('orders') }}" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-heart me-2"></i> Wishlist
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-map-marker-alt me-2"></i> Alamat
                        </a>
                        <a href="#" class="list-group-item list-group-item-action border-0 text-danger" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Pengaturan Profil</h5>
                    
                    <form method="POST" action="{{ route('profile.update') }}" class="auth-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input id="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                        name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                                    
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-phone text-muted"></i>
                                    </span>
                                    <input id="phone" type="text" class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                        name="phone" value="{{ old('phone', $user->phone) }}" required autocomplete="phone">
                                    
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Ubah Password</h5>
                        <p class="text-muted small mb-4">Biarkan kosong jika tidak ingin mengubah password</p>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input id="current_password" type="password" class="form-control border-start-0 @error('current_password') is-invalid @enderror" 
                                    name="current_password" autocomplete="current-password">
                                
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                        name="password" autocomplete="new-password">
                                    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password-confirm" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control border-start-0" 
                                        name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 