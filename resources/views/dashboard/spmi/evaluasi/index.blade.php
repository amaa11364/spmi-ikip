@extends('layouts.main')

@section('title', 'Evaluasi SPMI')

@push('styles')
<style>
    /* Folder Header */
    .folder-header {
        background: linear-gradient(135deg, #2c7873 0%, #004445 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(44, 120, 115, 0.2);
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
        background-color: rgba(44, 120, 115, 0.05);
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
    
    .komponen-icon.internal { background-color: #e3f2fd; color: #1976d2; }
    .komponen-icon.eksternal { background-color: #f3e5f5; color: #7b1fa2; }
    .komponen-icon.berkala { background-color: #e8f5e9; color: #388e3c; }
    .komponen-icon.khusus { background-color: #fff3e0; color: #f57c00; }
    
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
    
    .badge-draft { background-color: #e9ecef; color: #495057; }
    .badge-proses { background-color: #fff3cd; color: #856404; }
    .badge-selesai { background-color: #d4edda; color: #155724; }
    .badge-ditunda { background-color: #f8d7da; color: #721c24; }
    
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
        background: #2c7873;
        color: white;
        border-color: #2c7873;
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
                <i class="fas fa-chart-line folder-icon"></i>
                <div>
                    <h4 class="mb-1">Evaluasi SPMI</h4>
                    <p class="mb-0 opacity-75">Sistem evaluasi dan penilaian mutu institusi</p>
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
                <span class="stat-number">{{ $totalEvaluasi }}</span>
                <span class="stat-label">Total Evaluasi</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $evaluasiSelesai }}</span>
                <span class="stat-label">Selesai</span>
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
            <a href="{{ route('spmi.evaluasi.index') }}" class="filter-tab {{ !request('tipe') || request('tipe') == 'all' ? 'active' : '' }}">
                Semua Tipe
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'internal']) }}" class="filter-tab {{ request('tipe') == 'internal' ? 'active' : '' }}">
                Internal
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'eksternal']) }}" class="filter-tab {{ request('tipe') == 'eksternal' ? 'active' : '' }}">
                Eksternal
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'berkala']) }}" class="filter-tab {{ request('tipe') == 'berkala' ? 'active' : '' }}">
                Berkala
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'khusus']) }}" class="filter-tab {{ request('tipe') == 'khusus' ? 'active' : '' }}">
                Khusus
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
                    <select class="form-select" name="periode">
                        <option value="all">Semua Periode</option>
                        @foreach($periodeList as $periodeItem)
                            <option value="{{ $periodeItem }}" {{ request('periode') == $periodeItem ? 'selected' : '' }}>
                                {{ $periodeItem }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="all">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditunda" {{ request('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
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
    
    <!-- Section: Ringkasan Evaluasi -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-chart-bar me-2"></i> Ringkasan Evaluasi
            </h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-3">
                    <div class="p-3 border rounded">
                        <div class="text-primary fs-2 fw-bold">{{ $statistics['selesai'] }}</div>
                        <div class="text-muted">Evaluasi Selesai</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="p-3 border rounded">
                        <div class="text-warning fs-2 fw-bold">{{ $statistics['proses'] }}</div>
                        <div class="text-muted">Dalam Proses</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="p-3 border rounded">
                        <div class="text-success fs-2 fw-bold">{{ $statistics['valid'] }}</div>
                        <div class="text-muted">Dokumen Valid</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="p-3 border rounded">
                        <div class="text-danger fs-2 fw-bold">{{ $statistics['draft'] }}</div>
                        <div class="text-muted">Dalam Draft</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="table-folder">
        @if($evaluasi->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="35%">Evaluasi Komponen</th>
                            <th width="10%">Kode</th>
                            <th width="10%">Periode</th>
                            <th width="15%">Status</th>
                            <th width="15%">Dokumen</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluasi as $item)
                        <tr>
                            <!-- Komponen Column -->
                            <td>
                                <div class="komponen-info">
                                    <div class="komponen-icon {{ $item->tipe_evaluasi }}">
                                        @switch($item->tipe_evaluasi)
                                            @case('internal')<i class="fas fa-search"></i>@break
                                            @case('eksternal')<i class="fas fa-user-tie"></i>@break
                                            @case('berkala')<i class="fas fa-calendar-check"></i>@break
                                            @case('khusus')<i class="fas fa-star"></i>@break
                                            @default<i class="fas fa-chart-line"></i>
                                        @endswitch
                                    </div>
                                    <div class="komponen-details">
                                        <h6>{{ $item->nama_komponen }}</h6>
                                        <small>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $item->unitKerja->nama ?? 'Tidak ada unit kerja' }}
                                            • {{ $item->tahun }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kode Column -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->kode_evaluasi }}</span>
                            </td>
                            
                            <!-- Periode Column -->
                            <td>
                                <span class="badge bg-info text-white">{{ $item->periode }}</span>
                            </td>
                            
                            <!-- Status Column -->
                            <td>
                                @if($item->status == 'selesai')
                                    <span class="status-badge badge-selesai">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </span>
                                @elseif($item->status == 'proses')
                                    <span class="status-badge badge-proses">
                                        <i class="fas fa-spinner me-1"></i> Proses
                                    </span>
                                @elseif($item->status == 'ditunda')
                                    <span class="status-badge badge-ditunda">
                                        <i class="fas fa-pause-circle me-1"></i> Ditunda
                                    </span>
                                @else
                                    <span class="status-badge badge-draft">
                                        <i class="fas fa-edit me-1"></i> Draft
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
                                                        <strong>Upload ke:</strong> {{ $item->nama_komponen }}
                                                    </label>
                                                    <input type="file" class="form-control form-control-sm" name="file_dokumen" required 
                                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                                    <small class="text-muted d-block">Maksimal 10MB</small>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="keterangan" 
                                                           placeholder="Keterangan (opsional)" value="Dokumen Evaluasi {{ $item->nama_komponen }}">
                                                </div>
                                                <div class="mb-2">
                                                    <input type="text" class="form-control form-control-sm" name="jenis_dokumen" 
                                                           placeholder="Jenis dokumen (opsional)" value="Laporan Evaluasi">
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
                                    <a href="{{ route('upload.spmi-evaluasi', $item->id) }}" class="btn-action" title="Upload dengan Konteks">
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
                <h5 class="mb-2">Data Evaluasi Kosong</h5>
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
                            <label class="form-label">Nama Komponen Evaluasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_komponen" required 
                                   placeholder="Contoh: Evaluasi Kinerja Akademik">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipe_evaluasi" required>
                                <option value="">Pilih Tipe</option>
                                <option value="internal">Evaluasi Internal</option>
                                <option value="eksternal">Evaluasi Eksternal</option>
                                <option value="berkala">Evaluasi Berkala</option>
                                <option value="khusus">Evaluasi Khusus</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" 
                                   value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Periode <span class="text-danger">*</span></label>
                            <select class="form-select" name="periode" required>
                                <option value="">Pilih Periode</option>
                                <option value="Semester I">Semester I</option>
                                <option value="Semester II">Semester II</option>
                                <option value="Triwulan I">Triwulan I</option>
                                <option value="Triwulan II">Triwulan II</option>
                                <option value="Triwulan III">Triwulan III</option>
                                <option value="Triwulan IV">Triwulan IV</option>
                                <option value="Tahunan">Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="proses">Dalam Proses</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditunda">Ditunda</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unit Kerja</label>
                            <select class="form-select" name="unit_kerja_id">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($unitKerjaList as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" name="penanggung_jawab" 
                                   placeholder="Nama penanggung jawab evaluasi">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Evaluasi</label>
                            <input type="date" class="form-control" name="tanggal_evaluasi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">IKU Terkait</label>
                            <select class="form-select" name="iku_id">
                                <option value="">Pilih IKU</option>
                                @foreach($unitKerjaList as $unit)
                                    <!-- IKU options would be loaded via AJAX -->
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Hasil Evaluasi (Ringkasan)</label>
                        <textarea class="form-control" name="hasil_evaluasi" rows="2" 
                                  placeholder="Ringkasan hasil evaluasi"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rekomendasi</label>
                        <textarea class="form-control" name="rekomendasi" rows="2" 
                                  placeholder="Rekomendasi tindak lanjut"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Lengkap</label>
                        <textarea class="form-control" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi lengkap tentang evaluasi ini"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Evaluasi
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
                    <i class="fas fa-chart-line me-2"></i> Detail Evaluasi
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

    // View Evaluasi Detail
    function viewEvaluasi(id) {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.evaluasi.ajax.detail", ":id") }}'.replace(':id', id);
        
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
    
    // Edit Evaluasi
    function editEvaluasi(id) {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.evaluasi.ajax.edit-form", ":id") }}'.replace(':id', id);
        
        jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    jQuery('#editModalBody').html(response.html);
                    jQuery('#editForm').attr('action', '{{ route("spmi.evaluasi.update", ":id") }}'.replace(':id', id));
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
        if (confirm('Apakah Anda yakin ingin menghapus evaluasi ini?')) {
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