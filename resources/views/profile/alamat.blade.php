@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- BAGIAN HEADER HALAMAN --}}
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Alamat Saya</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile') }}" class="text-decoration-none">Profil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Alamat</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Sidebar Kiri (Identik dengan halaman profil) --}}
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

                    {{-- Menu Navigasi --}}
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-user-cog me-2"></i> Pengaturan Profil
                        </a>
                        <a href="{{ route('orders') }}" class="list-group-item list-group-item-action border-0">
                            <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                        </a>
                        {{-- Class 'active' dipindahkan ke link Alamat --}}
                        <a href="{{ route('alamat') }}" class="list-group-item list-group-item-action active border-0">
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

        {{-- Kolom Konten Kanan (Isi dengan manajemen alamat) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold mb-0">Daftar Alamat Tersimpan</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal" onclick="openAddModal()">
                            <i class="fas fa-plus me-1"></i> Tambah Alamat
                        </button>
                    </div>

                    @forelse ($addresses as $address)
                        <div class="card mb-3 {{ $address->is_default ? 'border-primary' : '' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="fw-bold">{{ $address->recipient_name }}
                                            @if ($address->is_default)
                                                <span class="badge bg-primary ms-2 fw-normal">Utama</span>
                                            @endif
                                        </h6>
                                        <p class="card-text small mb-1">{{ $address->phone_number }}</p>
                                        <p class="card-text small text-muted">
                                            {{ $address->address_line }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if (!$address->is_default)
                                            <li>
                                                <form action="{{ route('alamat.setDefault', $address) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Jadikan Utama</button>
                                                </form>
                                            </li>
                                            @endif
                                            <li><button class="dropdown-item" onclick="openEditModal({{ $address }})">Edit</button></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('alamat.destroy', $address) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus alamat ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-light rounded">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Anda belum memiliki alamat tersimpan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">Tambah Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressForm" action="{{ route('alamat.store') }}" method="POST">
                @csrf
                <div id="method-field"></div>
                <div class="modal-body">
                    {{-- Isi form di sini sama persis seperti kode sebelumnya --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="recipient_name" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address_line" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address_line" name="address_line" rows="2" required></textarea>
                    </div>
                    <div class="row">
                         <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Provinsi</label>
                            <input type="text" class="form-control" id="province" name="province" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Kota/Kabupaten</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                    </div>
                     <div class="mb-3">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <input type="text" class="form-control" id="notes" name="notes" placeholder="Contoh: Pagar warna hitam">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="is_default" name="is_default">
                        <label class="form-check-label" for="is_default">
                            Jadikan sebagai alamat utama
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Kode Javascript di sini sama persis seperti kode sebelumnya, tidak perlu diubah --}}
<script>
    const addressModal = new bootstrap.Modal(document.getElementById('addressModal'));
    const modalTitle = document.getElementById('addressModalLabel');
    const addressForm = document.getElementById('addressForm');
    const methodField = document.getElementById('method-field');

    function openAddModal() {
        addressForm.reset();
        addressForm.action = '{{ route('alamat.store') }}';
        methodField.innerHTML = '';
        modalTitle.textContent = 'Tambah Alamat Baru';
    }

    function openEditModal(address) {
        addressForm.reset();
        addressForm.action = `/alamat/${address.id}`;
        methodField.innerHTML = '@method('PUT')';
        modalTitle.textContent = 'Edit Alamat';

        document.getElementById('recipient_name').value = address.recipient_name;
        document.getElementById('phone_number').value = address.phone_number;
        document.getElementById('address_line').value = address.address_line;
        document.getElementById('province').value = address.province;
        document.getElementById('city').value = address.city;
        document.getElementById('postal_code').value = address.postal_code;
        document.getElementById('notes').value = address.notes || '';
        document.getElementById('is_default').checked = address.is_default;

        addressModal.show();
    }
</script>
@endpush
