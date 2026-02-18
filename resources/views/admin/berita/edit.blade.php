@extends('layouts.main')

@section('title', 'Edit Berita')

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
                <i class="fas fa-edit me-2"></i>Edit Berita
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('judul') is-invalid @enderror" 
                           id="judul" 
                           name="judul" 
                           value="{{ old('judul', $berita->judul) }}" 
                           required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ringkasan" class="form-label">Ringkasan</label>
                    <textarea class="form-control @error('ringkasan') is-invalid @enderror" 
                              id="ringkasan" 
                              name="ringkasan" 
                              rows="3">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                    @error('ringkasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="konten" class="form-label">Konten Berita <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('konten') is-invalid @enderror" 
                              id="konten" 
                              name="konten" 
                              rows="10">{{ old('konten', $berita->isi) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Gambar Sampul</label>
                    
                    @if($berita->gambar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $berita->gambar) }}" 
                             alt="Current image" 
                             style="max-height: 150px; object-fit: cover;"
                             class="border rounded">
                        <p class="text-muted small mt-1">Gambar saat ini</p>
                    </div>
                    @endif
                    
                    <input type="file" 
                           class="form-control @error('gambar') is-invalid @enderror" 
                           id="gambar" 
                           name="gambar" 
                           accept="image/*">
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Format: JPG, PNG, GIF (max: 2MB). Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="is_published" 
                               name="is_published" 
                               value="1" 
                               {{ old('is_published', $berita->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            Publikasikan berita ini
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Berita
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection