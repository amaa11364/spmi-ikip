@extends('layouts.main')

@section('title', 'Tambah Evaluasi SPMI')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-plus-circle me-2 text-primary"></i>Tambah Evaluasi Baru
            </h4>
            <p class="text-muted mb-0">Form tambah data evaluasi SPMI</p>
        </div>
        <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('spmi.evaluasi.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Nama Evaluasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_evaluasi" required 
                                   placeholder="Contoh: Audit Mutu Internal Prodi Teknik">
                            @error('nama_evaluasi')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipe_evaluasi" required>
                                    <option value="">Pilih Tipe Evaluasi</option>
                                    <option value="ami" {{ old('tipe_evaluasi') == 'ami' ? 'selected' : '' }}>Audit Mutu Internal (AMI)</option>
                                    <option value="edom" {{ old('tipe_evaluasi') == 'edom' ? 'selected' : '' }}>Evaluasi Dosen oleh Mahasiswa (EDOM)</option>
                                    <option value="evaluasi_layanan" {{ old('tipe_evaluasi') == 'evaluasi_layanan' ? 'selected' : '' }}>Evaluasi Layanan</option>
                                    <option value="evaluasi_kinerja" {{ old('tipe_evaluasi') == 'evaluasi_kinerja' ? 'selected' : '' }}>Evaluasi Kinerja</option>
                                </select>
                                @error('tipe_evaluasi')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="tahun" 
                                       value="{{ old('tahun', date('Y')) }}" min="2000" max="{{ date('Y') + 5 }}" required>
                                @error('tahun')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Periode (opsional)</label>
                            <input type="text" class="form-control" name="periode" 
                                   value="{{ old('periode') }}" 
                                   placeholder="Contoh: Semester Ganjil 2024, Triwulan I 2024">
                            @error('periode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="berjalan" {{ old('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Dokumen</label>
                                <select class="form-select" name="status_dokumen">
                                    <option value="belum_valid" {{ old('status_dokumen') == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                                    <option value="valid" {{ old('status_dokumen') == 'valid' ? 'selected' : '' }}>Valid</option>
                                    <option value="dalam_review" {{ old('status_dokumen') == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
                                </select>
                                @error('status_dokumen')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Kerja</label>
                                <select class="form-select" name="unit_kerja_id">
                                    <option value="">Pilih Unit Kerja</option>
                                    @foreach($unitKerjas as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_kerja_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">IKU</label>
                                <select class="form-select" name="iku_id">
                                    <option value="">Pilih IKU</option>
                                    @foreach($ikus as $iku)
                                        <option value="{{ $iku->id }}" {{ old('iku_id') == $iku->id ? 'selected' : '' }}>
                                            {{ $iku->kode }} - {{ $iku->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('iku_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab" 
                                   value="{{ old('penanggung_jawab') }}" 
                                   placeholder="Nama penanggung jawab evaluasi">
                            @error('penanggung_jawab')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="4" 
                                      placeholder="Deskripsi lengkap tentang evaluasi ini">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi</h6>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted">
                                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                                    Kode evaluasi akan digenerate otomatis setelah data disimpan.
                                </p>
                                <p class="small text-muted">
                                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                                    Pastikan data yang dimasukkan sudah benar sebelum disimpan.
                                </p>
                                <hr>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Simpan Evaluasi
                                    </button>
                                    <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i> Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection