@extends('layouts.app')

@section('content')
    <div class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="fw-bold">Tentang Kami</h1>
                    <p class="text-muted">Mengenal lebih dekat siapa kami, bagaimana kami memulai, dan apa yang membuat kami bersemangat.</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 text-lg-end mt-3 mt-lg-0" data-aos="fade-left">
                    <img src="https://img.freepik.com/free-vector/people-analyzing-growth-charts_23-2148866843.jpg" class="img-fluid rounded" style="max-height: 250px; width: auto;" alt="Tentang Kami">
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <img src="https://img.freepik.com/free-photo/diverse-business-people-meeting_53876-20427.jpg" class="img-fluid rounded shadow-sm" alt="Kisah Perusahaan">
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <h2 class="fw-bold mb-3">Kisah Perusahaan Kami</h2>
                <p class="text-muted">Didirikan pada tahun 2020 di Bandar Lampung, perusahaan kami lahir dari semangat untuk menyediakan produk berkualitas tinggi dengan pelayanan yang tak tertandingi. Kami memulai dari sebuah tim kecil dengan mimpi besar: untuk menjadi tujuan utama bagi para pelanggan yang mencari keunggulan dan inovasi.</p>
                <p class="text-muted">Seiring berjalannya waktu, kami terus bertumbuh, belajar, dan beradaptasi dengan perubahan pasar, namun satu hal yang tidak pernah berubah adalah komitmen kami terhadap kepuasan Anda.</p>
            </div>
        </div>
    </div>

    <div class="bg-light py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Misi & Visi Kami</h2>
                <p class="text-muted">Panduan yang mengarahkan setiap langkah kami.</p>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card text-center h-100 border-0 shadow-sm p-4">
                        <div class="card-body">
                            <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                            <h4 class="card-title fw-bold">Misi Kami</h4>
                            <p class="card-text text-muted">Menghadirkan produk inovatif dan layanan pelanggan yang luar biasa, serta menciptakan pengalaman berbelanja yang mudah, aman, dan menyenangkan bagi semua orang.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card text-center h-100 border-0 shadow-sm p-4">
                        <div class="card-body">
                            <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                            <h4 class="card-title fw-bold">Visi Kami</h4>
                            <p class="card-text text-muted">Menjadi perusahaan e-commerce terdepan dan paling terpercaya di Indonesia, yang dikenal karena integritas, kualitas, dan kontribusinya terhadap komunitas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold">Tim Profesional Kami</h2>
            <p class="text-muted">Orang-orang hebat di balik kesuksesan kami.</p>
        </div>
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/200x200.png?text=CEO" class="card-img-top" alt="CEO">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Ahmad Pratama</h5>
                        <p class="card-text text-muted">Chief Executive Officer</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/200x200.png?text=CTO" class="card-img-top" alt="CTO">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Budi Santoso</h5>
                        <p class="card-text text-muted">Chief Technology Officer</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/200x200.png?text=COO" class="card-img-top" alt="COO">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Citra Lestari</h5>
                        <p class="card-text text-muted">Chief Operating Officer</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                 <div class="card border-0 shadow-sm h-100">
                    <img src="https://via.placeholder.com/200x200.png?text=CMO" class="card-img-top" alt="CMO">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Dewi Anggraini</h5>
                        <p class="card-text text-muted">Chief Marketing Officer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-primary text-white">
        <div class="container py-5 text-center" data-aos="zoom-in">
            <h2 class="fw-bold">Siap Menjelajahi Produk Kami?</h2>
            <p class="lead mb-4">Temukan produk-produk berkualitas yang telah kami siapkan khusus untuk Anda.</p>
            <a href="products" class="btn btn-light btn-lg fw-bold">Lihat Semua Produk</a>
        </div>
    </div>

@endsection
