@extends('layouts.app')

@section('content')
    <div class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="fw-bold">Hubungi Kami</h1>
                    <p class="text-muted">Kami siap mendengar dari Anda. Kirimkan pertanyaan atau masukan Anda melalui form di bawah ini.</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kontak</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 text-lg-end mt-3 mt-lg-0" data-aos="fade-left">
    <img src="https://img.freepik.com/free-vector/flat-design-illustration-customer-support_23-2148887720.jpg"
         class="img-fluid rounded w-75 h-50 float-end"
         alt="Hubungi Kami">
</div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Informasi Kontak</h4>
                        <p class="text-muted mb-4">Jangan ragu untuk menghubungi kami melalui detail di bawah ini selama jam kerja.</p>

                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-primary fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">Alamat</h5>
                                <p class="mb-0 text-muted">Jl. Contoh No. 123, Kota Simulasi, 45678, Indonesia</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0">
                                <i class="fas fa-phone-alt text-primary fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">Telepon</h5>
                                <p class="mb-0 text-muted">(+62) 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-envelope text-primary fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="fw-bold mb-1">Email</h5>
                                <p class="mb-0 text-muted">kontak@namaperusahaan.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
<div class="col-lg-7" data-aos="fade-left">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Kirim Pesan</h4>

            <!-- Notifikasi Sukses atau Error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Nama Anda" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="email@anda.com" value="{{ old('email') }}" required>
                         @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="subject" class="form-label">Subjek</label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" placeholder="Subjek pesan Anda" value="{{ old('subject') }}" required>
                     @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Pesan</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Tuliskan pesan Anda di sini..." required>{{ old('message') }}</textarea>
                     @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</div>
        </div>

        <div class="row mt-5" data-aos="fade-up">
            <div class="col-12">
                 <div class="card border-0 shadow-sm">
                    <div class="card-body p-2">
                        <div class="ratio ratio-16x9">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127107.9413374825!2d105.2104449972656!3d-5.404224099999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da46e1b782b5%3A0x242337bfa5463121!2sBandar%20Lampung%2C%20Kota%20Bandar%20Lampung%2C%20Lampung!5e0!3m2!1sid!2sid!4v1689758957821!5m2!1sid!2sid"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
