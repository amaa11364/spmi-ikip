@extends('layouts.main')

@section('title', 'Repository Evaluasi SPMI')

@push('styles')
<style>
    /* ==================== FOLDER HEADER ==================== */
    .folder-header {
        background: linear-gradient(135deg, #4c51bf 0%, #6b46c1 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(76, 81, 191, 0.2);
    }
    
    .folder-icon {
        font-size: 2.5rem;
        margin-right: 15px;
    }
    
    .folder-stats {
        display: flex;
        gap: 20px;
        margin-top: 10px;
    }
    
    .stat-item {
        text-align: center;
        padding: 10px 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        min-width: 100px;
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        display: block;
    }
    
    .stat-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    
    /* ==================== TABLE STYLES ==================== */
    .table-folder {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .table-folder th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 1rem;
    }
    
    .table-folder td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table-folder tbody tr:hover {
        background-color: rgba(76, 81, 191, 0.05);
    }
    
    /* ==================== EVALUASI INFO ==================== */
    .komponen-info {
        display: flex;
        align-items: center;
    }
    
    .komponen-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.2rem;
    }
    
    .komponen-icon.ami { background-color: #e3f2fd; color: #1976d2; }
    .komponen-icon.edom { background-color: #f3e5f5; color: #7b1fa2; }
    .komponen-icon.evaluasi_layanan { background-color: #e8f5e9; color: #388e3c; }
    .komponen-icon.evaluasi_kinerja { background-color: #fff3e0; color: #f57c00; }
    
    .komponen-details h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
    }
    
    .komponen-details small {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    /* ==================== STATUS BADGES ==================== */
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-aktif { background-color: #d4edda; color: #155724; }
    .badge-selesai { background-color: #d1ecf1; color: #0c5460; }
    .badge-nonaktif { background-color: #f8d7da; color: #721c24; }
    .badge-berjalan { background-color: #fff3cd; color: #856404; }
    
    .badge-valid { background-color: #d1ecf1; color: #0c5460; }
    .badge-belum_valid { background-color: #f8d7da; color: #721c24; }
    .badge-dalam_review { background-color: #fff3cd; color: #856404; }
    
    /* ==================== ACTION BUTTONS ==================== */
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        background: white;
        color: #6c757d;
        transition: all 0.2s;
    }
    
    .btn-action:hover {
        background: #f8f9fa;
        color: #495057;
        border-color: #adb5bd;
    }
    
    .btn-view { color: #17a2b8; }
    .btn-edit { color: #ffc107; }
    .btn-upload { color: #28a745; }
    .btn-delete { color: #dc3545; }
    
    /* ==================== UPLOAD INLINE MODAL ==================== */
    .upload-inline-modal {
        position: absolute;
        right: 0;
        top: 100%;
        width: 300px;
        z-index: 1050;
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        border: 1px solid #dee2e6;
        padding: 15px;
        display: none;
    }
    
    .upload-inline-modal.show {
        display: block;
        animation: slideDown 0.3s ease;
    }
    
    .upload-inline-modal .form-control-sm {
        padding: 5px 10px;
        font-size: 0.875rem;
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
    
    /* ==================== FILTER SECTION ==================== */
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    
    .filter-tabs {
        display: flex;
        gap: 5px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }
    
    .filter-tab {
        padding: 8px 16px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .filter-tab:hover,
    .filter-tab.active {
        background: #4c51bf;
        color: white;
        border-color: #4c51bf;
    }
    
    /* ==================== PAGINATION ==================== */
    .pagination-container {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    
    /* ==================== EMPTY STATE ==================== */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }
    
    /* ==================== FORM STYLES ==================== */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4c51bf;
        box-shadow: 0 0 0 0.2rem rgba(76, 81, 191, 0.25);
    }
    
    /* ==================== MODAL STYLES ==================== */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }
    
    /* ==================== BADGES ==================== */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }
    
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    
    .badge.bg-secondary {
        background-color: #6c757d !important;
    }
    
    .badge.bg-success {
        background-color: #28a745 !important;
    }
    
    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .folder-stats {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .stat-item {
            min-width: 80px;
            padding: 8px 12px;
        }
        
        .table-folder {
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 3px;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
        }
        
        .komponen-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }
        
        .upload-inline-modal {
            width: 280px;
            right: -100px;
        }
        
        .filter-tabs {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 5px;
        }
        
        .filter-tab {
            white-space: nowrap;
        }
    }
    
    @media (max-width: 576px) {
        .folder-header {
            padding: 1rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .table-folder th,
        .table-folder td {
            padding: 0.75rem 0.5rem;
        }
        
        .komponen-details h6 {
            font-size: 0.9rem;
        }
        
        .komponen-details small {
            font-size: 0.75rem;
        }
        
        .status-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .modal-content {
            border-radius: 8px;
        }
    }
    
    /* ==================== MOBILE TABLE RESPONSIVE ==================== */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table-folder {
            min-width: 600px;
        }
        
        .action-buttons {
            min-width: 180px;
        }
        
        .komponen-info {
            min-width: 250px;
        }
        
        /* Stack form columns on mobile */
        .row.g-3 > [class*="col-"] {
            margin-bottom: 1rem;
        }
        
        /* Make form inputs full width on mobile */
        .form-control, .form-select {
            font-size: 16px; /* Prevents zoom on iOS */
        }
        
        /* Adjust modal for mobile */
        .modal-dialog {
            max-width: 95%;
            margin: 0.5rem auto;
        }
    }
    
    /* ==================== TABLET OPTIMIZATION ==================== */
    @media (min-width: 768px) and (max-width: 992px) {
        .stat-item {
            min-width: 90px;
        }
        
        .komponen-details h6 {
            font-size: 0.95rem;
        }
        
        .action-buttons {
            gap: 3px;
        }
    }
    
    /* ==================== PRINT STYLES ==================== */
    @media print {
        .folder-header,
        .filter-section,
        .action-buttons,
        .upload-inline-modal,
        .pagination-container {
            display: none !important;
        }
        
        .table-folder {
            box-shadow: none;
            border: 1px solid #dee2e6;
        }
        
        .table-folder th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endpush

@section('content')
{{-- Konten HTML sama seperti sebelumnya --}}
<div class="container-fluid px-3 px-md-4">
    <!-- Folder Header -->
    <div class="folder-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <i class="fas fa-chart-line folder-icon"></i>
                <div>
                    <h4 class="mb-1">Repository Evaluasi SPMI</h4>
                    <p class="mb-0 opacity-75">Sistem manajemen dokumen evaluasi mutu institusi</p>
                </div>
            </div>
            <div>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Evaluasi
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="folder-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $statistics['total'] }}</span>
                <span class="stat-label">Total Evaluasi</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $statistics['aktif'] }}</span>
                <span class="stat-label">Aktif/Selesai</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $statistics['valid'] }}</span>
                <span class="stat-label">Dokumen Valid</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $statistics['belum_valid'] }}</span>
                <span class="stat-label">Belum Valid</span>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('spmi.evaluasi.index') }}" class="filter-tab {{ !request('tipe') || request('tipe') == 'all' ? 'active' : '' }}">
                Semua Evaluasi
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'ami']) }}" class="filter-tab {{ request('tipe') == 'ami' ? 'active' : '' }}">
                Audit Mutu Internal
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'edom']) }}" class="filter-tab {{ request('tipe') == 'edom' ? 'active' : '' }}">
                EDOM
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'evaluasi_layanan']) }}" class="filter-tab {{ request('tipe') == 'evaluasi_layanan' ? 'active' : '' }}">
                Evaluasi Layanan
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'evaluasi_kinerja']) }}" class="filter-tab {{ request('tipe') == 'evaluasi_kinerja' ? 'active' : '' }}">
                Evaluasi Kinerja
            </a>
        </div>
        
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('spmi.evaluasi.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Cari evaluasi..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="tahun">
                        <option value="all">Semua Tahun</option>
                        @foreach($tahunList as $tahunItem)
                            <option value="{{ $tahunItem }}" {{ request('tahun') == $tahunItem ? 'selected' : '' }}>
                                {{ $tahunItem }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="all">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status_dokumen">
                        <option value="all">Status Dokumen</option>
                        <option value="valid" {{ request('status_dokumen') == 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="belum_valid" {{ request('status_dokumen') == 'belum_valid' ? 'selected' : '' }}>Belum Valid</option>
                        <option value="dalam_review" {{ request('status_dokumen') == 'dalam_review' ? 'selected' : '' }}>Dalam Review</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                        <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="table-folder">
        @if($evaluasi->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="35%">Evaluasi</th>
                            <th width="10%">Kode</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Status</th>
                            <th width="15%">Dokumen</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluasi as $item)
                        <tr>
                            <!-- Evaluasi Column -->
                            <td>
                                <div class="komponen-info">
                                    <div class="komponen-icon {{ $item->tipe_evaluasi }}">
                                        @switch($item->tipe_evaluasi)
                                            @case('ami')<i class="fas fa-clipboard-check"></i>@break
                                            @case('edom')<i class="fas fa-user-graduate"></i>@break
                                            @case('evaluasi_layanan')<i class="fas fa-handshake"></i>@break
                                            @case('evaluasi_kinerja')<i class="fas fa-chart-bar"></i>@break
                                            @default<i class="fas fa-chart-line"></i>
                                        @endswitch
                                    </div>
                                    <div class="komponen-details">
                                        <h6>{{ $item->nama_evaluasi }}</h6>
                                        <small>
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $item->tipe_evaluasi_label }}
                                            @if($item->periode)
                                                <span class="ms-2">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $item->periode }}
                                                </span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kode Column -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->kode_evaluasi }}</span>
                            </td>
                            
                            <!-- Tahun Column -->
                            <td>
                                <span class="badge bg-info text-white">{{ $item->tahun }}</span>
                            </td>
                            
                            <!-- Status Column -->
                            <td>
                                @if(in_array($item->status, ['aktif', 'selesai']))
                                    <span class="status-badge badge-aktif">
                                        <i class="fas fa-check-circle me-1"></i> {{ $item->status_label }}
                                    </span>
                                @elseif($item->status == 'nonaktif')
                                    <span class="status-badge badge-nonaktif">
                                        <i class="fas fa-times-circle me-1"></i> {{ $item->status_label }}
                                    </span>
                                @else
                                    <span class="status-badge badge-berjalan">
                                        <i class="fas fa-spinner me-1"></i> {{ $item->status_label }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Dokumen Column -->
                            <td>
                                @if($item->status_dokumen == 'valid')
                                    <span class="status-badge badge-valid">
                                        <i class="fas fa-check me-1"></i> Valid
                                        @if($item->total_dokumen > 0)
                                            <span class="badge bg-success ms-1">{{ $item->total_dokumen }}</span>
                                        @endif
                                    </span>
                                @elseif($item->status_dokumen == 'belum_valid')
                                    <span class="status-badge badge-belum_valid">
                                        <i class="fas fa-clock me-1"></i> Belum Valid
                                    </span>
                                @else
                                    <span class="status-badge badge-dalam_review">
                                        <i class="fas fa-search me-1"></i> Dalam Review
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Action Column -->
                            <td>
                                <div class="action-buttons position-relative">
                                    <!-- View Button -->
                                    <button class="btn-action btn-view" title="Lihat Detail" onclick="viewEvaluasi({{ $item->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button class="btn-action btn-edit" title="Edit" onclick="editEvaluasi({{ $item->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <!-- Upload Button with Inline Modal -->
                                    <div class="position-relative">
                                        <button class="btn-action btn-upload" title="Upload Dokumen" onclick="toggleUploadModal({{ $item->id }})">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        
                                        <div class="upload-inline-modal" id="uploadModal{{ $item->id }}">
                                            <form action="{{ route('spmi.evaluasi.upload', $item->id) }}" method="POST" enctype="multipart/form-data" class="upload-inline-form" id="uploadForm{{ $item->id }}">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label small mb-1">
                                                        <strong>Upload ke:</strong> {{ $item->nama_evaluasi }}
                                                    </label>
                                                    <input type="file" class="form-control form-control-sm" name="file_dokumen" required 
                                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                                    <small class="text-muted d-block">Maksimal 10MB</small>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="keterangan" 
                                                           placeholder="Keterangan (opsional)" value="Dokumen {{ $item->nama_evaluasi }}">
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="jenis_dokumen" 
                                                           placeholder="Jenis dokumen (opsional)" value="Evaluasi SPMI">
                                                </div>
                                                <input type="hidden" name="upload_source" value="inline_modal">
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-sm btn-primary flex-fill" onclick="uploadInlineFile(event, {{ $item->id }})">
                                                        <i class="fas fa-upload me-1"></i> Upload
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleUploadModal({{ $item->id }})">
                                                        Batal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('spmi.evaluasi.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Dokumen Link -->
                                    <a href="{{ route('upload.spmi-evaluasi', ['id' => $item->id]) }}" class="btn-action" title="Upload dengan Konteks">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($evaluasi->hasPages())
            <div class="pagination-container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $evaluasi->firstItem() }} - {{ $evaluasi->lastItem() }} dari {{ $evaluasi->total() }} evaluasi
                    </div>
                    <div>
                        {{ $evaluasi->links() }}
                    </div>
                </div>
            </div>
            @endif
            
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-chart-line"></i>
                <h5 class="mb-2">Repository Kosong</h5>
                <p class="text-muted mb-4">Belum ada data evaluasi SPMI. Mulai dengan menambahkan evaluasi baru.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Evaluasi Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('spmi.evaluasi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Evaluasi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama Evaluasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_evaluasi" required 
                                   placeholder="Contoh: Audit Mutu Internal Program Studi">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipe_evaluasi" required>
                                <option value="">Pilih Tipe</option>
                                <option value="ami">Audit Mutu Internal</option>
                                <option value="edom">Evaluasi Dosen oleh Mahasiswa</option>
                                <option value="evaluasi_layanan">Evaluasi Layanan</option>
                                <option value="evaluasi_kinerja">Evaluasi Kinerja</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" 
                                   value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Periode</label>
                            <input type="text" class="form-control" name="periode" 
                                   placeholder="Contoh: Semester Ganjil 2024">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unit Kerja</label>
                            <select class="form-select" name="unit_kerja_id">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($unitKerjaList as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" class="form-control" name="penanggung_jawab" 
                               placeholder="Nama penanggung jawab">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi singkat tentang evaluasi ini"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Hasil Evaluasi</label>
                        <textarea class="form-control" name="hasil_evaluasi" rows="2" 
                                  placeholder="Ringkasan hasil evaluasi"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rekomendasi</label>
                        <textarea class="form-control" name="rekomendasi" rows="2" 
                                  placeholder="Rekomendasi tindak lanjut"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Target Waktu</label>
                        <input type="date" class="form-control" name="target_waktu">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i> Detail Evaluasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit Evaluasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editModalBody">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // Fungsi untuk upload file via AJAX
    function uploadInlineFile(event, id) {
        event.preventDefault();
        
        const form = document.getElementById('uploadForm' + id);
        const formData = new FormData(form);
        const url = form.action;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
        submitBtn.disabled = true;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Dokumen berhasil diupload!');
                toggleUploadModal(id);
                location.reload();
            } else {
                alert('Gagal: ' + data.message);
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
    }

    // Toggle upload modal
    function toggleUploadModal(id) {
        const modal = document.getElementById('uploadModal' + id);
        const allModals = document.querySelectorAll('.upload-inline-modal');
        
        allModals.forEach(m => {
            if (m.id !== 'uploadModal' + id) {
                m.classList.remove('show');
            }
        });
        
        if (modal.classList.contains('show')) {
            modal.classList.remove('show');
        } else {
            modal.classList.add('show');
        }
        
        if (modal.classList.contains('show')) {
            setTimeout(() => {
                const handleClickOutside = (event) => {
                    if (!modal.contains(event.target) && !event.target.closest('.btn-upload')) {
                        modal.classList.remove('show');
                        document.removeEventListener('click', handleClickOutside);
                    }
                };
                document.addEventListener('click', handleClickOutside);
            }, 100);
        }
    }

    // View Evaluasi Detail
    function viewEvaluasi(id) {
        const url = '{{ route("spmi.evaluasi.ajax.detail", ":id") }}'.replace(':id', id);
        
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#viewModalBody').html(response.html);
                    $('#viewModal').modal('show');
                    
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('#viewModal [title]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Gagal memuat data. Silakan coba lagi.');
            }
        });
    }
    
    // Edit Evaluasi
    function editEvaluasi(id) {
        const url = '{{ route("spmi.evaluasi.ajax.edit-form", ":id") }}'.replace(':id', id);
        
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#editModalBody').html(response.html);
                    $('#editForm').attr('action', '{{ route("spmi.evaluasi.update", ":id") }}'.replace(':id', id));
                    $('#editModal').modal('show');
                    
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('#editModal [title]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Gagal memuat form edit. Silakan coba lagi.');
            }
        });
    }
    
    // Confirm Delete
    function confirmDelete(button) {
        if (confirm('Apakah Anda yakin ingin menghapus evaluasi ini?')) {
            button.closest('.delete-form').submit();
        }
    }

    // Initialize page
    (function() {
        // Handle Edit Form Submission
        $('#editForm').submit(function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            const formData = form.serialize();
            
            $.ajax({
                url: url,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#editModal').modal('hide');
                        alert('Data berhasil diperbarui!');
                        location.reload();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Gagal memperbarui data. Silakan coba lagi.');
                }
            });
        });
        
        // Handle Upload Inline Form Submission
        $('body').on('submit', '.upload-inline-form', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const url = form.attr('action');
            const formData = new FormData(this);
            
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert('Dokumen berhasil diupload!');
                        location.reload();
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Gagal mengupload dokumen. Silakan coba lagi.');
                }
            });
        });
        
        // Make form inputs mobile-friendly
        document.addEventListener('DOMContentLoaded', function() {
            // Set form inputs to have correct font size for mobile
            const inputs = document.querySelectorAll('input[type="text"], input[type="number"], input[type="date"], textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.fontSize = '16px'; // Prevent zoom on iOS
                });
            });
            
            // Make modal scrollable on mobile
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = '0';
                });
                
                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.style.overflow = 'auto';
                });
            });
        });
    })();
</script>
@endpush