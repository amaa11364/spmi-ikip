@extends('layouts.main')

@section('title', 'Program Peningkatan SPMI')

@push('styles')
<style>
    .folder-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
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
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    .program-info {
        display: flex;
        align-items: center;
    }
    
    .program-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.2rem;
    }
    
    .program-icon.strategis { background-color: #f8d7da; color: #721c24; }
    .program-icon.operasional { background-color: #d1ecf1; color: #0c5460; }
    .program-icon.perbaikan { background-color: #fff3cd; color: #856404; }
    .program-icon.pengembangan { background-color: #d4edda; color: #155724; }
    .program-icon.inovasi { background-color: #e2e3e5; color: #383d41; }
    
    .program-details h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
        line-height: 1.3;
    }
    
    .program-details small {
        color: #6c757d;
        font-size: 0.85rem;
        display: block;
        margin-top: 2px;
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-draft { background-color: #e2e3e5; color: #383d41; }
    .badge-disetujui { background-color: #d4edda; color: #155724; }
    .badge-berjalan { background-color: #d1ecf1; color: #0c5460; }
    .badge-selesai { background-color: #cce5ff; color: #004085; }
    .badge-ditunda { background-color: #fff3cd; color: #856404; }
    .badge-dibatalkan { background-color: #f8d7da; color: #721c24; }
    
    .badge-valid { background-color: #d1ecf1; color: #0c5460; }
    .badge-belum_valid { background-color: #f8d7da; color: #721c24; }
    .badge-dalam_review { background-color: #fff3cd; color: #856404; }
    
    .progress-container {
        width: 100%;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 8px;
        border-radius: 10px;
        text-align: center;
        font-size: 10px;
        line-height: 8px;
        color: white;
        transition: width 0.6s ease;
    }
    
    .progress-success { background-color: #28a745; }
    .progress-primary { background-color: #007bff; }
    .progress-warning { background-color: #ffc107; }
    .progress-danger { background-color: #dc3545; }
    
    .progress-text {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 3px;
        display: block;
    }
    
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
        background: #28a745;
        color: white;
        border-color: #28a745;
    }
    
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
        
        .program-icon {
            width: 32px;
            height: 32px;
            font-size: 1rem;
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
                    <h4 class="mb-1">Program Peningkatan SPMI</h4>
                    <p class="mb-0 opacity-75">Manajemen program peningkatan mutu berkelanjutan</p>
                </div>
            </div>
            <div>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Program
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="folder-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $totalPeningkatan }}</span>
                <span class="stat-label">Total Program</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $peningkatanAktif }}</span>
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
    <div class="card mb-4">
        <div class="card-body">
            <!-- Filter Tabs -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('spmi.peningkatan.index') }}" class="filter-tab {{ !request('tipe') || request('tipe') == 'all' ? 'active' : '' }}">
                    Semua Program
                </a>
                <a href="{{ route('spmi.peningkatan.index', ['tipe' => 'strategis']) }}" class="filter-tab {{ request('tipe') == 'strategis' ? 'active' : '' }}">
                    Strategis
                </a>
                <a href="{{ route('spmi.peningkatan.index', ['tipe' => 'operasional']) }}" class="filter-tab {{ request('tipe') == 'operasional' ? 'active' : '' }}">
                    Operasional
                </a>
                <a href="{{ route('spmi.peningkatan.index', ['tipe' => 'perbaikan']) }}" class="filter-tab {{ request('tipe') == 'perbaikan' ? 'active' : '' }}">
                    Perbaikan
                </a>
                <a href="{{ route('spmi.peningkatan.index', ['tipe' => 'pengembangan']) }}" class="filter-tab {{ request('tipe') == 'pengembangan' ? 'active' : '' }}">
                    Pengembangan
                </a>
                <a href="{{ route('spmi.peningkatan.index', ['tipe' => 'inovasi']) }}" class="filter-tab {{ request('tipe') == 'inovasi' ? 'active' : '' }}">
                    Inovasi
                </a>
            </div>
            
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('spmi.peningkatan.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" placeholder="Cari program..." 
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
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditunda" {{ request('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
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
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="fas fa-filter me-2"></i> Filter
                            </button>
                            <a href="{{ route('spmi.peningkatan.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table -->
    <div class="table-folder">
        @if($peningkatan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="30%">Program Peningkatan</th>
                            <th width="10%">Kode</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Status</th>
                            <th width="15%">Progress</th>
                            <th width="10%">Dokumen</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peningkatan as $item)
                        <tr>
                            <!-- Program Column -->
                            <td>
                                <div class="program-info">
                                    <div class="program-icon {{ $item->tipe_peningkatan }}">
                                        @switch($item->tipe_peningkatan)
                                            @case('strategis')<i class="fas fa-flag"></i>@break
                                            @case('operasional')<i class="fas fa-cogs"></i>@break
                                            @case('perbaikan')<i class="fas fa-tools"></i>@break
                                            @case('pengembangan')<i class="fas fa-chart-line"></i>@break
                                            @case('inovasi')<i class="fas fa-lightbulb"></i>@break
                                            @default<i class="fas fa-file-alt"></i>
                                        @endswitch
                                    </div>
                                    <div class="program-details">
                                        <h6>{{ $item->nama_program }}</h6>
                                        <small>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $item->unitKerja->nama ?? 'Tidak ada unit kerja' }}
                                        </small>
                                        @if($item->penanggung_jawab)
                                        <small>
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $item->penanggung_jawab }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kode Column -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->kode_peningkatan }}</span>
                            </td>
                            
                            <!-- Tahun Column -->
                            <td>
                                <span class="badge bg-info text-white">{{ $item->tahun }}</span>
                            </td>
                            
                            <!-- Status Column -->
                            <td>
                                @if($item->status == 'disetujui')
                                    <span class="status-badge badge-disetujui">
                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif($item->status == 'berjalan')
                                    <span class="status-badge badge-berjalan">
                                        <i class="fas fa-play-circle me-1"></i> Berjalan
                                    </span>
                                @elseif($item->status == 'selesai')
                                    <span class="status-badge badge-selesai">
                                        <i class="fas fa-flag-checkered me-1"></i> Selesai
                                    </span>
                                @elseif($item->status == 'ditunda')
                                    <span class="status-badge badge-ditunda">
                                        <i class="fas fa-pause-circle me-1"></i> Ditunda
                                    </span>
                                @elseif($item->status == 'dibatalkan')
                                    <span class="status-badge badge-dibatalkan">
                                        <i class="fas fa-times-circle me-1"></i> Dibatalkan
                                    </span>
                                @else
                                    <span class="status-badge badge-draft">
                                        <i class="fas fa-edit me-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Progress Column -->
                            <td>
                                <div class="progress-container">
                                    <div class="progress-bar progress-{{ $item->progress_color }}" 
                                         style="width: {{ $item->progress }}%">
                                    </div>
                                </div>
                                <span class="progress-text text-{{ $item->progress_color }}">
                                    {{ $item->progress }}%
                                </span>
                                @if($item->anggaran > 0)
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-coins me-1"></i>{{ $item->anggaran_formatted }}
                                </small>
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
                                <div class="action-buttons">
                                    <!-- View Button -->
                                    <button class="btn-action btn-view" title="Lihat Detail" onclick="viewPeningkatan({{ $item->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Edit Button -->
                                    <button class="btn-action btn-edit" title="Edit" onclick="editPeningkatan({{ $item->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <!-- Upload Button -->
                                    <a href="{{ route('upload.spmi-penetapan', $item->id) }}" class="btn-action btn-upload" title="Upload Dokumen">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('spmi.peningkatan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($peningkatan->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $peningkatan->firstItem() }} - {{ $peningkatan->lastItem() }} dari {{ $peningkatan->total() }} program
                    </div>
                    <div>
                        {{ $peningkatan->links() }}
                    </div>
                </div>
            </div>
            @endif
            
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-chart-line"></i>
                <h5 class="mb-2">Belum Ada Program Peningkatan</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan program peningkatan baru.</p>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Program Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('spmi.peningkatan.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Program Peningkatan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama Program <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_program" required 
                                   placeholder="Contoh: Peningkatan Kualitas Pembelajaran Daring">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipe Program <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipe_peningkatan" required>
                                <option value="">Pilih Tipe</option>
                                <option value="strategis">Strategis</option>
                                <option value="operasional">Operasional</option>
                                <option value="perbaikan">Perbaikan</option>
                                <option value="pengembangan">Pengembangan</option>
                                <option value="inovasi">Inovasi</option>
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
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="berjalan">Berjalan</option>
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
                                   placeholder="Nama penanggung jawab">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Anggaran (Rp)</label>
                            <input type="number" class="form-control" name="anggaran" 
                                   placeholder="0" min="0" step="1000">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Progress (%)</label>
                            <input type="range" class="form-range" name="progress" min="0" max="100" step="5" value="0">
                            <div class="d-flex justify-content-between">
                                <small>0%</small>
                                <small id="progressValue">0%</small>
                                <small>100%</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Program</label>
                        <textarea class="form-control" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi lengkap program peningkatan"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tanggal_selesai">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
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
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i> Detail Program Peningkatan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i> Edit Program Peningkatan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editModalBody">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
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
    // Progress slider update
    document.querySelector('input[name="progress"]').addEventListener('input', function(e) {
        document.getElementById('progressValue').textContent = e.target.value + '%';
    });

    // View Peningkatan Detail
    function viewPeningkatan(id) {
        const url = '{{ route("spmi.peningkatan.ajax.detail", ":id") }}'.replace(':id', id);
        
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#viewModalBody').html(response.html);
                    $('#viewModal').modal('show');
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Gagal memuat data. Silakan coba lagi.');
            }
        });
    }
    
    // Edit Peningkatan
    function editPeningkatan(id) {
        const url = '{{ route("spmi.peningkatan.ajax.edit-form", ":id") }}'.replace(':id', id);
        
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#editModalBody').html(response.html);
                    $('#editForm').attr('action', '{{ route("spmi.peningkatan.update", ":id") }}'.replace(':id', id));
                    $('#editModal').modal('show');
                    
                    // Update progress value display
                    const progressSlider = $('#editModalBody').find('input[name="progress"]');
                    const progressValue = $('#editModalBody').find('#progressValue');
                    progressSlider.on('input', function() {
                        progressValue.text(this.value + '%');
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Gagal memuat form edit. Silakan coba lagi.');
            }
        });
    }
    
    // Confirm Delete
    function confirmDelete(button) {
        if (confirm('Apakah Anda yakin ingin menghapus program peningkatan ini?')) {
            button.closest('.delete-form').submit();
        }
    }

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
            error: function() {
                alert('Gagal memperbarui data. Silakan coba lagi.');
            }
        });
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush