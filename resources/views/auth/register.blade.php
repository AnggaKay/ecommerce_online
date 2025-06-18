@extends('layouts.app')

@section('content')
<div class="auth-page py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Daftar Akun</h2>
                            <p class="text-muted">Buat akun untuk mulai berbelanja</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="auth-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input id="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                                            placeholder="Nama lengkap Anda">
                                        
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
                                            name="phone" value="{{ old('phone') }}" required autocomplete="phone" 
                                            placeholder="08xxxxxxxxxx">
                                        
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
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="nama@example.com">
                                    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                            name="password" required autocomplete="new-password"
                                            placeholder="••••••••">
                                        
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input id="password-confirm" type="password" class="form-control border-start-0" 
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Saya menyetujui <a href="#" class="text-primary">Syarat & Ketentuan</a> dan <a href="#" class="text-primary">Kebijakan Privasi</a>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary py-2">
                                    <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none">Login</a></p>
                            </div>
                        </form>

                        <div class="social-login mt-4">
                            <div class="separator text-muted mb-3">
                                <span>Atau daftar dengan</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-secondary w-100">
                                    <i class="fab fa-google me-2"></i> Google
                                </a>
                                <a href="#" class="btn btn-outline-secondary w-100">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 