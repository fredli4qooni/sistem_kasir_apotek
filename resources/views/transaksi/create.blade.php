{{-- resources/views/transaksi/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid"> {{-- Gunakan container-fluid agar lebih lebar --}}
    <div class="row">
        {{-- Kolom Kiri: Pencarian Obat dan Item Terpilih --}}
        <div class="col-lg-7"> {{-- Ganti ke lg untuk layout lebih baik di layar besar --}}
            <div class="card mb-3">
                <div class="card-header">Pencarian Obat</div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Ketik Nama/Kode Obat atau Scan QR...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton" title="Cari Manual">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <button class="btn btn-outline-success" type="button" id="startQrScanButton" title="Pindai QR Code">
                            <i class="fas fa-qrcode"></i> Scan
                        </button>
                    </div>

                    {{-- Area untuk QR Code Scanner --}}
                    <div id="qrScannerContainer" class="mb-3" style="display: none;">
                        <div id="qr-reader" style="width: 100%; max-width: 400px; margin: auto; border: 1px solid #ddd; padding: 5px;"></div>
                        <button class="btn btn-danger btn-sm mt-2 d-block mx-auto" id="stopQrScanButton" style="display: none;">
                            <i class="fas fa-stop-circle"></i> Stop Scan
                        </button>
                         <p id="qrScanFeedback" class="text-center text-muted mt-2 small"></p> {{-- Feedback status scan --}}
                    </div>
                    {{-- Akhir Area QR Scanner --}}

                    <div id="searchResults" class="list-group" style="max-height: 200px; overflow-y: auto;">
                        {{-- Hasil pencarian akan muncul di sini --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Keranjang Belanja</span>
                    <button id="clearCartButton" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i> Kosongkan</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" id="cartTable">
                            <thead>
                                <tr>
                                    <th>Obat</th>
                                    <th class="text-center" style="width: 120px;">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center" style="width: 100px;">Diskon (%)</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Item di keranjang akan muncul di sini --}}
                                <tr id="emptyCartRow">
                                    <td colspan="6" class="text-center">Keranjang masih kosong.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Transaksi dan Pembayaran --}}
        <div class="col-lg-5 mt-3 mt-lg-0"> {{-- mt-3 untuk mobile, mt-lg-0 untuk desktop --}}
            <form id="formTransaksi" method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header">Detail Transaksi</div>
                    <div class="card-body">
                         @if ($errors->any())
                            <div class="alert alert-danger pb-0">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                         @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                         @endif

                        <div class="mb-3 row">
                            <label for="nomor_transaksi" class="col-sm-4 col-form-label">No. Transaksi</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="nomor_transaksi" name="nomor_transaksi" value="{{ $nomorTransaksi }}">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="kasir" class="col-sm-4 col-form-label">Kasir</label>
                            <div class="col-sm-8">
                                <input type="text" readonly class="form-control-plaintext" id="kasir" value="{{ Auth::user()->name }}">
                                <input type="hidden" name="id_user" value="{{ Auth::id() }}">
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2 row align-items-center">
                            <h5 class="col-sm-6 mb-0">Grand Total:</h5>
                            <h5 class="col-sm-6 text-end mb-0" id="grandTotalDisplay">Rp 0</h5>
                            <input type="hidden" name="total_harga" id="grandTotalInput" value="0">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                            <input type="number" step="1" class="form-control" id="jumlah_bayar" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="kembalian" class="form-label">Kembalian</label>
                            <input type="text" readonly class="form-control-plaintext" id="kembalian" name="kembalian_display" value="Rp 0">
                            <input type="hidden" name="kembalian" id="kembalianInput" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                        </div>

                        {{-- Hidden input untuk item keranjang (akan diisi oleh JavaScript) --}}
                        <div id="cartItemsInput"></div>

                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" id="simpanTransaksiButton" class="btn btn-success btn-lg" disabled>
                            <i class="fas fa-save"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library html5-qrcode harus sudah di-include di layout utama --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Elemen DOM
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchResultsContainer = document.getElementById('searchResults');
    const cartTableBody = document.getElementById('cartTable').getElementsByTagName('tbody')[0];
    const emptyCartRow = document.getElementById('emptyCartRow');
    const grandTotalDisplay = document.getElementById('grandTotalDisplay');
    const grandTotalInput = document.getElementById('grandTotalInput');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const kembalianDisplay = document.getElementById('kembalian');
    const kembalianInput = document.getElementById('kembalianInput');
    const simpanTransaksiButton = document.getElementById('simpanTransaksiButton');
    const clearCartButton = document.getElementById('clearCartButton');
    const formTransaksi = document.getElementById('formTransaksi');
    const cartItemsInputContainer = document.getElementById('cartItemsInput');

    // Elemen DOM untuk QR Scanner
    const startQrScanButton = document.getElementById('startQrScanButton');
    const stopQrScanButton = document.getElementById('stopQrScanButton');
    const qrScannerContainer = document.getElementById('qrScannerContainer');
    const qrReaderElementId = "qr-reader";
    const qrScanFeedback = document.getElementById('qrScanFeedback');
    let html5QrCodeScanner;

    // State aplikasi
    let cart = [];

    // ----- FUNGSI-FUNGSI -----
    async function searchObat() {
        const query = searchInput.value.trim();
        if (query.length < 1) {
            searchResultsContainer.innerHTML = '<p class="text-muted p-2 small">Ketik nama/kode atau scan QR.</p>';
            return;
        }
        searchResultsContainer.innerHTML = '<p class="text-muted p-2 small">Mencari...</p>';
        try {
            const response = await fetch(`{{ route('api.obat.search') }}?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const obats = await response.json();
            renderSearchResults(obats);
        } catch (error) {
            console.error("Fetch error:", error);
            searchResultsContainer.innerHTML = '<p class="text-danger p-2 small">Gagal mengambil data obat.</p>';
        }
    }

    function renderSearchResults(obats) {
        searchResultsContainer.innerHTML = '';
        if (obats.length > 0) {
            obats.forEach(obat => {
                const isInCart = cart.some(item => item.id === obat.id);
                const isStokHabis = obat.stok <= 0;
                let itemClass = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center small py-2';
                let stokInfo = `<span class="badge bg-success rounded-pill">Stok: ${obat.stok}</span>`;
                if (isStokHabis) {
                    stokInfo = `<span class="badge bg-danger rounded-pill">Stok Habis</span>`;
                    itemClass += ' disabled text-muted';
                } else if (isInCart) {
                    stokInfo = `<span class="badge bg-info rounded-pill">Sudah di Keranjang</span>`;
                    itemClass += ' list-group-item-info';
                }
                const itemHtml = `
                    <a href="#" class="${itemClass}" data-id="${obat.id}" data-nama="${obat.nama_obat}" data-harga="${obat.harga_jual}" data-stok="${obat.stok}" ${isStokHabis ? 'style="pointer-events: none;"' : ''}>
                        <div><strong>${obat.nama_obat}</strong> (${obat.kode_obat})<br><small>Harga: Rp ${formatRupiah(obat.harga_jual.toString())}</small></div>
                        ${stokInfo}
                    </a>`;
                searchResultsContainer.insertAdjacentHTML('beforeend', itemHtml);
            });
            searchResultsContainer.querySelectorAll('.list-group-item:not(.disabled)').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault(); addToCart(this.dataset); searchInput.value = ''; searchResultsContainer.innerHTML = '';
                });
            });
        } else {
            searchResultsContainer.innerHTML = '<p class="text-muted p-2 small">Obat tidak ditemukan.</p>';
        }
    }

    function addToCart(obatData) {
        const obatId = parseInt(obatData.id);
        const stokMax = parseInt(obatData.stok);
        const existingItem = cart.find(item => item.id === obatId);
        if (existingItem) {
            if (existingItem.jumlah < stokMax) { existingItem.jumlah++; }
            else { alert(`Stok maksimal untuk ${obatData.nama} hanya ${stokMax}.`); }
        } else {
            if (stokMax > 0) {
                cart.push({
                    id: obatId, nama: obatData.nama, harga: parseFloat(obatData.harga),
                    stok: stokMax, jumlah: 1, diskonPersen: 0
                });
            } else { alert(`${obatData.nama} stoknya habis.`); return; }
        }
        renderCart(); updateGrandTotal(); validateForm();
    }

    function renderCart() {
        cartTableBody.innerHTML = '';
        if (cart.length === 0) {
            cartTableBody.appendChild(emptyCartRow);
        } else {
            cart.forEach((item, index) => {
                const row = cartTableBody.insertRow();
                row.dataset.index = index;
                let hargaSebelumDiskon = item.jumlah * item.harga;
                let diskonNominal = (item.diskonPersen / 100) * hargaSebelumDiskon;
                let subTotalSetelahDiskon = hargaSebelumDiskon - diskonNominal;

                row.innerHTML = `
                    <td>${item.nama}</td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 110px; margin: auto;">
                            <button class="btn btn-outline-secondary btn-sm change-qty" data-action="decrease" type="button">-</button>
                            <input type="number" class="form-control form-control-sm text-center qty-input" value="${item.jumlah}" min="1" max="${item.stok}" style="width: 40px;" aria-label="Jumlah">
                            <button class="btn btn-outline-secondary btn-sm change-qty" data-action="increase" type="button">+</button>
                        </div>
                    </td>
                    <td class="text-end">Rp ${formatRupiah(item.harga.toString())}</td>
                    <td class="text-center">
                        <div class="input-group input-group-sm" style="width: 80px; margin: auto;">
                            <input type="number" step="0.01" class="form-control form-control-sm diskon-persen-input" value="${item.diskonPersen}" min="0" max="100" placeholder="%" title="Diskon Persen">
                            <span class="input-group-text">%</span>
                        </div>
                    </td>
                    <td class="text-end fw-bold">Rp ${formatRupiah(subTotalSetelahDiskon.toString())}</td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm remove-item" title="Hapus Item"><i class="fas fa-times"></i></button>
                    </td>`;
            });
        }
    }

    cartTableBody.addEventListener('click', function(e) {
        const target = e.target;
        const row = target.closest('tr');
        if (!row || !row.dataset.index) return;
        const index = parseInt(row.dataset.index);
        if (target.classList.contains('change-qty')) {
            updateQuantity(index, target.dataset.action);
        } else if (target.classList.contains('remove-item') || target.closest('.remove-item')) {
            removeItemFromCart(index);
        }
    });

    cartTableBody.addEventListener('input', function(e) {
        const target = e.target;
        const row = target.closest('tr');
        if (!row || !row.dataset.index) return;
        const index = parseInt(row.dataset.index);
        const item = cart[index];
        if (!item) return;

        let reRenderRow = false;

        if (target.classList.contains('qty-input')) {
            let newQty = parseInt(target.value);
            let currentItemQty = item.jumlah;
            if (isNaN(newQty) || newQty < 1) { newQty = 1; }
            if (newQty > item.stok) { newQty = item.stok; alert(`Stok maksimal ${item.nama}: ${item.stok}.`); }
            target.value = newQty;
            item.jumlah = newQty;
            if (item.jumlah !== currentItemQty) reRenderRow = true;

        } else if (target.classList.contains('diskon-persen-input')) {
            let persen = parseFloat(target.value) || 0;
            if (persen < 0) persen = 0; 
            if (persen > 100) persen = 100;
            target.value = persen;
            if (item.diskonPersen !== persen) {
                item.diskonPersen = persen;
                reRenderRow = true;
            }
        }

        if (reRenderRow) {
            renderCart();
            updateGrandTotal();
            validateForm();
        }
    });

    function updateQuantity(index, action) {
        const item = cart[index];
        if (!item) return;
        let oldQty = item.jumlah;

        if (action === 'increase') {
            if (item.jumlah < item.stok) item.jumlah++;
            else alert(`Stok maksimal ${item.nama}: ${item.stok}.`);
        } else if (action === 'decrease') {
            if (item.jumlah > 1) item.jumlah--;
            else { removeItemFromCart(index); return; }
        }
        if (oldQty !== item.jumlah) {
             renderCart(); updateGrandTotal(); validateForm();
        }
    }

    function removeItemFromCart(index) {
        cart.splice(index, 1);
        renderCart(); updateGrandTotal(); validateForm();
    }

    function updateGrandTotal() {
        const total = cart.reduce((sum, item) => {
            let hargaSebelumDiskon = item.jumlah * item.harga;
            let diskonNominal = (item.diskonPersen / 100) * hargaSebelumDiskon;
            let subTotalSetelahDiskon = hargaSebelumDiskon - diskonNominal;
            return sum + subTotalSetelahDiskon;
        }, 0);
        grandTotalDisplay.textContent = `Rp ${formatRupiah(total.toString())}`;
        grandTotalInput.value = total;
        calculateKembalian();
    }

    function formatRupiah(angka, prefix = '') {
        if (angka === null || typeof angka === 'undefined' || isNaN(Number(angka))) return prefix + '0';
        let number_string = parseFloat(angka).toFixed(0).toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','), sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa), ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) { rupiah += (sisa ? '.' : '') + ribuan.join('.'); }
        return prefix + rupiah;
    }

    function calculateKembalian() {
        const totalBelanja = parseFloat(grandTotalInput.value) || 0;
        const bayar = parseFloat(jumlahBayarInput.value) || 0;
        let kembali = (bayar >= totalBelanja && totalBelanja > 0) ? bayar - totalBelanja : 0;
        kembalianDisplay.value = `Rp ${formatRupiah(kembali.toString())}`;
        kembalianInput.value = kembali;
        validateForm();
    }

    function validateForm() {
        const totalBelanja = parseFloat(grandTotalInput.value) || 0;
        const bayar = parseFloat(jumlahBayarInput.value) || 0;
        const isCartEmpty = cart.length === 0;
        simpanTransaksiButton.disabled = (isCartEmpty || totalBelanja <= 0 || bayar < totalBelanja);
    }

    // --- FUNGSI-FUNGSI SCANNER QR ---
    function startQrScanner() {
        qrScanFeedback.textContent = 'Mempersiapkan kamera...';
        qrScannerContainer.style.display = 'block'; stopQrScanButton.style.display = 'block';
        startQrScanButton.disabled = true; searchInput.disabled = true;
        html5QrCodeScanner = new Html5Qrcode(qrReaderElementId);
        const qrConfig = { fps: 10, qrbox: { width: 250, height: 250 }, supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA] };
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            qrScanFeedback.textContent = `Kode terdeteksi: ${decodedText}`;
            searchInput.value = decodedText; stopQrScanner(); searchObat();
            setTimeout(() => { qrScanFeedback.textContent = ''; }, 2000);
        };
        html5QrCodeScanner.start({ facingMode: "environment" }, qrConfig, qrCodeSuccessCallback, (errorMessage) => {})
            .then(() => { qrScanFeedback.textContent = 'Arahkan kamera ke QR Code...'; })
            .catch(err => {
                console.error("Gagal memulai QR Scanner:", err);
                alert("Gagal memulai kamera. Pastikan Anda memberikan izin dan tidak ada aplikasi lain yang menggunakan kamera.");
                stopQrScanner();
            });
    }

    function stopQrScanner() {
        if (html5QrCodeScanner && html5QrCodeScanner.getState() === Html5QrcodeScannerState.SCANNING) {
             html5QrCodeScanner.stop().then(() => {}).catch(err => {}).finally(() => { html5QrCodeScanner.clear(); });
        }
        qrScanFeedback.textContent = ''; qrScannerContainer.style.display = 'none'; stopQrScanButton.style.display = 'none';
        startQrScanButton.disabled = false; searchInput.disabled = false;
    }

    // ----- EVENT LISTENERS -----
    searchButton.addEventListener('click', searchObat);
    searchInput.addEventListener('keypress', function (e) { if (e.key === 'Enter') { e.preventDefault(); searchObat(); } });
    startQrScanButton.addEventListener('click', startQrScanner);
    stopQrScanButton.addEventListener('click', stopQrScanner);
    jumlahBayarInput.addEventListener('input', calculateKembalian);
    clearCartButton.addEventListener('click', function() {
        if (confirm('Anda yakin ingin mengosongkan keranjang?')) {
            cart = []; renderCart(); updateGrandTotal(); validateForm();
            searchResultsContainer.innerHTML = ''; searchInput.value = '';
        }
    });
    formTransaksi.addEventListener('submit', function(e) {
        cartItemsInputContainer.innerHTML = '';
        cart.forEach((item, index) => {
            cartItemsInputContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][id_obat]" value="${item.id}">`);
            cartItemsInputContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">`);
            cartItemsInputContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][harga_satuan]" value="${item.harga}">`);
            cartItemsInputContainer.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][diskon_item_persen]" value="${item.diskonPersen || 0}">`);
        });
        if (simpanTransaksiButton.disabled) {
            e.preventDefault();
            alert('Periksa kembali transaksi Anda. Pastikan ada item di keranjang dan jumlah bayar mencukupi.');
        } else {
             simpanTransaksiButton.disabled = true;
             simpanTransaksiButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        }
    });

    // ----- INISIALISASI -----
    renderCart();
    updateGrandTotal();
});
</script>
@endpush