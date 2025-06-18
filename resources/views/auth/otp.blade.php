@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">OTP Verification</h2>
                        <p class="text-muted">Please enter the 6-digit code sent to your email</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('otp.verify') }}" class="auth-form">
                        @csrf

                        <div class="mb-4">
                            <label for="otp" class="form-label">OTP Code</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input id="otp" type="text" 
                                    class="form-control border-start-0 @error('otp') is-invalid @enderror" 
                                    name="otp" value="{{ old('otp') }}" required 
                                    autocomplete="off" autofocus maxlength="6"
                                    placeholder="Enter 6-digit code">
                                
                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-check-circle me-2"></i> Verify OTP
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">
                                Didn't receive the code? 
                                <a href="{{ route('otp.resend') }}" class="text-decoration-none">
                                    Resend OTP
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 