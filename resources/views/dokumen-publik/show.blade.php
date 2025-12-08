@extends('layouts.main')

@section('title', $dokumen->nama_dokumen . ' - Dokumen Publik SPMI')

@push('styles')
<style>
    .detail-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .document-info-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        margin-bottom: 2rem;
    }
    
    .document-icon-large {
        font-size: 4rem;
        color: var(--primary-brown);
    }
    
    .info-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 120px;
    }
    
    .info-value {
        color: #6c757d;
    }
    
    .iku-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        font-size: 0.875rem;
        padding: 6px 12px;
        border-radius: 8px;
    }
    
    .action-btn {
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: scale(1.05);
    }
    
    .login-modal .modal-content {
        border-radius: 15px;
        border: none;
    }
    
    .login-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .detail-header {
            padding: 1rem;
            text-align: center;
        }
        
        .detail-header .text-md-end {
            text-align: center !important;
            margin-top: 1rem;
        }
        
        .document-info-card {
            padding: 1rem;
        }
        
        .document-icon-large {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .info-label {
            min-width: auto;
            margin-bottom: 0.25rem;
        }
        
        .action-btn {
            font-size: 0.9rem;
            padding: 0.75rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .detail-header h2 {
            font-size: 1.5rem;
        }
        
        .document-info-card {
            padding: 0.75rem;
        }
        
        .action-btn {
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Detail Header -->
<div class="detail-header">
    <div class="row align-items-center">
        <div class="col-md-8 col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dokumen-publik.index') }}" class="text-white text-decoration-none">
                            <i class="fas fa-globe me-1"></i>Dokumen Publik
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Detail Dokumen</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-2">{{ $dokumen->nama_dokumen }}</h2>
            <p class="mb-0">{{ $dokumen->deskripsi ?: 'Tidak ada deskripsi' }}</p>
        </div>
        <div class="col-md-4 col-12 text-md-end text-center mt-2 mt-md-0">
            <a href="{{ route('dokumen-publik.index') }}" class="btn btn-light btn-lg me-2 mb-2 mb-md-0">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <a href="{{ route('landing.page') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-home me-2"></i>Beranda
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Document Information -->
    <div class="col-lg-8 col-12">
        <div class="document-info-card">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <i class="{{ $dokumen->file_icon }} document-icon-large"></i>
                </div>
                <div class="col-md-9">
                    <h4 class="fw-bold mb-3">Informasi Dokumen</h4>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Nama Dokumen:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->nama_dokumen }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Deskripsi:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->deskripsi ?: 'Tidak ada deskripsi' }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Unit Kerja:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->unitKerja->nama }}</span>
                    </div>
                    
                    @if($dokumen->iku)
                    <div class="info-item d-flex">
                        <span class="info-label">IKU:</span>
                        <div class="info-value flex-grow-1">
                            <span class="iku-badge me-2">{{ $dokumen->iku->kode }}</span>
                            <span>{{ $dokumen->iku->nama }}</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Jenis Dokumen:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->jenis_dokumen }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Ukuran File:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->file_size_formatted }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Uploader:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->uploader->name }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Tanggal Upload:</span>
                        <span class="info-value flex-grow-1">
                            {{ $dokumen->created_at->format('d F Y H:i') }}
                        </span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Diperbarui:</span>
                        <span class="info-value flex-grow-1">
                            {{ $dokumen->updated_at->format('d F Y H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Panel -->
    <div class="col-lg-4 col-12">
        <div class="document-info-card">
            <h5 class="fw-bold mb-4 text-center">
                <i class="fas fa-download me-2"></i>Akses Dokumen
            </h5>
            
            <div class="d-grid gap-3">
                @if($dokumen->is_pdf)
                <button type="button" class="btn btn-info btn-lg action-btn require-login" 
                        data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                    <i class="fas fa-eye me-2"></i>Preview Dokumen
                </button>
                @endif
                
                <button type="button" class="btn btn-success btn-lg action-btn require-login" 
                        data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                    <i class="fas fa-download me-2"></i>Download Dokumen
                </button>
                
                <a href="{{ route('dokumen-publik.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
            
            <div class="mt-4 p-3 bg-light rounded">
                <h6 class="fw-semibold mb-2">
                    <i class="fas fa-info-circle me-2"></i>Informasi Akses
                </h6>
                <p class="small text-muted mb-0">
                    Untuk mengunduh atau melihat preview dokumen, Anda perlu login terlebih dahulu