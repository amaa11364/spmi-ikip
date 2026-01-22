@extends('layouts.main')

@section('title', 'Edit Pelaksanaan SPMI')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('spmi.pelaksanaan.index') }}">
                    <i class="fas fa-play-circle me-1"></i> Repository Pelaksanaan
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('spmi.pelaksanaan.show', $pelaksanaan->id) }}">
                    {{ Str::limit($pelaksanaan->nama_komponen, 30) }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i> Edit Pelaksanaan: {{ $pelaksanaan->nama_komponen }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('spmi.pelaksanaan.update', $pelaksanaan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_komponen') is-invalid @enderror" 
                                   name="nama_komponen" value="{{ old('nama_komponen', $pelaksanaan->nama_komponen) }}" required>
                            @error('nama_komponen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" 
                                       name="tahun" value="{{ old('tahun', $pelaksanaan->tahun) }}" 
                                       min="2000" max="{{ date('Y') + 5 }}" required>
                                @error('tahun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="aktif" {{ old('status', $pelaksanaan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $pelaksanaan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="revisi" {{ old('status', $pelaksanaan->status) == 'revisi' ? 'selected' : '' }}>Revisi</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Dokumen</label>
                                <select class="form-select @error('status_dokumen') is-invalid @enderror" name="status_dokumen">
                                    <option value="valid" {{ old('status_dokumen', $pelaksanaan->status_dokumen) == 'valid' ? 'selected' : '' }}>Valid</option>
                                    <option value="belum_valid" {{ old('status_dokumen', $pelaksanaan->status_dokumen) == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                                    <option value="dalam_review" {{ old('status_dokumen', $pelaksanaan->status_dokumen) == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
                                </select>
                                @error('status_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Kerja</label>
                                <select class="form-select @error('unit_kerja_id') is-invalid @enderror" name="unit_kerja_id">
                                    <option value="">Pilih Unit Kerja</option>
                                    @foreach($unitKerjas as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $pelaksanaan->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_kerja_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                   name="penanggung_jawab" value="{{ old('penanggung_jawab', $pelaksanaan->penanggung_jawab) }}">
                            @error('penanggung_jawab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kegiatan</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" 
                                      rows="4">{{ old('deskripsi', $pelaksanaan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">IKU (Indikator Kinerja Utama)</label>
                            <select class="form-select @error('iku_id') is-invalid @enderror" name="iku_id">
                                <option value="">Pilih IKU</option>
                                @foreach($ikus as $iku)
                                    <option value="{{ $iku->id }}" {{ old('iku_id', $pelaksanaan->iku_id) == $iku->id ? 'selected' : '' }}>
                                        {{ $iku->kode }} - {{ $iku->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('iku_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('spmi.pelaksanaan.show', $pelaksanaan->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i> Update Pelaksanaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Danger Zone -->
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> Zona Bahaya
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-danger mb-1">Hapus Pelaksanaan</h6>
                            <p class="text-muted small mb-0">
                                Tindakan ini akan menghapus permanen data pelaksanaan. Tidak dapat dibatalkan.
                            </p>
                        </div>
                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i> Hapus Pelaksanaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pelaksanaan ini?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini akan menghapus:
                    <ul class="mb-0 mt-2">
                        <li>Data pelaksanaan</li>
                        <li>Semua dokumen terkait</li>
                        <li>Riwayat dan log</li>
                    </ul>
                </div>
                <p class="mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('spmi.pelaksanaan.destroy', $pelaksanaan->id) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        
        .form-control, .form-select {
            padding: 0.625rem 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    // Initialize form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
</script>
@endpush