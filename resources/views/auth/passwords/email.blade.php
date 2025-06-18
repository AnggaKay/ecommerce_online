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
                            <p class="text-muted">Masukkan email Anda untuk menerima link reset password</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mb-4" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                                        placeholder="nama@example.com">
                                    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary py-2">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Link Reset Password
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