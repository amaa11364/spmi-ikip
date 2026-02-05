@extends('layouts.main')

@section('title', 'Detail Pengendalian SPMI')

@push('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    
    .detail-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    
    .progress-container {
        height: 10px;
        background-color: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
        margin: 10px 0;
    }
    
    .progress-bar {
        height: 100%;
        background-color: #28a745;
        transition: width 0.3s ease;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-rencana { background-color: #e9ecef; color: #495057; }
    .badge-berjalan { background-color: #cff4fc; color: #055160; }
    .badge-selesai { background-color: #d1e7dd; color: #0a3622; }
    .badge-terverifikasi { background-color: #d1ecf1; color: #0c5460; }
    .badge-tertunda { background-color: #fff3cd; color: #856404; }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('spmi.pengendalian.index') }}">
                    <i class="fas fa-tasks me-1"></i> Pengendalian SPMI
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Detail: {{ $pengendalian->nama_tindakan }}
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="detail-card">
                <!-- Header -->
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-2">{{ $pengendalian->nama_tindakan }}</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="status-badge badge-{{ $pengendalian->status_pelaksanaan }}">
                                    {{ $pengendalian->status_pelaksanaan_label }}
                                </span>
                                <span class="badge bg-secondary">{{ $pengendalian->tahun }}</span>
                                @if($pengendalian->sumber_evaluasi)
                                <span class="badge bg-info">{{ $pengendalian->sumber_evaluasi }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('spmi.pengendalian.edit', $pengendalian->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('upload.spmi-pengendalian', $pengendalian->id) }}" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Dokumen
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="mb-4">
                    <h6 class="mb-2">Progress Pelaksanaan</h6>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">{{ $pengendalian->progress }}%</span>
                        <span class="text-muted">
                            {{ $pengendalian->status_pelaksanaan_label }}
                        </span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $pengendalian->progress }}%; background-color: {{ 
                            $pengendalian->progress >= 100 ? '#28a745' : 
                            ($pengendalian->progress >= 70 ? '#17a2b8' : 
                            ($pengendalian->progress >= 40 ? '#ffc107' : '#dc3545')) 
                        }}"></div>
                    </div>
                </div>

                <!-- Detail Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-user-tie me-2"></i> Penanggung Jawab
                            </h6>
                            <p class="mb-0">{{ $pengendalian->penanggung_jawab }}</p>
                        </div>
                        
                        <div class="info-group mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-calendar me-2"></i> Target Waktu
                            </h6>
                            <p class="mb-0">
                                {{ $pengendalian->target_waktu ? \Carbon\Carbon::parse($pengendalian->target_waktu)->format('d F Y') : 'Belum ditentukan' }}
                                @if($pengendalian->target_waktu && $pengendalian->target_waktu < now())
                                <span class="badge bg-danger ms-2">Terlambat</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-group mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-building me-2"></i> Unit Kerja
                            </h6>
                            <p class="mb-0">{{ $pengendalian->unitKerja->nama ?? 'Tidak ada' }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-group mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-calendar-check me-2"></i> Timeline
                            </h6>
                            <p class="mb-0">
                                @if($pengendalian->tanggal_mulai)
                                    Mulai: {{ \Carbon\Carbon::parse($pengendalian->tanggal_mulai)->format('d/m/Y') }}
                                @else
                                    Belum mulai
                                @endif
                                <br>
                                @if($pengendalian->tanggal_selesai)
                                    Selesai: {{ \Carbon\Carbon::parse($pengendalian->tanggal_selesai)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        
                        <div class="info-group mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-chart-line me-2"></i> IKU
                            </h6>
                            <p class="mb-0">{{ $pengendalian->iku->nama ?? 'Tidak ada' }}</p>
                        </div>
                        
                        <div class="info-group mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-file-alt me-2"></i> Dokumen
                            </h6>
                            <p class="mb-0">
                                <span class="badge bg-{{ $pengendalian->status_dokumen_color }}">
                                    {{ $pengendalian->status_dokumen_label }}
                                </span>
                                @if($allDokumen->count() > 0)
                                <span class="badge bg-primary ms-2">{{ $allDokumen->count() }} dokumen</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Masalah -->
                <div class="info-group mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-exclamation-circle me-2"></i> Deskripsi Masalah
                    </h6>
                    <div class="card card-body bg-light">
                        {{ $pengendalian->deskripsi_masalah }}
                    </div>
                </div>

                <!-- Tindakan Perbaikan -->
                <div class="info-group mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-wrench me-2"></i> Tindakan Perbaikan
                    </h6>
                    <div class="card card-body bg-light">
                        {{ $pengendalian->tindakan_perbaikan }}
                    </div>
                </div>

                <!-- Hasil Verifikasi -->
                @if($pengendalian->hasil_verifikasi)
                <div class="info-group mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-check-double me-2"></i> Hasil Verifikasi
                    </h6>
                    <div class="card card-body bg-success text-white">
                        {{ $pengendalian->hasil_verifikasi }}
                    </div>
                </div>
                @endif

                <!-- Catatan -->
                @if($pengendalian->catatan)
                <div class="info-group mb-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-sticky-note me-2"></i> Catatan
                    </h6>
                    <div class="card card-body bg-light">
                        {{ $pengendalian->catatan }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Dokumen Terkait Section -->
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i> Dokumen Terkait
                                <span class="badge bg-primary ms-2">{{ count($allDokumen) }}</span>
                            </h5>
                        </div>
                        <a href="{{ route('upload.spmi-pengendalian', $pengendalian->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-upload me-1"></i> Upload Dokumen
                        </a>
                    </div>
                </div>

                <!-- Dokumen List -->
                @if(count($allDokumen) > 0)
                    <div class="dokumen-list">
                        @foreach($allDokumen as $dokumen)
                        <div class="dokumen-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex flex-grow-1">
                                    <div class="dokumen-icon">
                                        <i class="{{ $dokumen->file_icon }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $dokumen->nama_dokumen }}</h6>
                                        @if($dokumen->keterangan)
                                        <p class="text-muted small mb-2">{{ $dokumen->keterangan }}</p>
                                        @endif
                                        <div class="dokumen-meta">
                                            <span class="meta-badge">
                                                <i class="far fa-file me-1"></i>
                                                {{ strtoupper($dokumen->file_extension) }}
                                            </span>
                                            <span class="meta-badge">
                                                <i class="fas fa-weight me-1"></i>
                                                {{ $dokumen->file_size_formatted }}
                                            </span>
                                            <span class="meta-badge">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $dokumen->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-group btn-group-sm ms-3">
                                    @if($dokumen->is_pdf)
                                    <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                                       class="btn btn-outline-primary" title="Preview" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                                       class="btn btn-outline-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-excel text-muted fa-2x mb-3"></i>
                        <h5 class="text-muted mb-2">Belum ada dokumen</h5>
                        <p class="text-muted mb-3">Upload dokumen untuk tindakan pengendalian ini</p>
                        <a href="{{ route('upload.spmi-pengendalian', $pengendalian->id) }}" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i> Upload Dokumen Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <!-- Metadata -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Informasi Tambahan
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="far fa-calendar-plus me-2"></i>
                                <strong>Dibuat:</strong> {{ $pengendalian->created_at->format('d/m/Y H:i') }}
                            </small>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="far fa-calendar-check me-2"></i>
                                <strong>Diperbarui:</strong> {{ $pengendalian->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </li>
                        @if($pengendalian->created_by)
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user-plus me-2"></i>
                                <strong>Dibuat oleh:</strong> {{ $pengendalian->created_by_user->name ?? 'System' }}
                            </small>
                        </li>
                        @endif
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-hashtag me-2"></i>
                                <strong>ID:</strong> {{ $pengendalian->id }}
                            </small>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i> Aksi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('spmi.pengendalian.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                        <a href="{{ route('spmi.pengendalian.edit', $pengendalian->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Pengendalian
                        </a>
                        <a href="{{ route('upload.spmi-pengendalian', $pengendalian->id) }}" class="btn btn-success">
                            <i class="fas fa-upload me-1"></i> Upload Dokumen
                        </a>
                        @if(count($allDokumen) > 0)
                        <a href="javascript:void(0)" class="btn btn-info" onclick="downloadAllDokumen()">
                            <i class="fas fa-download me-1"></i> Download Semua
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Dokumen Card -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-file-signature me-2"></i> Status Dokumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-{{ $pengendalian->status_dokumen_color }} p-2 w-100">
                            <i class="fas fa-file-alt me-2"></i>
                            {{ $pengendalian->status_dokumen_label }}
                        </span>
                    </div>
                    @if($pengendalian->status_dokumen != 'valid')
                    <form action="{{ route('spmi.pengendalian.status.update', $pengendalian->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small">Update Status Dokumen</label>
                            <select class="form-select form-select-sm" name="status_dokumen">
                                <option value="valid" {{ $pengendalian->status_dokumen == 'valid' ? 'selected' : '' }}>Valid</option>
                                <option value="belum_valid" {{ $pengendalian->status_dokumen == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                                <option value="dalam_review" {{ $pengendalian->status_dokumen == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control form-control-sm" name="catatan" rows="2" placeholder="Catatan (opsional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Download all documents
    function downloadAllDokumen() {
        alert('Fitur download semua dokumen sedang dikembangkan.');
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush