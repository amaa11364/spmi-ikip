@extends('layouts.main')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2 text-warning"></i>Edit Jadwal
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kegiatan" class="form-label">
                                Nama Kegiatan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('kegiatan') is-invalid @enderror" 
                                   id="kegiatan" 
                                   name="kegiatan" 
                                   value="{{ old('kegiatan', $jadwal->kegiatan) }}" 
                                   required>
                            @error('kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}" 
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu</label>
                            <input type="time" 
                                   class="form-control @error('waktu') is-invalid @enderror" 
                                   id="waktu" 
                                   name="waktu" 
                                   value="{{ old('waktu', $jadwal->waktu ? $jadwal->waktu->format('H:i') : '') }}">
                            @error('waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tempat" class="form-label">Tempat</label>
                            <input type="text" 
                                   class="form-control @error('tempat') is-invalid @enderror" 
                                   id="tempat" 
                                   name="tempat" 
                                   value="{{ old('tempat', $jadwal->tempat) }}">
                            @error('tempat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                            <input type="text" 
                                   class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                   id="penanggung_jawab" 
                                   name="penanggung_jawab" 
                                   value="{{ old('penanggung_jawab', $jadwal->penanggung_jawab) }}">
                            @error('penanggung_jawab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" 
                              name="deskripsi" 
                              rows="3">{{ old('deskripsi', $jadwal->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori') is-invalid @enderror" 
                                    id="kategori" 
                                    name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="rapat" {{ old('kategori', $jadwal->kategori) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                <option value="pelatihan" {{ old('kategori', $jadwal->kategori) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                                <option value="workshop" {{ old('kategori', $jadwal->kategori) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="sosialisasi" {{ old('kategori', $jadwal->kategori) == 'sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                                <option value="evaluasi" {{ old('kategori', $jadwal->kategori) == 'evaluasi' ? 'selected' : '' }}>Evaluasi</option>
                                <option value="lainnya" {{ old('kategori', $jadwal->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status">
                                <option value="akan_datang" {{ old('status', $jadwal->status) == 'akan_datang' ? 'selected' : '' }}>Akan Datang</option>
                                <option value="sedang_berlangsung" {{ old('status', $jadwal->status) == 'sedang_berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                <option value="selesai" {{ old('status', $jadwal->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status', $jadwal->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="warna" class="form-label">Warna</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" 
                                       class="form-control form-control-color @error('warna') is-invalid @enderror" 
                                       id="warna" 
                                       name="warna" 
                                       value="{{ old('warna', $jadwal->warna ?? '#0d6efd') }}">
                            </div>
                            @error('warna')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       role="switch" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $jadwal->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Perbarui Jadwal
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection