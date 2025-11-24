@extends('layouts.main')

@section('title', isset($iku) ? 'Edit IKU' : 'Tambah IKU')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold">
                    <i class="fas fa-chart-line me-2"></i>
                    {{ isset($iku) ? 'Edit IKU' : 'Tambah IKU' }}
                </h4>
                <p class="text-muted mb-0">
                    {{ isset($iku) ? 'Perbarui data IKU' : 'Tambahkan IKU baru' }}
                </p>
            </div>
            <a href="{{ route('settings.iku.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($iku) ? route('settings.iku.update', $iku->id) : route('settings.iku.store') }}" method="POST">
                    @csrf
                    @if(isset($iku))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label">Kode IKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode" name="kode" 
                                   value="{{ old('kode', $iku->kode ?? '') }}" required>
                            @error('kode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama IKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="{{ old('nama', $iku->nama ?? '') }}" required>
                            @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $iku->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" 
                                   {{ isset($iku) && $iku->status ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="status">
                                Status Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            IKU aktif akan muncul di form upload dokumen
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('settings.iku.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($iku) ? 'Update IKU' : 'Simpan IKU' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection