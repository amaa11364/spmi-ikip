{{-- resources/views/admin/berita/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Berita Baru')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-plus me-2"></i>Tambah Berita Baru
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" 
                           name="judul" 
                           value="{{ old('judul') }}" 
                           required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Sampul</label>
                    <input type="file" 
                           class="form-control @error('gambar') is-invalid @enderror" 
                           id="gambar" 
                           name="gambar" 
                           accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: JPG, PNG, GIF (max: 2MB)</small>
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label">Link Eksternal <span class="text-danger">*</span></label>
                    <input type="url" 
                           class="form-control @error('link') is-invalid @enderror" 
                           id="link" 
                           name="link" 
                           value="{{ old('link') }}" 
                           placeholder="https://" 
                           required>
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Berita
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