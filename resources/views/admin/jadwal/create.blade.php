{{-- resources/views/admin/jadwal/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Jadwal Baru')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-plus me-2"></i>Tambah Jadwal Baru
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" 
                           class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" 
                           name="tanggal" 
                           value="{{ old('tanggal') }}" 
                           required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kegiatan" class="form-label">Keterangan Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('kegiatan') is-invalid @enderror" 
                           id="kegiatan" 
                           name="kegiatan" 
                           value="{{ old('kegiatan') }}" 
                           placeholder="Contoh: Rapat Koordinasi" 
                           required>
                    @error('kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Jadwal
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection