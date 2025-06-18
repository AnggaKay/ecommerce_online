@extends('layouts.app')

@section('content')
<div class="auth-page py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Reset Password</h2>
                            <p class="text-muted">Masukkan password baru Anda</p>
                        </div>

                        <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                                    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                        name="password" required autocomplete="new-password" placeholder="••••••••">
                                    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password-confirm" type="password" class="form-control border-start-0" 
                                        name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary py-2">
                                    <i class="fas fa-key me-2"></i> Reset Password
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Kembali ke <a href="{{ route('login') }}" class="text-primary text-decoration-none">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 