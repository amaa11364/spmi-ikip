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

    .guest-notice {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffecb5;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    .access-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        border-left: 4px solid var(--primary-brown);
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

        .guest-notice .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .guest-notice i {
            margin-bottom: 0.5rem;
            margin-right: 0 !important;
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
            padding: 0.5rem;
        }

        .btn-lg {
            padding: 0.5rem 0.75rem;
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
            @auth
                <a href="{{ route('dokumen-saya') }}" class="btn btn-light btn-lg me-2 mb-2 mb-md-0">
                    <i class="fas fa-folder me-2"></i>Dokumen Saya
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            @else
                <a href="{{ route('dokumen-publik.index') }}" class="btn btn-light btn-lg me-2 mb-2 mb-md-0">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <a href="{{ route('landing.page') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Guest Notice -->
@guest
<div class="guest-notice">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle fa-2x text-warning me-3"></i>
        <div>
            <h6 class="fw-bold mb-1">Anda sedang mengakses sebagai tamu</h6>
            <p class="mb-0">Anda dapat melihat detail dokumen. Untuk mengunduh atau melihat preview dokumen, silakan login terlebih dahulu.</p>
        </div>
    </div>
</div>
@endguest

<div class="row">
    <!-- Document Information -->
    <div class="col-lg-8 col-12">
        <div class="document-info-card">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <i class="{{ $dokumen->file_icon }} document-icon-large"></i>
                    <div class="mt-3">
                        <span class="badge bg-{{ $dokumen->is_public ? 'success' : 'secondary' }}">
                            {{ $dokumen->is_public ? 'Publik' : 'Privat' }}
                        </span>
                    </div>
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
                        <span class="info-value flex-grow-1">{{ $dokumen->unitKerja->nama ?? '-' }}</span>
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
                        <span class="info-label">Format File:</span>
                        <span class="info-value flex-grow-1 text-uppercase">{{ $dokumen->file_extension }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Ukuran File:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->file_size_formatted }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Uploader:</span>
                        <span class="info-value flex-grow-1">{{ $dokumen->uploader->name ?? '-' }}</span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Tanggal Upload:</span>
                        <span class="info-value flex-grow-1">
                            {{ $dokumen->created_at->format('d F Y H:i') }}
                            <small class="text-muted d-block">({{ $dokumen->upload_time_ago }})</small>
                        </span>
                    </div>
                    
                    <div class="info-item d-flex">
                        <span class="info-label">Diperbarui:</span>
                        <span class="info-value flex-grow-1">
                            {{ $dokumen->updated_at->format('d F Y H:i') }}
                        </span>
                    </div>

                    @if($dokumen->file_exists)
                    <div class="info-item d-flex">
                        <span class="info-label">Status File:</span>
                        <span class="info-value flex-grow-1">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Tersedia
                            </span>
                        </span>
                    </div>
                    @else
                    <div class="info-item d-flex">
                        <span class="info-label">Status File:</span>
                        <span class="info-value flex-grow-1">
                            <span class="badge bg-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>Tidak Tersedia
                            </span>
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- File Preview Section (for PDF only) -->
        @if($dokumen->is_pdf && auth()->check() && $dokumen->file_exists)
        <div class="document-info-card">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-eye me-2"></i>Preview Dokumen
            </h5>
            <div class="ratio ratio-16x9">
                <iframe src="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                        frameborder="0" 
                        allowfullscreen>
                </iframe>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                   class="btn btn-success btn-lg action-btn" 
                   download="{{ $dokumen->file_name }}">
                    <i class="fas fa-download me-2"></i>Download Sekarang
                </a>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Action Panel -->
    <div class="col-lg-4 col-12">
        <div class="document-info-card">
            <h5 class="fw-bold mb-4 text-center">
                <i class="fas fa-download me-2"></i>Akses Dokumen
            </h5>
            
            <div class="d-grid gap-3">
                @auth
                    <!-- Untuk user yang sudah login -->
                    @if($dokumen->file_exists)
                        @if($dokumen->is_pdf)
                        <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                           class="btn btn-info btn-lg action-btn" 
                           target="_blank">
                            <i class="fas fa-eye me-2"></i>Preview Dokumen
                        </a>
                        @endif
                        
                        <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                           class="btn btn-success btn-lg action-btn" 
                           download="{{ $dokumen->file_name }}">
                            <i class="fas fa-download me-2"></i>Download Dokumen
                        </a>
                    @else
                        <button type="button" class="btn btn-danger btn-lg action-btn" disabled>
                            <i class="fas fa-exclamation-triangle me-2"></i>File Tidak Tersedia
                        </button>
                    @endif
                {{-- Di show.blade.php bagian Action Panel --}}
@else
    <!-- Untuk tamu/guest -->
    @if($dokumen->is_pdf)
    <a href="{{ route('masuk') }}?redirect={{ urlencode(route('dokumen-publik.show', $dokumen->id)) }}&action=preview&source=public" 
       class="btn btn-info btn-lg action-btn">
        <i class="fas fa-eye me-2"></i>Preview Dokumen
    </a>
    @endif
    
    <a href="{{ route('masuk') }}?redirect={{ urlencode(route('dokumen-publik.show', $dokumen->id)) }}&action=download&source=public" 
       class="btn btn-success btn-lg action-btn">
        <i class="fas fa-download me-2"></i>Download Dokumen
    </a>
@endauth
                
                <a href="{{ route('dokumen-publik.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-home me-2"></i>Ke Dashboard
                    </a>
                @else
                    <a href="{{ route('landing.page') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-home me-2"></i>Ke Beranda
                    </a>
                @endauth
            </div>
            
            <div class="access-info">
                <h6 class="fw-semibold mb-2">
                    <i class="fas fa-info-circle me-2"></i>Informasi Akses
                </h6>
                @auth
                    <p class="small text-muted mb-2">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Anda sudah login sebagai <strong>{{ auth()->user()->name }}</strong>
                    </p>
                    @if($dokumen->file_exists)
                        <p class="small text-muted mb-0">
                            Anda dapat mengunduh atau melihat preview dokumen ini.
                        </p>
                    @else
                        <p class="small text-danger mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            File dokumen tidak tersedia di server.
                        </p>
                    @endif
                @else
                    <p class="small text-muted mb-0">
                        Untuk mengunduh atau melihat preview dokumen, Anda perlu login terlebih dahulu.
                        Dokumen ini tersedia untuk umum, tetapi akses download dibatasi untuk pengguna terdaftar.
                    </p>
                @endauth
            </div>
        </div>

        <!-- Related Documents -->
        @if($relatedDocuments->count() > 0)
        <div class="document-info-card">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-link me-2"></i>Dokumen Terkait
            </h5>
            <div class="list-group">
                @foreach($relatedDocuments as $related)
                <a href="{{ route('dokumen-publik.show', $related->id) }}" 
                   class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="{{ $related->file_icon }} me-3 text-primary"></i>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ Str::limit($related->nama_dokumen, 40) }}</h6>
                        <small class="text-muted">{{ $related->unitKerja->nama ?? '-' }} • {{ $related->upload_time_ago }}</small>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Login Required Modal -->
