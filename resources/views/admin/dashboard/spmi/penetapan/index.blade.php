@extends('layouts.main')

@section('title', 'Repository Penetapan SPMI')

@push('styles')
<style>
    /* Folder Header */
    .folder-header {
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.2);
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
        background-color: rgba(139, 69, 19, 0.05);
    }
    
    /* Komponen Info */
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
    
    .komponen-icon.pengelolaan { background-color: #e3f2fd; color: #1976d2; }
    .komponen-icon.organisasi { background-color: #f3e5f5; color: #7b1fa2; }
    .komponen-icon.pelaksanaan { background-color: #e8f5e9; color: #388e3c; }
    .komponen-icon.evaluasi { background-color: #fff3e0; color: #f57c00; }
    .komponen-icon.pengendalian { background-color: #fce4ec; color: #c2185b; }
    .komponen-icon.peningkatan { background-color: #e8eaf6; color: #303f9f; }
    
    .komponen-details h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
    }
    
    .komponen-details small {
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
    .badge-nonaktif { background-color: #f8d7da; color: #721c24; }
    .badge-revisi { background-color: #fff3cd; color: #856404; }
    
    .badge-valid { background-color: #d1ecf1; color: #0c5460; }
    .badge-belum_valid { background-color: #f8d7da; color: #721c24; }
    .badge-dalam_review { background-color: #fff3cd; color: #856404; }
    
    /* Action Buttons */
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
        background: #8B4513;
        color: white;
        border-color: #8B4513;
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
                <i class="fas fa-folder-open folder-icon"></i>
                <div>
                    <h4 class="mb-1">Repository Penetapan SPMI</h4>
                    <p class="mb-0 opacity-75">Sistem manajemen dokumen penetapan mutu institusi</p>
                </div>
            </div>
            <div>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Penetapan
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="folder-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $totalPenetapan }}</span>
                <span class="stat-label">Total Komponen</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $penetapanAktif }}</span>
                <span class="stat-label">Aktif</span>
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
            <a href="{{ route('spmi.penetapan.index') }}" class="filter-tab {{ !request('tipe') || request('tipe') == 'all' ? 'active' : '' }}">
                Semua Komponen
            </a>
            <a href="{{ route('spmi.penetapan.index', ['tipe' => 'pengelolaan']) }}" class="filter-tab {{ request('tipe') == 'pengelolaan' ? 'active' : '' }}">
                Pengelolaan
            </a>
            <a href="{{ route('spmi.penetapan.index', ['tipe' => 'organisasi']) }}" class="filter-tab {{ request('tipe') == 'organisasi' ? 'active' : '' }}">
                Organisasi
            </a>
            <a href="{{ route('spmi.penetapan.index', ['tipe' => 'pelaksanaan']) }}" class="filter-tab {{ request('tipe') == 'pelaksanaan' ? 'active' : '' }}">
                Pelaksanaan
            </a>
            <a href="{{ route('spmi.penetapan.index', ['tipe' => 'evaluasi']) }}" class="filter-tab {{ request('tipe') == 'evaluasi' ? 'active' : '' }}">
                Evaluasi
            </a>
            <a href="{{ route('spmi.penetapan.index', ['tipe' => 'pengendalian']) }}" class="filter-tab {{ request('tipe') == 'pengendalian' ? 'active' : '' }}">
                Pengendalian
            </a>
            <a href="{{ route('spmi.penetapan.index', ['tipe' => 'peningkatan']) }}" class="filter-tab {{ request('tipe') == 'peningkatan' ? 'active' : '' }}">
                Peningkatan
            </a>
        </div>
        
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('spmi.penetapan.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Cari komponen..." 
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
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
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
                        <a href="{{ route('spmi.penetapan.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="table-folder">
        @if($penetapan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="35%">Komponen</th>
                            <th width="10%">Kode</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Status</th>
                            <th width="15%">Dokumen</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penetapan as $item)
                        <tr>
                            <!-- Komponen Column -->
                            <td>
                                <div class="komponen-info">
                                    <div class="komponen-icon {{ $item->tipe_penetapan }}">
                                        @switch($item->tipe_penetapan)
                                            @case('pengelolaan')<i class="fas fa-cogs"></i>@break
                                            @case('organisasi')<i class="fas fa-sitemap"></i>@break
                                            @case('pelaksanaan')<i class="fas fa-play-circle"></i>@break
                                            @case('evaluasi')<i class="fas fa-chart-line"></i>@break
                                            @case('pengendalian')<i class="fas fa-tasks"></i>@break
                                            @case('peningkatan')<i class="fas fa-chart-line"></i>@break
                                            @default<i class="fas fa-file-alt"></i>
                                        @endswitch
                                    </div>
                                    <div class="komponen-details">
                                        <h6>{{ $item->nama_komponen }}</h6>
                                        <small>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $item->unitKerja->nama ?? 'Tidak ada unit kerja' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kode Column -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->kode_penetapan }}</span>
                            </td>
                            
                            <!-- Tahun Column -->
                            <td>
                                <span class="badge bg-info text-white">{{ $item->tahun }}</span>
                            </td>
                            
                            <!-- Status Column -->
                            <td>
                                @if($item->status == 'aktif')
                                    <span class="status-badge badge-aktif">
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </span>
                                @elseif($item->status == 'nonaktif')
                                    <span class="status-badge badge-nonaktif">
                                        <i class="fas fa-times-circle me-1"></i> Nonaktif
                                    </span>
                                @else
                                    <span class="status-badge badge-revisi">
                                        <i class="fas fa-edit me-1"></i> Revisi
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Dokumen Column -->
                            <td>
                                @if($item->status_dokumen == 'valid')
                                    <span class="status-badge badge-valid">
                                        <i class="fas fa-check me-1"></i> Valid
                                        @if($item->dokumen_count > 0)
                                            <span class="badge bg-success ms-1">{{ $item->dokumen_count }}</span>
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
                                    <button class="btn-action btn-view" title="Lihat Detail" onclick="viewPenetapan({{ $item->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button class="btn-action btn-edit" title="Edit" onclick="editPenetapan({{ $item->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <!-- Upload Button with Inline Modal -->
                                    <div class="position-relative">
                                        <button class="btn-action btn-upload" title="Upload Dokumen" onclick="toggleUploadModal({{ $item->id }})">
                                            <i class="fas fa-upload"></i>
                                        </button>
                                        
                                        <div class="upload-inline-modal" id="uploadModal{{ $item->id }}">
                                            <form action="{{ route('spmi.penetapan.upload', $item->id) }}" method="POST" enctype="multipart/form-data" class="upload-inline-form" id="uploadForm{{ $item->id }}">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label small mb-1">
                                                        <strong>Upload ke:</strong> {{ $item->nama_komponen }}
                                                    </label>
                                                    <input type="file" class="form-control form-control-sm" name="file_dokumen" required 
                                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                                    <small class="text-muted d-block">Maksimal 10MB</small>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="keterangan" 
                                                           placeholder="Keterangan (opsional)" value="Dokumen {{ $item->nama_komponen }}">
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="jenis_dokumen" 
                                                           placeholder="Jenis dokumen (opsional)" value="Penetapan SPMI">
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
                                    <form action="{{ route('spmi.penetapan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Dokumen Link -->
                                    <a href="{{ route('upload.spmi-penetapan', $item->id) }}" class="btn-action" title="Upload dengan Konteks">
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
            @if($penetapan->hasPages())
            <div class="pagination-container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $penetapan->firstItem() }} - {{ $penetapan->lastItem() }} dari {{ $penetapan->total() }} komponen
                    </div>
                    <div>
                        {{ $penetapan->links() }}
                    </div>
                </div>
            </div>
            @endif
            
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <h5 class="mb-2">Repository Kosong</h5>
                <p class="text-muted mb-4">Belum ada data penetapan SPMI. Mulai dengan menambahkan komponen baru.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Penetapan Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('spmi.penetapan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Penetapan Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Komponen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_komponen" required 
                               placeholder="Contoh: Kebijakan Mutu Institusi">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Penetapan <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipe_penetapan" required>
                                <option value="">Pilih Tipe</option>
                                <option value="pengelolaan">Pengelolaan SPMI</option>
                                <option value="organisasi">Organisasi SPMI</option>
                                <option value="pelaksanaan">Pelaksanaan SPMI</option>
                                <option value="evaluasi">Evaluasi SPMI</option>
                                <option value="pengendalian">Pengendalian SPMI</option>
                                <option value="peningkatan">Peningkatan SPMI</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" 
                                   value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="revisi">Revisi</option>
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
                                  placeholder="Deskripsi singkat tentang komponen ini"></textarea>
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
                    <i class="fas fa-file-alt me-2"></i> Detail Penetapan
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
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit Penetapan
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
<!-- Load jQuery sebelum script lainnya -->
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
                toggleUploadModal(id); // Tutup modal
                location.reload(); // Refresh halaman untuk update count
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

    // View Penetapan Detail
    function viewPenetapan(id) {
        // Cek jika jQuery tersedia
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.penetapan.ajax.detail", ":id") }}'.replace(':id', id);
        
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
    
    // Edit Penetapan
    function editPenetapan(id) {
        // Cek jika jQuery tersedia
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.penetapan.ajax.edit-form", ":id") }}'.replace(':id', id);
        
        jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    jQuery('#editModalBody').html(response.html);
                    jQuery('#editForm').attr('action', '{{ route("spmi.penetapan.update", ":id") }}'.replace(':id', id));
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
        if (confirm('Apakah Anda yakin ingin menghapus penetapan ini?')) {
            button.closest('.delete-form').submit();
        }
    }

    // Initialize page
    (function() {
        function initPage() {
            // Cek jika jQuery sudah dimuat
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