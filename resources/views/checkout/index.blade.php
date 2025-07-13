@extends('layouts.app')

@section('title', 'Checkout')

{{-- Menambahkan CSS khusus untuk halaman ini --}}
@push('styles')
<style>
    .summary-card {
        position: sticky;
        top: 20px;
    }
    #destination-results {
        position: absolute;
        z-index: 1000;
        width: 95%;
        max-height: 250px;
        overflow-y: auto;
    }
    #destination-results .list-group-item {
        cursor: pointer;
    }
    #destination-results .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .shipping-option {
        cursor: pointer;
        border: 1px solid #ddd;
        transition: all 0.2s ease-in-out;
    }
    .shipping-option:hover {
        background-color: #f8f9fa;
        border-color: #0d6efd;
    }
    .shipping-option.selected {
        border-color: #0d6efd;
        border-width: 2px;
        background-color: #e7f1ff;
    }
</style>
@endpush


@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Checkout</h1>
        <p class="text-muted">Satu langkah lagi untuk menyelesaikan pesanan Anda.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row g-5">
            {{-- KOLOM KIRI: INFORMASI PENGIRIMAN --}}
            <div class="col-lg-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3">Alamat Pengiriman</h5>

                        {{-- Logika untuk memilih alamat yang sudah ada bisa ditambahkan di sini --}}

                        <div class="mb-3 position-relative">
                            <label for="destination-search" class="form-label">Cari Kota / Kecamatan Tujuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" id="destination-search" placeholder="Ketik minimal 3 huruf..." autocomplete="off" required>
                            </div>
                            <div id="destination-results-container" class="list-group mt-1"></div>
                            <input type="hidden" name="destination_id" id="destination-id-input" required>
                        </div>
                    </div>
                </div>

                <div id="shipping-section" class="card shadow-sm" style="display: none;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3">Pilih Metode Pengiriman</h5>
                        <div class="mb-3">
                            <label for="courier-select" class="form-label">Pilih Kurir</label>
                            <select class="form-select" id="courier-select" name="courier" required>
                                <option value="">Pilih Kurir</option>
                                <option value="jne">JNE</option>
                                <option value="tiki">TIKI</option>
                                <option value="pos">POS Indonesia</option>
                            </select>
                        </div>
                        <div id="shipping-options-container" class="mt-3">
                            <div class="text-center text-muted" id="shipping-placeholder">Pilih tujuan dan kurir untuk melihat ongkos kirim.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: RINGKASAN PESANAN --}}
            <div class="col-lg-5">
                <div class="card shadow-sm summary-card">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4">Ringkasan Pesanan</h5>
                        @if($cart && $cart->cartItems->isNotEmpty())
                            @foreach($cart->cartItems as $item)
                                <div class="d-flex justify-content-between small mb-2">
                                    <span class="text-truncate" style="max-width: 250px;">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                    <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp {{ number_format($cart->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim</span>
                            <span id="shipping-cost-summary">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>Total Pembayaran</span>
                            <span id="total-payment" class="text-primary">Rp {{ number_format($cart->subtotal ?? 0, 0, ',', '.') }}</span>
                        </div>

                        {{-- Hidden inputs untuk menyimpan pilihan final --}}
                        <input type="hidden" name="shipping_cost" id="shipping-cost-input">
                        <input type="hidden" name="shipping_service" id="shipping-service-input">
                        <input type="hidden" name="shipping_courier" id="shipping-courier-input">

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="place-order-btn" disabled>
                            Lanjutkan ke Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === ELEMEN DOM ===
    const searchInput = document.getElementById('destination-search');
    const resultsContainer = document.getElementById('destination-results-container');
    const destinationIdInput = document.getElementById('destination-id-input');
    const shippingSection = document.getElementById('shipping-section');
    const courierSelect = document.getElementById('courier-select');
    const shippingOptionsContainer = document.getElementById('shipping-options-container');
    const shippingPlaceholder = document.getElementById('shipping-placeholder');
    const placeOrderBtn = document.getElementById('place-order-btn');

    // === DATA DARI SERVER (BLADE) ===
    const subtotal = {{ $cart->subtotal ?? 0 }};
    const totalWeight = {{ $cart->total_weight ?? 0 }};

    // PERBAIKAN 1: Gunakan helper config() untuk mengambil variabel.
    // Pastikan Anda sudah menambahkan KOMERCE_ORIGIN_ID di .env dan config/services.php
    const originCityId = '{{ config('services.komerce.origin_id') }}';

    let debounceTimer;

    // === EVENT LISTENERS ===
    searchInput.addEventListener('keyup', function () {
        clearTimeout(debounceTimer);
        const query = this.value;

        if (query.length < 3) {
            resultsContainer.innerHTML = '';
            shippingSection.style.display = 'none';
            courierSelect.value = '';
            courierSelect.disabled = true;
            resetShipping();
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('api.shipping.destinations') }}?search=${query}`)
                .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok.'))
                .then(data => displayDestinations(data))
                .catch(error => console.error('Error fetching destinations:', error));
        }, 300);
    });

    resultsContainer.addEventListener('click', function(e) {
        e.preventDefault();
        const target = e.target.closest('.list-group-item-action'); // Target klik yang lebih andal
        if (target) {
            searchInput.value = target.textContent.trim();
            destinationIdInput.value = target.dataset.id;
            resultsContainer.innerHTML = '';
            shippingSection.style.display = 'block';
            courierSelect.disabled = false;
            resetShipping();
        }
    });

    courierSelect.addEventListener('change', calculateCost);

    // === FUNGSI-FUNGSI ===
    function displayDestinations(data) {
        resultsContainer.innerHTML = '';
        if (data && data.length > 0) {
            const listGroup = document.createElement('div');
            listGroup.className = 'list-group';
            data.forEach(dest => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                // PERBAIKAN 2: Sesuaikan dengan format response API Komerce (label & id)
                item.textContent = dest.label;
                item.dataset.id = dest.id;
                listGroup.appendChild(item);
            });
            resultsContainer.appendChild(listGroup);
        } else {
            resultsContainer.innerHTML = '<div class="list-group"><div class="list-group-item disabled">Tujuan tidak ditemukan</div></div>';
        }
    }

    function calculateCost() {
        const destinationId = destinationIdInput.value;
        const courier = courierSelect.value;

        if (!destinationId || !courier) {
            shippingOptionsContainer.innerHTML = '';
            shippingPlaceholder.style.display = 'block';
            return;
        }

        shippingOptionsContainer.innerHTML = '<div class="text-center p-3"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghitung...</div>';
        shippingPlaceholder.style.display = 'none';
        resetShipping();

        fetch(`{{ route('api.shipping.calculate') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                destination: destinationId,
                courier: courier,
            })
        })
        .then(response => response.ok ? response.json() : response.json().then(err => Promise.reject(err)))
        .then(data => {
            shippingOptionsContainer.innerHTML = '';
            // PERBAIKAN 3: Sesuaikan dengan struktur response ongkir RajaOngkir/Komerce
            if (data && data.length > 0 && data[0].costs && data[0].costs.length > 0) {
                data[0].costs.forEach(cost => {
                    const costDiv = document.createElement('div');
                    costDiv.className = 'p-3 mb-2 rounded shipping-option';
                    costDiv.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">${data[0].code.toUpperCase()} - ${cost.service}</span>
                            <span class="fw-bold">Rp ${cost.cost[0].value.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="text-muted small">Estimasi: ${cost.cost[0].etd} hari</div>
                    `;
                    costDiv.onclick = () => selectShipping(costDiv, `${data[0].code.toUpperCase()} - ${cost.service}`, cost.cost[0].value);
                    shippingOptionsContainer.appendChild(costDiv);
                });
            } else {
                shippingOptionsContainer.innerHTML = '<div class="alert alert-warning">Tidak ada layanan pengiriman yang tersedia untuk tujuan ini.</div>';
            }
        })
        .catch(error => {
            console.error('Error calculating shipping:', error);
            const errorMessage = error.error || 'Gagal menghitung ongkos kirim. Silakan coba lagi.';
            shippingOptionsContainer.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
        });
    }

    function selectShipping(element, service, cost) {
        document.querySelectorAll('.shipping-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        document.getElementById('shipping-cost-summary').textContent = `Rp ${cost.toLocaleString('id-ID')}`;
        document.getElementById('shipping-cost-input').value = cost;
        document.getElementById('shipping-service-input').value = service;
        document.getElementById('shipping-courier-input').value = courierSelect.value;

        const total = subtotal + cost;
        document.getElementById('total-payment').textContent = `Rp ${total.toLocaleString('id-ID')}`;

        placeOrderBtn.disabled = false;
    }

    function resetShipping() {
        placeOrderBtn.disabled = true;
        document.getElementById('shipping-cost-input').value = '';
        document.getElementById('shipping-service-input').value = '';
        document.getElementById('shipping-cost-summary').textContent = 'Rp 0';
        document.getElementById('total-payment').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    }
});
</script>
@endpush
