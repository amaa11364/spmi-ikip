@extends('layouts.main')

@section('title', 'Detail Evaluasi SPMI')

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
    
    .info-group {
        margin-bottom: 1.5rem;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .info-value {
        color: #212529;
        font-size: 1.1rem;
        padding-left: 2rem;
    }
    
    .dokumen-list {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        max-height: 500px;
        overflow-y: auto;
    }
    
    .dokumen-item {
        padding: 1.25rem;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    
    .dokumen-item:hover {
        background: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .dokumen-item:last-child {
        border-bottom: none;
    }
    
    .dokumen-icon {
        font-size: 2rem;
        margin-right: 1rem;
        min-width: 50px;
        text-align: center;
    }
    
    .dokumen-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
    }
    
    .meta-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        background: #f8f9fa;
        color: #6c757d;
        display: flex;
        align-items: center;
    }
    
    .upload-inline-form {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        border: 2px dashed #dee2e6;
        margin-top: 1rem;
        display: none;
    }
    
    .upload-inline-form.show {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('spmi.evaluasi.index') }}">
                    <i class="fas fa-chart-bar me-1"></i> Repository Evaluasi
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Detail: {{ $evaluasi->nama_evaluasi }}
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
                            <h4 class="mb-2">{{ $evaluasi->nama_evaluasi }}</h4>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-info">{{ $evaluasi->kode_evaluasi }}</span>
                                <span class="badge bg-secondary">{{ $evaluasi->tahun }}</span>
                                <span class="badge bg-{{ $evaluasi->status_color }}">{{ $evaluasi->status_label }}</span>
                                <span class="badge bg-{{ $evaluasi->status_dokumen_color }}">{{ $evaluasi->status_dokumen_label }}</span>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('spmi.evaluasi.edit', $evaluasi->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('upload.spmi-evaluasi') }}?evaluasi_id={{ $evaluasi->id }}" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Dokumen
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Detail Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-chart-bar me-2 text-primary"></i> Tipe Evaluasi
                            </div>
                            <div class="info-value">
                                {{ $evaluasi->tipe_evaluasi_label }}
                            </div>
                        </div>
                        
                        @if($evaluasi->periode)
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i> Periode
                            </div>
                            <div class="info-value">
                                {{ $evaluasi->periode }}
                            </div>
                        </div>
                        @endif
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-user-tie me-2 text-primary"></i> Penanggung Jawab
                            </div>
                            <div class="info-value">
                                {{ $evaluasi->penanggung_jawab ?? 'Belum ditentukan' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-building me-2 text-primary"></i> Unit Kerja
                            </div>
                            <div class="info-value">
                                {{ $evaluasi->unitKerja->nama ?? 'Tidak ada' }}
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-chart-line me-2 text-primary"></i> IKU
                            </div>
                            <div class="info-value">
                                {{ $evaluasi->iku->nama ?? 'Tidak ada' }}
                            </div>
                        </div>
                        
                        <div class="info-group">
                            <div class="info-label">
                                <i class="fas fa-calendar me-2 text-primary"></i> Tanggal Evaluasi
                            </div>
                            <div class="info-value">
                                {{ $evaluasi->tanggal_evaluasi ? $evaluasi->tanggal_evaluasi->format('d/m/Y H:i') : 'Belum dievaluasi' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                @if($evaluasi->deskripsi)
                <div class="info-group">
                    <div class="info-label">
                        <i class="fas fa-align-left me-2 text-primary"></i> Deskripsi
                    </div>
                    <div class="info-value">
                        <p class="mb-0">{{ $evaluasi->deskripsi }}</p>
                    </div>
                </div>
                @endif

                <!-- Catatan Verifikasi -->
                @if($evaluasi->catatan_verifikasi)
                <div class="info-group">
                    <div class="info-label">
                        <i class="fas fa-sticky-note me-2 text-primary"></i> Catatan Verifikasi
                    </div>
                    <div class="info-value">
                        <div class="alert alert-info mb-0">
                            {{ $evaluasi->catatan_verifikasi }}
                        </div>
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
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleUploadForm()">
                                <i class="fas fa-paperclip me-1"></i> Upload Cepat
                            </button>
                            <a href="{{ route('upload.spmi-evaluasi') }}?evaluasi_id={{ $evaluasi->id }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Lengkap
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Upload Form -->
                <form action="{{ route('spmi.evaluasi.upload', $evaluasi->id) }}" method="POST" enctype="multipart/form-data" class="upload-inline-form" id="quickUploadForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload File ke <strong>{{ $evaluasi->nama_evaluasi }}</strong></label>
                        <input type="file" class="form-control" name="file_dokumen" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                        <div class="form-text">Maksimal 10MB. Format: PDF, DOC, XLS, PPT, JPG, PNG</div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="keterangan" placeholder="Keterangan (opsional)" value="Dokumen {{ $evaluasi->nama_evaluasi }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-upload me-1"></i> Upload
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="jenis_dokumen" value="Evaluasi SPMI">
                    <input type="hidden" name="upload_source" value="quick_form">
                </form>

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
                                            @if($dokumen->metadata && isset(json_decode($dokumen->metadata, true)['upload_source']))
                                            <span class="badge bg-info">
                                                {{ json_decode($dokumen->metadata, true)['upload_source'] }}
                                            </span>
                                            @endif
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
                                    @if($dokumen->jenis_upload === 'link')
                                    <a href="{{ $dokumen->file_path }}" 
                                       class="btn btn-outline-info" title="Buka Link" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-excel text-muted fa-2x mb-3"></i>
                        <h5 class="text-muted mb-2">Belum ada dokumen</h5>
                        <p class="text-muted mb-3">Upload dokumen pertama untuk evaluasi ini</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary" onclick="toggleUploadForm()">
                                <i class="fas fa-paperclip me-1"></i> Upload Cepat
                            </button>
                            <a href="{{ route('upload.spmi-evaluasi') }}?evaluasi_id={{ $evaluasi->id }}" class="btn btn-outline-primary">
                                <i class="fas fa-upload me-1"></i> Upload Lengkap
                            </a>
                        </div>
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
                                <strong>Dibuat:</strong> {{ $evaluasi->created_at->format('d/m/Y H:i') }}
                            </small>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="far fa-calendar-check me-2"></i>
                                <strong>Diperbarui:</strong> {{ $evaluasi->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </li>
                        @if($evaluasi->tanggal_review)
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-search me-2"></i>
                                <strong>Review Terakhir:</strong> {{ $evaluasi->tanggal_review->format('d/m/Y H:i') }}
                            </small>
                        </li>
                        @endif
                        @if($evaluasi->diperiksa_oleh)
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user-check me-2"></i>
                                <strong>Diperiksa oleh:</strong> {{ $evaluasi->diperiksa_oleh }}
                            </small>
                        </li>
                        @endif
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-folder me-2"></i>
                                <strong>Folder:</strong> 
                                <code class="small">{{ $evaluasi->folder_path }}</code>
                            </small>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-file me-2"></i>
                                <strong>Total Dokumen:</strong> {{ count($allDokumen) }}
                            </small>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-hashtag me-2"></i>
                                <strong>ID:</strong> {{ $evaluasi->id }}
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
                        <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                        </a>
                        <a href="{{ route('spmi.evaluasi.edit', $evaluasi->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Evaluasi
                        </a>
                        <button class="btn btn-primary" onclick="toggleUploadForm()">
                            <i class="fas fa-paperclip me-1"></i> Upload Dokumen Cepat
                        </button>
                        <a href="{{ route('upload.spmi-evaluasi') }}?evaluasi_id={{ $evaluasi->id }}" class="btn btn-success">
                            <i class="fas fa-upload me-1"></i> Upload dengan Detail
                        </a>
                        @if(count($allDokumen) > 0)
                        <a href="javascript:void(0)" class="btn btn-info" onclick="downloadAllDokumen()">
                            <i class="fas fa-download me-1"></i> Download Semua
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Di bagian JavaScript, perbaikan form submission --}}
<script>
    // Toggle quick upload form
    function toggleUploadForm() {
        const form = document.getElementById('quickUploadForm');
        if (form) {
            form.classList.toggle('show');
            
            if (form.classList.contains('show')) {
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }

    // Handle quick upload form submission - Diperbaiki
    document.addEventListener('DOMContentLoaded', function() {
        const quickUploadForm = document.getElementById('quickUploadForm');
        if (quickUploadForm) {
            quickUploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengupload...';
                submitBtn.disabled = true;
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Dokumen berhasil diupload!');
                        location.reload();
                    } else {
                        alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengupload dokumen. Silakan coba lagi.');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush