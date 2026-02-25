@extends('layouts.main')

@section('title', 'Detail Program Peningkatan')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('spmi.peningkatan.index') }}">Peningkatan SPMI</a></li>
                    <li class="breadcrumb-item active">Detail Program</li>
                </ol>
            </nav>
            <h4 class="mb-0">{{ $peningkatan->nama_program }}</h4>
        </div>
        <div>
            <a href="{{ route('spmi.peningkatan.edit', $peningkatan->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit
            </a>
            <a href="{{ route('spmi.peningkatan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <!-- Program Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Program</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kode Program</th>
                                    <td>: {{ $peningkatan->kode_peningkatan }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Program</th>
                                    <td>: 
                                        <span class="badge bg-{{ $peningkatan->tipe_peningkatan == 'strategis' ? 'danger' : 'warning' }}">
                                            {{ $peningkatan->tipe_peningkatan_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tahun</th>
                                    <td>: {{ $peningkatan->tahun }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: 
                                        <span class="badge bg-{{ $peningkatan->status_color }}">
                                            {{ $peningkatan->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status Dokumen</th>
                                    <td>: 
                                        <span class="badge bg-{{ $peningkatan->status_dokumen_color }}">
                                            {{ $peningkatan->status_dokumen_label }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Unit Kerja</th>
                                    <td>: {{ $peningkatan->unitKerja->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>IKU</th>
                                    <td>: {{ $peningkatan->iku->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Penanggung Jawab</th>
                                    <td>: {{ $peningkatan->penanggung_jawab ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Anggaran</th>
                                    <td>: {{ $peningkatan->anggaran_formatted }}</td>
                                </tr>
                                <tr>
                                    <th>Realisasi</th>
                                    <td>: {{ $peningkatan->realisasi_anggaran_formatted }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Progress -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span><strong>Progress:</strong></span>
                            <strong>{{ $peningkatan->progress }}%</strong>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-{{ $peningkatan->progress_color }} progress-bar-striped progress-bar-animated" 
                                 style="width: {{ $peningkatan->progress }}%">
                                {{ $peningkatan->progress }}%
                            </div>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    @if($peningkatan->tanggal_mulai || $peningkatan->tanggal_selesai)
                    <div class="mt-4">
                        <h6 class="mb-3"><i class="fas fa-calendar-alt me-2"></i> Timeline</h6>
                        <div class="row">
                            @if($peningkatan->tanggal_mulai)
                            <div class="col-md-6">
                                <div class="card border-start border-primary border-3">
                                    <div class="card-body py-2">
                                        <small class="text-muted">Tanggal Mulai</small>
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($peningkatan->tanggal_mulai)->format('d F Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($peningkatan->tanggal_selesai)
                            <div class="col-md-6">
                                <div class="card border-start border-success border-3">
                                    <div class="card-body py-2">
                                        <small class="text-muted">Tanggal Selesai</small>
                                        <h6 class="mb-0">{{ \Carbon\Carbon::parse($peningkatan->tanggal_selesai)->format('d F Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Deskripsi & Catatan -->
            <div class="row">
                @if($peningkatan->deskripsi)
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-align-left me-2"></i> Deskripsi</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $peningkatan->deskripsi }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($peningkatan->catatan_evaluasi)
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i> Catatan Evaluasi</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $peningkatan->catatan_evaluasi }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Dokumen Section -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i> Dokumen Terkait</h6>
                    <span class="badge bg-light text-dark">{{ $allDokumen->count() }} dokumen</span>
                </div>
                <div class="card-body p-0">
                    @if($allDokumen->count() > 0)
                        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                            @foreach($allDokumen as $dokumen)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $dokumen->file_icon }} me-3"></i>
                                        <div>
                                            <h6 class="mb-1" style="font-size: 0.9rem;">{{ $dokumen->nama_dokumen }}</h6>
                                            <small class="text-muted">
                                                {{ $dokumen->created_at->format('d/m/Y') }} • 
                                                {{ $dokumen->file_size_formatted }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                                           class="btn btn-outline-primary" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if($dokumen->is_pdf)
                                        <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                                           class="btn btn-outline-info" title="Preview" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-excel text-muted fa-3x mb-3"></i>
                            <p class="text-muted mb-3">Belum ada dokumen</p>
                            <a href="{{ route('upload.spmi-penetapan', ['id' => $peningkatan->id]) }}" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Dokumen
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('upload.spmi-penetapan', ['id' => $peningkatan->id]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Dokumen Baru
                    </a>
                </div>
            </div>
            
            <!-- Status Dokumen Update -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i> Update Status Dokumen</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('spmi.peningkatan.status.update', $peningkatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Status Dokumen</label>
                            <select class="form-select" name="status_dokumen">
                                <option value="valid" {{ $peningkatan->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
                                <option value="belum_valid" {{ $peningkatan->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                                <option value="dalam_review" {{ $peningkatan->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="3" placeholder="Tambahkan catatan evaluasi...">{{ $peningkatan->catatan_evaluasi }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="card">
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="text-muted">
                        <i class="far fa-calendar-plus me-1"></i> Dibuat: {{ $peningkatan->created_at->format('d F Y H:i') }}
                    </small>
                    @if($peningkatan->updated_at != $peningkatan->created_at)
                    <small class="text-muted ms-3">
                        <i class="far fa-calendar-check me-1"></i> Diperbarui: {{ $peningkatan->updated_at->format('d F Y H:i') }}
                    </small>
                    @endif
                </div>
                <div>
                    <a href="{{ route('spmi.peningkatan.edit', $peningkatan->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('spmi.peningkatan.destroy', $peningkatan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(this)">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(button) {
        if (confirm('Apakah Anda yakin ingin menghapus program peningkatan ini?')) {
            button.closest('form').submit();
        }
    }
</script>
@endpush