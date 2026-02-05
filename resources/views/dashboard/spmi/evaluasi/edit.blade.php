@extends('layouts.main')

@section('title', 'Edit Evaluasi SPMI')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-edit me-2 text-primary"></i>Edit Evaluasi
            </h4>
            <p class="text-muted mb-0">Form edit data evaluasi SPMI</p>
        </div>
        <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('spmi.evaluasi.update', $evaluasi->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Nama Evaluasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_evaluasi" required 
                                   value="{{ old('nama_evaluasi', $evaluasi->nama_evaluasi) }}">
                            @error('nama_evaluasi')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipe_evaluasi" required>
                                    <option value="ami" {{ old('tipe_evaluasi', $evaluasi->tipe_evaluasi) == 'ami' ? 'selected' : '' }}>Audit Mutu Internal (AMI)</option>
                                    <option value="edom" {{ old('tipe_evaluasi', $evaluasi->tipe_evaluasi) == 'edom' ? 'selected' : '' }}>Evaluasi Dosen oleh Mahasiswa (EDOM)</option>
                                    <option value="evaluasi_layanan" {{ old('tipe_evaluasi', $evaluasi->tipe_evaluasi) == 'evaluasi_layanan' ? 'selected' : '' }}>Evaluasi Layanan</option>
                                    <option value="evaluasi_kinerja" {{ old('tipe_evaluasi', $evaluasi->tipe_evaluasi) == 'evaluasi_kinerja' ? 'selected' : '' }}>Evaluasi Kinerja</option>
                                </select>
                                @error('tipe_evaluasi')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="tahun" 
                                       value="{{ old('tahun', $evaluasi->tahun) }}" min="2000" max="{{ date('Y') + 5 }}" required>
                                @error('tahun')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Periode (opsional)</label>
                            <input type="text" class="form-control" name="periode" 
                                   value="{{ old('periode', $evaluasi->periode) }}">
                            @error('periode')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="aktif" {{ old('status', $evaluasi->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $evaluasi->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="selesai" {{ old('status', $evaluasi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="berjalan" {{ old('status', $evaluasi->status) == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Dokumen</label>
                                <select class="form-select" name="status_dokumen">
                                    <option value="belum_valid" {{ old('status_dokumen', $evaluasi->status_dokumen) == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                                    <option value="valid" {{ old('status_dokumen', $evaluasi->status_dokumen) == 'valid' ? 'selected' : '' }}>Valid</option>
                                    <option value="dalam_review" {{ old('status_dokumen', $evaluasi->status_dokumen) == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
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
                                        <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $evaluasi->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
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
                                        <option value="{{ $iku->id }}" {{ old('iku_id', $evaluasi->iku_id) == $iku->id ? 'selected' : '' }}>
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
                                   value="{{ old('penanggung_jawab', $evaluasi->penanggung_jawab) }}">
                            @error('penanggung_jawab')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="4">{{ old('deskripsi', $evaluasi->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Evaluasi</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row small">
                                    <dt class="col-sm-5">Kode:</dt>
                                    <dd class="col-sm-7"><code>{{ $evaluasi->kode_evaluasi }}</code></dd>
                                    
                                    <dt class="col-sm-5">Dibuat:</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->created_at->format('d/m/Y H:i') }}</dd>
                                    
                                    <dt class="col-sm-5">Diperbarui:</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->updated_at->format('d/m/Y H:i') }}</dd>
                                    
                                    <dt class="col-sm-5">Total Dokumen:</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->total_dokumen }}</dd>
                                </dl>
                                <hr>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('spmi.evaluasi.show', $evaluasi->id) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye me-2"></i> Lihat Detail
                                    </a>
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