<div class="modal fade login-modal" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock me-2"></i>Login Diperlukan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-user-lock fa-3x text-warning mb-3"></i>
                <h5 class="mb-3">Akses Terbatas</h5>
                <p class="text-muted mb-4">
                    Untuk mengakses fitur preview dan download dokumen, Anda perlu login terlebih dahulu.
                    Silakan login untuk melanjutkan.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('masuk') }}" class="btn btn-primary" 
                       onclick="sessionStorage.setItem('login_redirect', window.location.href)">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dokumen Detail Manager initialized');
    
    // Inisialisasi modal login
    const loginModalElement = document.getElementById('loginModal');
    let loginModal = null;
    
    if (loginModalElement) {
        loginModal = new bootstrap.Modal(loginModalElement);
        console.log('Login modal initialized');
    }
    
    // FIX UTAMA: Event delegation untuk semua tombol require-login
    document.addEventListener('click', function(e) {
        const requireLoginBtn = e.target.closest('.require-login');
        if (requireLoginBtn && loginModal) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Tombol require-login diklik');
            
            // Simpan URL untuk redirect setelah login
            sessionStorage.setItem('login_redirect', window.location.href);
            
            // Tampilkan modal login
            loginModal.show();
            return false;
        }
    });
    
    // Handle direct download links
    document.querySelectorAll('a[download]').forEach(link => {
        link.addEventListener('click', function() {
            console.log('Download started:', this.getAttribute('download'));
        });
    });
    
    console.log('Dokumen Detail Manager ready!');
});
</script>
@endpush