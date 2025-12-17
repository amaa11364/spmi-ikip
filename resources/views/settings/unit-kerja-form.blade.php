@extends('layouts.main')

@section('title', isset($unitKerja) ? 'Edit Unit Kerja' : 'Tambah Unit Kerja') {{-- DIUBAH --}}

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold">
                    <i class="fas fa-building me-2"></i> {{-- DIUBAH --}}
                    {{ isset($unitKerja) ? 'Edit Unit Kerja' : 'Tambah Unit Kerja' }} {{-- DIUBAH --}}
                </h4>
                <p class="text-muted mb-0">
                    {{ isset($unitKerja) ? 'Perbarui data Unit Kerja' : 'Tambahkan Unit Kerja baru' }} {{-- DIUBAH --}}
                </p>
            </div>
            <a href="{{ route('settings.unit-kerja.index') }}" class="btn btn-outline-secondary"> {{-- DIUBAH --}}
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($unitKerja) ? route('settings.unit-kerja.update', $unitKerja->id) : route('settings.unit-kerja.store') }}" method="POST"> {{-- DIUBAH --}}
                    @csrf
                    @if(isset($unitKerja)) {{-- DIUBAH --}}
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label">Kode Unit Kerja <span class="text-danger">*</span></label> {{-- DIUBAH --}}
                            <input type="text" class="form-control" id="kode" name="kode" 
                                   value="{{ old('kode', $unitKerja->kode ?? '') }}" required> {{-- DIUBAH --}}
                            @error('kode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Unit Kerja <span class="text-danger">*</span></label> {{-- DIUBAH --}}
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="{{ old('nama', $unitKerja->nama ?? '') }}" required> {{-- DIUBAH --}}
                            @error('nama')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $unitKerja->deskripsi ?? '') }}</textarea> {{-- DIUBAH --}}
                        @error('deskripsi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="status" name="status" 
                                   {{ isset($unitKerja) && $unitKerja->status ? 'checked' : 'checked' }}> {{-- DIUBAH --}}
                            <label class="form-check-label" for="status">
                                Status Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            Unit Kerja aktif akan muncul di form upload dokumen {{-- DIUBAH --}}
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('settings.unit-kerja.index') }}" class="btn btn-outline-secondary"> {{-- DIUBAH --}}
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            {{ isset($unitKerja) ? 'Update Unit Kerja' : 'Simpan Unit Kerja' }} {{-- DIUBAH --}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection