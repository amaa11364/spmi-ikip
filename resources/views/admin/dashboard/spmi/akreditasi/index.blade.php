@extends('layouts.main')

@section('title', 'Repository Akreditasi SPMI')

@push('styles')
<style>
    /* Folder Header */
    .folder-header {
        background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.2);
    }
    
    .folder-icon {
        font-size: 2.5rem;
        margin-right: 15px;
    }
    
    .folder-stats {
        display: flex;
        gap: 20px;
        margin-top: 10px;
        flex-wrap: wrap;
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
    
    /* Table Styles */
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
        background-color: rgba(111, 66, 193, 0.05);
    }
    
    /* Akreditasi Info */
    .akreditasi-info {
        display: flex;
        align-items: center;
    }
    
    .akreditasi-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.2rem;
    }
    
    .akreditasi-icon.institusi { background-color: #e3f2fd; color: #1976d2; }
    .akreditasi-icon.program_studi { background-color: #e8f5e9; color: #388e3c; }
    .akreditasi-icon.fakultas { background-color: #f3e5f5; color: #7b1fa2; }
    .akreditasi-icon.laboratorium { background-color: #fff3e0; color: #f57c00; }
    .akreditasi-icon.lainnya { background-color: #e8eaf6; color: #303f9f; }
    
    .akreditasi-details h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
    }
    
    .akreditasi-details small {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-aktif { background-color: #d4edda; color: #155724; }
    .badge-berjalan { background-color: #fff3cd; color: #856404; }
    .badge-selesai { background-color: #d1ecf1; color: #0c5460; }
    .badge-tidak_akreditasi { background-color: #f8d7da; color: #721c24; }
    .badge-kadaluarsa { background-color: #e2e3e5; color: #383d41; }
    
    .badge-valid { background-color: #d1ecf1; color: #0c5460; }
    .badge-belum_valid { background-color: #f8d7da; color: #721c24; }
    .badge-dalam_review { background-color: #fff3cd; color: #856404; }
    
    .badge-peringkat {
        font-weight: bold;
        min-width: 30px;
        text-align: center;
    }
    .peringkat-A { background-color: #28a745; color: white; }
    .peringkat-B { background-color: #17a2b8; color: white; }
    .peringkat-C { background-color: #ffc107; color: black; }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
        flex-wrap: wrap;
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
    .btn-dokumen { color: #6f42c1; }
    
    /* Upload Inline Modal */
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
    
    /* Filter Section */
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
        background: #6f42c1;
        color: white;
        border-color: #6f42c1;
    }
    
    /* Pagination */
    .pagination-container {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    
    /* Empty State */
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .folder-stats {
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
        
        .akreditasi-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
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
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Folder Header -->
    <div class="folder-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <i class="fas fa-award folder-icon"></i>
                <div>
                    <h4 class="mb-1">Repository Akreditasi SPMI</h4>
                    <p class="mb-0 opacity-75">Sistem manajemen dokumen akreditasi institusi</p>
                </div>
            </div>
            <div>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Akreditasi
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="folder-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $totalAkreditasi }}</span>
                <span class="stat-label">Total Akreditasi</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $akreditasiAktif }}</span>
                <span class="stat-label">Aktif</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $akreditasiBerjalan }}</span>
                <span class="stat-label">Sedang Berjalan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $dokumenValid }}</span>
                <span class="stat-label">Dokumen Valid</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $dokumenBelumValid }}</span>
                <span class="stat-label">Belum Valid</span>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('spmi.akreditasi.index') }}" class="filter-tab {{ !request('jenis_akreditasi') || request('jenis_akreditasi') == 'all' ? 'active' : '' }}">
                Semua Jenis
            </a>
            <a href="{{ route('spmi.akreditasi.index', ['jenis_akreditasi' => 'institusi']) }}" class="filter-tab {{ request('jenis_akreditasi') == 'institusi' ? 'active' : '' }}">
                Institusi
            </a>
            <a href="{{ route('spmi.akreditasi.index', ['jenis_akreditasi' => 'program_studi']) }}" class="filter-tab {{ request('jenis_akreditasi') == 'program_studi' ? 'active' : '' }}">
                Program Studi
            </a>
            <a href="{{ route('spmi.akreditasi.index', ['jenis_akreditasi' => 'fakultas']) }}" class="filter-tab {{ request('jenis_akreditasi') == 'fakultas' ? 'active' : '' }}">
                Fakultas
            </a>
            <a href="{{ route('spmi.akreditasi.index', ['jenis_akreditasi' => 'laboratorium']) }}" class="filter-tab {{ request('jenis_akreditasi') == 'laboratorium' ? 'active' : '' }}">
                Laboratorium
            </a>
        </div>
        
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('spmi.akreditasi.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Cari akreditasi..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="tahun">
                        <option value="all">Semua Tahun</option>
                        @foreach($tahunList as $tahunItem)
                            <option value="{{ $tahunItem->tahun }}" {{ request('tahun') == $tahunItem->tahun ? 'selected' : '' }}>
                                {{ $tahunItem->tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="all">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="tidak_akreditasi" {{ request('status') == 'tidak_akreditasi' ? 'selected' : '' }}>Tidak Akreditasi</option>
                        <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
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
                        <a href="{{ route('spmi.akreditasi.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Advanced Filters (Collapsible) -->
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <select class="form-select" name="lembaga_akreditasi">
                        <option value="all">Semua Lembaga</option>
                        @foreach($lembagaList as $lembaga)
                            <option value="{{ $lembaga->lembaga_akreditasi }}" {{ request('lembaga_akreditasi') == $lembaga->lembaga_akreditasi ? 'selected' : '' }}>
                                {{ $lembaga->lembaga_akreditasi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="peringkat">
                        <option value="all">Semua Peringkat</option>
                        @foreach($peringkatList as $peringkat)
                            <option value="{{ $peringkat->peringkat }}" {{ request('peringkat') == $peringkat->peringkat ? 'selected' : '' }}>
                                {{ $peringkat->peringkat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="unit_kerja_id">
                        <option value="all">Semua Unit Kerja</option>
                        @foreach($unitKerjaList as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="table-folder">
        @if($akreditasi->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="30%">Akreditasi</th>
                            <th width="10%">Kode</th>
                            <th width="15%">Informasi</th>
                            <th width="15%">Status</th>
                            <th width="15%">Dokumen</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($akreditasi as $item)
                        <tr>
                            <!-- Akreditasi Column -->
                            <td>
                                <div class="akreditasi-info">
                                    <div class="akreditasi-icon {{ $item->jenis_akreditasi }}">
                                        @switch($item->jenis_akreditasi)
                                            @case('institusi')<i class="fas fa-university"></i>@break
                                            @case('program_studi')<i class="fas fa-graduation-cap"></i>@break
                                            @case('fakultas')<i class="fas fa-building"></i>@break
                                            @case('laboratorium')<i class="fas fa-flask"></i>@break
                                            @default<i class="fas fa-award"></i>
                                        @endswitch
                                    </div>
                                    <div class="akreditasi-details">
                                        <h6>{{ $item->judul_akreditasi }}</h6>
                                        <small>
                                            <i class="fas fa-landmark me-1"></i>
                                            {{ $item->lembaga_akreditasi }}
                                            @if($item->no_sertifikat)
                                            • {{ $item->no_sertifikat }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kode Column -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->kode_akreditasi }}</span>
                            </td>
                            
                            <!-- Informasi Column -->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="badge bg-info text-white mb-1">{{ $item->tahun }}</span>
                                    @if($item->peringkat)
                                    <span class="status-badge badge-peringkat peringkat-{{ $item->peringkat }}">
                                        {{ $item->peringkat }}
                                    </span>
                                    @endif
                                    @if($item->skor)
                                    <small class="text-muted mt-1">{{ $item->skor }} poin</small>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status Column -->
                            <td>
                                @if($item->status == 'aktif')
                                    <span class="status-badge badge-aktif">
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </span>
                                    @if($item->masa_berlaku)
                                    <small class="d-block text-success mt-1">{{ $item->masa_berlaku }}</small>
                                    @endif
                                @elseif($item->status == 'berjalan')
                                    <span class="status-badge badge-berjalan">
                                        <i class="fas fa-spinner me-1"></i> Berjalan
                                    </span>
                                @elseif($item->status == 'selesai')
                                    <span class="status-badge badge-selesai">
                                        <i class="fas fa-flag-checkered me-1"></i> Selesai
                                    </span>
                                @elseif($item->status == 'tidak_akreditasi')
                                    <span class="status-badge badge-tidak_akreditasi">
                                        <i class="fas fa-times-circle me-1"></i> Tidak Akreditasi
                                    </span>
                                @else
                                    <span class="status-badge badge-kadaluarsa">
                                        <i class="fas fa-clock me-1"></i> Kadaluarsa
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
                                    <button class="btn-action btn-view" title="Lihat Detail" onclick="viewAkreditasi({{ $item->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button class="btn-action btn-edit" title="Edit" onclick="editAkreditasi({{ $item->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <!-- Upload Button with Inline Modal -->
                                    <div class="position-relative">
                                        <button class="btn-action btn-upload" title="Upload Dokumen" onclick="toggleUploadModal({{ $item->id }})">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        
                                        <div class="upload-inline-modal" id="uploadModal{{ $item->id }}">
                                            <form action="{{ route('spmi.akreditasi.upload', $item->id) }}" method="POST" enctype="multipart/form-data" class="upload-inline-form" id="uploadForm{{ $item->id }}">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label small mb-1">
                                                        <strong>Upload ke:</strong> {{ $item->judul_akreditasi }}
                                                    </label>
                                                    <input type="file" class="form-control form-control-sm" name="file_dokumen" required 
                                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                                    <small class="text-muted d-block">Maksimal 10MB</small>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="keterangan" 
                                                           placeholder="Keterangan (opsional)" value="Dokumen {{ $item->judul_akreditasi }}">
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="jenis_dokumen" 
                                                           placeholder="Jenis dokumen (opsional)" value="Akreditasi SPMI">
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
                                    <form action="{{ route('spmi.akreditasi.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Dokumen Link -->
                                    <a href="{{ route('upload.spmi-akreditasi', $item->id) }}" class="btn-action" title="Upload dengan Konteks">
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
            @if($akreditasi->hasPages())
            <div class="pagination-container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $akreditasi->firstItem() }} - {{ $akreditasi->lastItem() }} dari {{ $akreditasi->total() }} akreditasi
                    </div>
                    <div>
                        {{ $akreditasi->links() }}
                    </div>
                </div>
            </div>
            @endif
            
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-award"></i>
                <h5 class="mb-2">Repository Kosong</h5>
                <p class="text-muted mb-4">Belum ada data akreditasi SPMI. Mulai dengan menambahkan akreditasi baru.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Akreditasi Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('spmi.akreditasi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Akreditasi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Akreditasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul_akreditasi" required 
                               placeholder="Contoh: Akreditasi Program Studi Teknik Informatika">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Akreditasi <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_akreditasi" required>
                                <option value="">Pilih Jenis</option>
                                <option value="institusi">Akreditasi Institusi</option>
                                <option value="program_studi">Akreditasi Program Studi</option>
                                <option value="fakultas">Akreditasi Fakultas</option>
                                <option value="laboratorium">Akreditasi Laboratorium</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lembaga Akreditasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lembaga_akreditasi" required 
                                   placeholder="Contoh: BAN-PT, LAM Teknik, dll">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" 
                                   value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Peringkat</label>
                            <select class="form-select" name="peringkat">
                                <option value="">Pilih Peringkat</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="Unggul">Unggul</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Skor</label>
                            <input type="number" class="form-control" name="skor" step="0.01" min="0" max="100" 
                                   placeholder="Contoh: 85.5">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Akreditasi</label>
                            <input type="date" class="form-control" name="tanggal_akreditasi">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Berlaku</label>
                            <input type="date" class="form-control" name="tanggal_berlaku">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" name="tanggal_kadaluarsa">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="berjalan">Sedang Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="tidak_akreditasi">Tidak Terakreditasi</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Sertifikat</label>
                            <input type="text" class="form-control" name="no_sertifikat" 
                                   placeholder="Nomor sertifikat akreditasi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab" 
                                   placeholder="Nama penanggung jawab">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi singkat tentang akreditasi ini"></textarea>
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
                    <i class="fas fa-award me-2"></i> Detail Akreditasi
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
                        <i class="fas fa-edit me-2"></i> Edit Akreditasi
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script>
    // Fungsi untuk upload file via AJAX
    function uploadInlineFile(event, id) {
        event.preventDefault();
        
        const form = document.getElementById('uploadForm' + id);
        const formData = new FormData(form);
        const url = form.action;
        
        // Tampilkan loading
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
        
        // Hide all other modals
        allModals.forEach(m => {
            if (m.id !== 'uploadModal' + id) {
                m.classList.remove('show');
            }
        });
        
        // Toggle current modal
        if (modal.classList.contains('show')) {
            modal.classList.remove('show');
        } else {
            modal.classList.add('show');
        }
        
        // Close modal when clicking outside
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

    // View Akreditasi Detail
    function viewAkreditasi(id) {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.akreditasi.ajax.detail", ":id") }}'.replace(':id', id);
        
        jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    jQuery('#viewModalBody').html(response.html);
                    jQuery('#viewModal').modal('show');
                    
                    // Re-initialize tooltips di modal
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
    
    // Edit Akreditasi
    function editAkreditasi(id) {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.akreditasi.ajax.edit-form", ":id") }}'.replace(':id', id);
        
        jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    jQuery('#editModalBody').html(response.html);
                    jQuery('#editForm').attr('action', '{{ route("spmi.akreditasi.update", ":id") }}'.replace(':id', id));
                    jQuery('#editModal').modal('show');
                    
                    // Re-initialize tooltips di modal
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
        if (confirm('Apakah Anda yakin ingin menghapus akreditasi ini?')) {
            button.closest('.delete-form').submit();
        }
    }

    // Initialize page
    (function() {
        function initPage() {
            if (typeof jQuery === 'undefined') {
                console.error('jQuery belum dimuat!');
                setTimeout(initPage, 100);
                return;
            }
            
            console.log('jQuery loaded, version:', jQuery.fn.jquery);
            
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Handle Edit Form Submission dengan jQuery
            jQuery('#editForm').submit(function(e) {
                e.preventDefault();
                
                const form = jQuery(this);
                const url = form.attr('action');
                const formData = form.serialize();
                
                jQuery.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            jQuery('#editModal').modal('hide');
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
            
            // Handle Upload Inline Form Submission dengan jQuery
            jQuery('body').on('submit', '.upload-inline-form', function(e) {
                e.preventDefault();
                
                const form = jQuery(this);
                const url = form.attr('action');
                const formData = new FormData(this);
                
                jQuery.ajax({
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
        }
        
        // Tunggu DOM siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPage);
        } else {
            initPage();
        }
    })();
</script>
@endpush