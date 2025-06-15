{{-- resources/views/obat/_form.blade.php --}}
@csrf {{-- CSRF Token untuk keamanan --}}

{{-- Informasi Dasar Obat --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="kode_obat" class="form-label">Kode Obat <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('kode_obat') is-invalid @enderror" id="kode_obat" name="kode_obat" value="{{ old('kode_obat', $obat->kode_obat ?? '') }}" required>
        @error('kode_obat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat ?? '') }}" required>
        @error('nama_obat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="deskripsi" class="form-label">Deskripsi</label>
    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi detail obat">{{ old('deskripsi', $obat->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Informasi Satuan dan Supplier --}}
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan" value="{{ old('satuan', $obat->satuan ?? '') }}" placeholder="Contoh: Tablet, Botol, PCS" required>
        @error('satuan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="distributor" class="form-label">Distributor</label>
        <input type="text" class="form-control @error('distributor') is-invalid @enderror" id="distributor" name="distributor" value="{{ old('distributor', $obat->distributor ?? '') }}" placeholder="Nama Distributor">
        @error('distributor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="nomor_batch" class="form-label">Nomor Batch</label>
        <input type="text" class="form-control @error('nomor_batch') is-invalid @enderror" id="nomor_batch" name="nomor_batch" value="{{ old('nomor_batch', $obat->nomor_batch ?? '') }}" placeholder="Nomor Batch Produksi">
        @error('nomor_batch')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Informasi Stok --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="stok" class="form-label">Stok Awal <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', $obat->stok ?? 0) }}" required min="0">
        @error('stok')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="stok_minimal" class="form-label">Stok Minimal</label>
        <input type="number" class="form-control @error('stok_minimal') is-invalid @enderror" id="stok_minimal" name="stok_minimal" value="{{ old('stok_minimal', $obat->stok_minimal ?? 10) }}" min="0">
        @error('stok_minimal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Informasi Harga --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="harga_beli" class="form-label">Harga Beli <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="0.01" class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli" name="harga_beli" value="{{ old('harga_beli', $obat->harga_beli ?? 0) }}" required min="0">
        </div>
        @error('harga_beli')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">Rp</span>
            <input type="number" step="0.01" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $obat->harga_jual ?? 0) }}" required min="0">
        </div>
        @error('harga_jual')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Tanggal Kadaluarsa --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" value="{{ old('tanggal_kadaluarsa', isset($obat->tanggal_kadaluarsa) ? ($obat->tanggal_kadaluarsa instanceof \Carbon\Carbon ? $obat->tanggal_kadaluarsa->format('Y-m-d') : $obat->tanggal_kadaluarsa) : '') }}" required>
        @error('tanggal_kadaluarsa')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        {{-- Kolom kosong untuk menjaga balance layout --}}
    </div>
</div>

{{-- Tombol Submit --}}
<div class="mt-4 pt-3 border-top">
    <button type="submit" class="btn btn-primary me-2">
        <i class="fas fa-save me-1"></i>
        {{ $tombolSubmit ?? 'Simpan' }}
    </button>
    <a href="{{ route('obat.index') }}" class="btn btn-secondary">
        <i class="fas fa-times me-1"></i>
        Batal
    </a>
</div>