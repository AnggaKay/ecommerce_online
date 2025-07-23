@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    body { background-color: #f8f9fa; }
    .summary-card { position: sticky; top: 20px; }
    .shipping-option { cursor: pointer; border: 1px solid #ddd; transition: all 0.2s ease-in-out; }
    .shipping-option:hover { background-color: #f8f9fa; border-color: #0d6efd; }
    .shipping-option.selected { border-color: #0d6efd; border-width: 2px; background-color: #e7f1ff; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Halaman Checkout</h1>
        <p class="text-muted">Lengkapi detail pengiriman dan selesaikan pesanan Anda.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row g-5">
            <div class="col-lg-7">
                <!-- Detail Pengiriman -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3">Detail Pengiriman</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Contoh: Jl. Mawar No. 5, Kel. Suka Maju, Kec. Damai, Kota Bandar Lampung, 35142" required></textarea>
                            </div>
                             <div class="col-12">
                                <label for="notes" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Contoh: Pagar warna biru, dekat masjid"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opsi Pengiriman -->
                <div id="shipping-section" class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3">Pilih Metode Pengiriman</h5>
                        <div class="mb-3">
                            <label for="courier-select" class="form-label">Pilih Kurir <span class="text-danger">*</span></label>
                            <select class="form-select" id="courier-select" name="shipping_courier" required>
                                <option value="" selected disabled>-- Pilih Salah Satu --</option>
                                <option value="jne">JNE</option>
                                <option value="jnt">J&T</option>
                                <option value="tiki">TIKI</option>
                                <option value="pos">POS Indonesia</option>
                            </select>
                        </div>
                        <div id="shipping-options-container" class="mt-3">
                            <div class="text-center text-muted" id="shipping-placeholder">Pilih kurir untuk melihat ongkos kirim.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="col-lg-5">
                <div class="card shadow-sm summary-card">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4">Ringkasan Pesanan</h5>
                        @if($cart && !$cart->cartItems->isEmpty())
                            @foreach($cart->cartItems as $item)
                                <div class="d-flex justify-content-between small mb-2">
                                    <span class="text-truncate" style="max-width: 250px;">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                    <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small">Keranjang Anda kosong.</p>
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
                        <input type="hidden" name="shipping_cost" id="shipping-cost-input" required>
                        <input type="hidden" name="shipping_service" id="shipping-service-input" required>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="place-order-btn" disabled>
                            Buat Pesanan
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
    const courierSelect = document.getElementById('courier-select');
    const shippingOptionsContainer = document.getElementById('shipping-options-container');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const subtotal = {{ $cart->subtotal ?? 0 }};

    courierSelect.addEventListener('change', calculateCost);

    function calculateCost() {
        const courier = courierSelect.value;
        if (!courier) {
            resetShipping();
            return;
        };

        shippingOptionsContainer.innerHTML = '<div class="text-center p-3"><span class="spinner-border spinner-border-sm"></span> Menghitung...</div>';
        resetShipping(false);

        fetch(`{{ route('checkout.calculate_cost') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ courier: courier })
        })
        .then(response => response.json())
        .then(response => {
            shippingOptionsContainer.innerHTML = '';
            if (response.meta.code === 200 && response.data.length > 0) {
                let services = response.data[0];
                services.costs.forEach(cost => {
                    const costDiv = document.createElement('div');
                    costDiv.className = 'p-3 mb-2 rounded shipping-option';
                    costDiv.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">${cost.service}</span>
                            <span class="fw-bold">Rp ${cost.cost[0].value.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="text-muted small">Estimasi: ${cost.cost[0].etd} hari</div>
                    `;
                    costDiv.onclick = () => selectShipping(costDiv, cost.service, cost.cost[0].value);
                    shippingOptionsContainer.appendChild(costDiv);
                    // Automatically select the first option
                    costDiv.click();
                });
            } else {
                shippingOptionsContainer.innerHTML = '<div class="alert alert-warning">Layanan pengiriman tidak tersedia.</div>';
            }
        })
        .catch(error => {
            console.error('Error calculating shipping:', error);
            shippingOptionsContainer.innerHTML = `<div class="alert alert-danger">Gagal menghitung ongkos kirim.</div>`;
        });
    }

    function selectShipping(element, service, cost) {
        document.querySelectorAll('.shipping-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('shipping-cost-summary').textContent = `Rp ${cost.toLocaleString('id-ID')}`;
        document.getElementById('shipping-cost-input').value = cost;
        document.getElementById('shipping-service-input').value = service;
        document.getElementById('total-payment').textContent = `Rp ${(subtotal + cost).toLocaleString('id-ID')}`;
        placeOrderBtn.disabled = false;
    }

    function resetShipping(resetCourier = true) {
        if (resetCourier) courierSelect.value = '';
        placeOrderBtn.disabled = true;
        document.getElementById('shipping-cost-input').value = '';
        document.getElementById('shipping-service-input').value = '';
        document.getElementById('shipping-cost-summary').textContent = 'Rp 0';
        document.getElementById('total-payment').textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        shippingOptionsContainer.innerHTML = '<div class="text-center text-muted" id="shipping-placeholder">Pilih kurir untuk melihat ongkos kirim.</div>';
    }
});
</script>
@endpush
