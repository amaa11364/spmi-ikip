@extends('layouts.app')

@section('title', 'Dokumen Publik SPMI')

@push('styles')
<style>
    .public-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .filter-toggle {
        background: white;
        border: 2px solid var(--primary-brown);
        color: var(--primary-brown);
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .filter-toggle:hover {
        background: var(--primary-brown);
        color: white;
    }
    
    .filter-toggle.active {
        background: var(--primary-brown);
        color: white;
    }
    
    .file-icon-cell {
        width: 50px;
        text-align: center;
    }
    
    .file-icon {
        font-size: 1.5rem;
    }
    
    .actions-cell {
        width: 120px;
        text-align: center;
    }
    
    .no-documents {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
    
    .document-name {
        font-weight: 600;
        color: #495057;
    }
    
    .document-type {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .guest-notice {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffecb5;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 2rem;
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

    .results-info {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-brown);
    }

    /* Responsive table */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .table-custom th {
        background: #f8f9fa;
        border-bottom: 2px solid var(--primary-brown);
        font-weight: 600;
        color: #495057;
    }

    /* Pastikan tabel tetap konsisten meski data sedikit */
    .table-custom {
        min-height: 400px; /* Beri tinggi minimum */
    }

    .table-custom tbody {
        min-height: 300px;
    }

    /* Pastikan lebar kolom konsisten */
    .table-custom th:nth-child(1) { width: 50px; }   /* Icon */
    .table-custom th:nth-child(2) { width: 25%; }    /* Nama Dokumen */
    .table-custom th:nth-child(3) { width: 15%; }    /* Unit Kerja */
    .table-custom th:nth-child(4) { width: 10%; }    /* IKU */
    .table-custom th:nth-child(5) { width: 10%; }    /* Ukuran */
    .table-custom th:nth-child(6) { width: 15%; }    /* Uploader */
    .table-custom th:nth-child(7) { width: 15%; }    /* Tanggal */
    .table-custom th:nth-child(8) { width: 120px; }  /* Aksi */

    /* Mobile card view */
    .mobile-card {
        display: none;
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }

    .mobile-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .mobile-file-icon {
        font-size: 2rem;
        margin-right: 1rem;
        color: var(--primary-brown);
    }

    .mobile-document-info {
        flex: 1;
    }

    .mobile-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    /* Responsive buttons */
    .btn-responsive {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .public-header {
            padding: 1rem;
            text-align: center;
        }
        
        .public-header .text-md-end {
            text-align: center !important;
            margin-top: 1rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .table-desktop {
            display: none;
        }
        
        .mobile-card {
            display: block;
        }
        
        .results-info h5 {
            font-size: 1rem;
        }
        
        .guest-notice .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .guest-notice i {
            margin-bottom: 0.5rem;
            margin-right: 0 !important;
        }
        
        /* Mobile button group */
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .public-header h2 {
            font-size: 1.5rem;
        }
        
        .filter-section .col-md-8,
        .filter-section .col-md-4 {
            margin-bottom: 1rem;
        }
        
        .input-group .btn {
            padding: 0.5rem 1rem;
        }
        
        .mobile-card {
            padding: 0.75rem;
        }
        
        .mobile-file-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
    }

    /* Desktop optimizations */
    @media (min-width: 769px) {
        .mobile-card {
            display: none;
        }
        
        .table-desktop {
            display: table;
        }
    }
</style>
@endpush

@section('content')
<!-- Public Header -->
<div class="public-header">
    <div class="row align-items-center">
        <div class="col-md-8 col-12">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-globe me-2"></i>Dokumen Publik SPMI
            </h2>
            <p class="mb-0">Akses dokumen SPMI yang tersedia untuk umum</p>
        </div>
        <div class="col-md-4 col-12 text-md-end text-center mt-2 mt-md-0">
            @auth
                <a href="{{ route('dokumen-saya') }}" class="btn btn-light btn-responsive me-2 mb-2 mb-md-0">
                    <i class="fas fa-folder me-2"></i>Dokumen Saya
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-responsive">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            @else
                <a href="{{ route('landing.page') }}" class="btn btn-light btn-responsive me-2 mb-2 mb-md-0">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
                <a href="{{ route('masuk') }}" class="btn btn-outline-light btn-responsive">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
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
            <p class="mb-0">Anda dapat melihat daftar dan detail dokumen. Untuk mengunduh atau melihat preview dokumen, silakan login terlebih dahulu.</p>
        </div>
    </div>
</div>
@endguest

<!-- Search & Filter Section -->
<div class="filter-section">
    <form action="{{ route('dokumen-publik.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-8 col-12">
            <label class="form-label fw-semibold">Cari Dokumen</label>
            <div class="input-group">
                <input type="text" class="form-control" name="search" id="searchInput"
                       placeholder="Ketik nama dokumen, jenis, unit kerja, atau IKU..." 
                       value="{{ request('search') }}"
                       autocomplete="off">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </div>
        
        <div class="col-md-4 col-12">
            <button type="button" class="btn filter-toggle" id="filterToggle">
                <i class="fas fa-filter me-2"></i>Filter Lanjutan
            </button>
        </div>

        <!-- Advanced Filters -->
        <div class="col-12 mt-3" id="advancedFilters" style="display: none;">
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold">Unit Kerja</label>
                    <select class="form-select" name="unit_kerja" id="unitKerjaFilter">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold">IKU</label>
                    <select class="form-select" name="iku_id" id="ikuFilter">
                        <option value="">Semua IKU</option>
                        @foreach($ikus as $iku)
                            <option value="{{ $iku->id }}" {{ request('iku_id') == $iku->id ? 'selected' : '' }}>
                                {{ $iku->kode }} - {{ $iku->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 col-12 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-filter me-1"></i>Terapkan Filter
                        </button>
                        @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                            <a href="{{ route('dokumen-publik.index') }}" class="btn btn-outline-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Results Info -->
<div class="results-info">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                    <i class="fas fa-search me-2"></i>
                    Hasil Pencarian
                    @if(request('search'))
                        untuk "{{ request('search') }}"
                    @endif
                    <span class="badge bg-primary ms-2">{{ $dokumens->count() }} dokumen ditemukan</span>
                @else
                    <i class="fas fa-files me-2"></i>
                    Semua Dokumen Publik
                    <span class="badge bg-success ms-2">{{ $dokumens->count() }} dokumen</span>
                @endif
            </h5>
        </div>
        @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
            <a href="{{ route('dokumen-publik.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times me-1"></i>Reset
            </a>
        @endif
    </div>
</div>

<!-- Desktop Table View -->
<div class="table-responsive table-desktop">
    <table class="table table-custom table-hover">
        <thead>
            <tr>
                <th class="file-icon-cell"></th>
                <th>Nama Dokumen</th>
                <th>Unit Kerja</th>
                <th>IKU</th>
                <th>Ukuran</th>
                <th>Uploader</th>
                <th>Tanggal Upload</th>
                <th class="actions-cell">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dokumens as $dokumen)
            <tr>
                <td class="file-icon-cell">
                    <i class="{{ $dokumen->file_icon }} file-icon"></i>
                </td>
                <td>
                    <div class="document-name">{{ $dokumen->nama_dokumen }}</div>
                    <div class="document-type">{{ $dokumen->jenis_dokumen }}</div>
                </td>
                <td>{{ $dokumen->unitKerja->nama }}</td>
                <td>
                    @if($dokumen->iku)
                    <span class="iku-badge" title="{{ $dokumen->iku->nama }}">
                        {{ $dokumen->iku->kode }}
                    </span>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ $dokumen->file_size_formatted }}</td>
                <td>{{ $dokumen->uploader->name }}</td>
                <td>{{ $dokumen->upload_time_ago }}</td>
                <td class="actions-cell">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" 
                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $dokumen->id }}">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        @if($dokumen->is_pdf)
                        <button type="button" class="btn btn-outline-info require-login" 
                                data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                            <i class="fas fa-eye"></i>
                        </button>
                        @endif
                        <button type="button" class="btn btn-outline-success require-login" 
                                data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </td>
            </tr>

            <!-- Detail Modal -->
            <div class="modal fade" id="detailModal{{ $dokumen->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-info-circle me-2"></i>Detail Dokumen
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <i class="{{ $dokumen->file_icon }} fa-4x text-primary"></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="fw-bold">{{ $dokumen->nama_dokumen }}</h5>
                                    <p class="text-muted">{{ $dokumen->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                    
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-folder me-1"></i>
                                                <strong>Unit Kerja:</strong><br>
                                                {{ $dokumen->unitKerja->nama }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            @if($dokumen->iku)
                                            <small class="text-muted d-block">
                                                <i class="fas fa-chart-line me-1"></i>
                                                <strong>IKU:</strong><br>
                                                {{ $dokumen->iku->kode }} - {{ $dokumen->iku->nama }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-file me-1"></i>
                                                <strong>Ukuran:</strong><br>
                                                {{ $dokumen->file_size_formatted }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-user me-1"></i>
                                                <strong>Uploader:</strong><br>
                                                {{ $dokumen->uploader->name }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-calendar me-1"></i>
                                                <strong>Tanggal Upload:</strong><br>
                                                {{ $dokumen->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-clock me-1"></i>
                                                <strong>Jenis:</strong><br>
                                                {{ $dokumen->jenis_dokumen }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            @if($dokumen->is_pdf)
                            <button type="button" class="btn btn-info require-login" 
                                    data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                                <i class="fas fa-eye me-1"></i>Preview
                            </button>
                            @endif
                            <button type="button" class="btn btn-success require-login" 
                                    data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                                <i class="fas fa-download me-1"></i>Download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="8">
                    <div class="no-documents">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">
                            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                                Tidak ada dokumen yang sesuai dengan pencarian
                            @else
                                Belum ada dokumen publik
                            @endif
                        </h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                                Coba ubah kata kunci atau filter pencarian Anda
                            @else
                                Dokumen akan ditampilkan di sini ketika tersedia
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Card View -->
<div class="mobile-card-view">
    @forelse($dokumens as $dokumen)
    <div class="mobile-card">
        <div class="mobile-card-header">
            <i class="{{ $dokumen->file_icon }} mobile-file-icon"></i>
            <div class="mobile-document-info">
                <h6 class="document-name mb-1">{{ $dokumen->nama_dokumen }}</h6>
                <small class="document-type d-block">{{ $dokumen->jenis_dokumen }}</small>
                <div class="mt-1">
                    <small class="text-muted">
                        <i class="fas fa-folder me-1"></i>{{ $dokumen->unitKerja->nama }}
                    </small>
                    @if($dokumen->iku)
                    <small class="text-muted ms-2">
                        <i class="fas fa-chart-line me-1"></i>{{ $dokumen->iku->kode }}
                    </small>
                    @endif
                </div>
                <div class="mt-1">
                    <small class="text-muted">
                        <i class="fas fa-user me-1"></i>{{ $dokumen->uploader->name }}
                    </small>
                    <small class="text-muted ms-2">
                        <i class="fas fa-clock me-1"></i>{{ $dokumen->upload_time_ago }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="mobile-actions">
            <button type="button" class="btn btn-outline-primary btn-sm" 
                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $dokumen->id }}">
                <i class="fas fa-info-circle"></i>
            </button>
            @if($dokumen->is_pdf)
            <button type="button" class="btn btn-outline-info btn-sm require-login" 
                    data-dokumen-id="{{ $dokumen->id }}" data-action="preview">
                <i class="fas fa-eye"></i>
            </button>
            @endif
            <button type="button" class="btn btn-outline-success btn-sm require-login" 
                    data-dokumen-id="{{ $dokumen->id }}" data-action="download">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="no-documents">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">
            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                Tidak ada dokumen yang sesuai dengan pencarian
            @else
                Belum ada dokumen publik
            @endif
        </h5>
        <p class="text-muted">
            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                Coba ubah kata kunci atau filter pencarian Anda
            @else
                Dokumen akan ditampilkan di sini ketika tersedia
            @endif
        </p>
    </div>
    @endforelse
</div>

<!-- Pagination Simple -->
@if($dokumens->hasPages())
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page --}}
                    @if ($dokumens->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">‹</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->previousPageUrl() }}">‹</a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @php
                        $current = $dokumens->currentPage();
                        $last = $dokumens->lastPage();
                        $start = max(1, $current - 1);
                        $end = min($last, $current + 1);
                    @endphp

                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->url(1) }}">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        <li class="page-item {{ $page == $current ? 'active' : '' }}">
                            <a class="page-link" href="{{ $dokumens->url($page) }}">{{ $page }}</a>
                        </li>
                    @endfor

                    @if($end < $last)
                        @if($end < $last - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->url($last) }}">{{ $last }}</a>
                        </li>
                    @endif

                    {{-- Next Page --}}
                    @if ($dokumens->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $dokumens->nextPageUrl() }}">›</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">›</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        <div class="text-center mt-2">
            <small class="text-muted">
                Halaman {{ $dokumens->currentPage() }} dari {{ $dokumens->lastPage() }}
            </small>
        </div>
    </div>
</div>
@endif

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
                    <a href="{{ route('masuk') }}" class="btn btn-primary">
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
        const filterToggle = document.getElementById('filterToggle');
        const advancedFilters = document.getElementById('advancedFilters');

        // Toggle advanced filters
        filterToggle.addEventListener('click', function() {
            advancedFilters.style.display = advancedFilters.style.display === 'none' ? 'block' : 'none';
            filterToggle.classList.toggle('active');
            
            if (filterToggle.classList.contains('active')) {
                filterToggle.innerHTML = '<i class="fas fa-times me-2"></i>Tutup Filter';
            } else {
                filterToggle.innerHTML = '<i class="fas fa-filter me-2"></i>Filter Lanjutan';
            }
        });

        // Auto submit form when filter changes
        document.getElementById('unitKerjaFilter')?.addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('ikuFilter')?.addEventListener('change', function() {
            this.form.submit();
        });
        
        // Show advanced filters if filters are active
        @if(request()->hasAny(['unit_kerja', 'iku_id']))
            advancedFilters.style.display = 'block';
            filterToggle.classList.add('active');
            filterToggle.innerHTML = '<i class="fas fa-times me-2"></i>Tutup Filter';
        @endif

        // Handle login required buttons
        const requireLoginButtons = document.querySelectorAll('.require-login');
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

        requireLoginButtons.forEach(button => {
            button.addEventListener('click', function() {
                loginModal.show();
            });
        });
    });
</script>
@endpush