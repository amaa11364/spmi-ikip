@extends('layouts.main')

@section('title', 'Repository Evaluasi SPMI')
<link rel="stylesheet" href="{{ asset('css/evaluasi.css') }}">
@section('content')

<div class="container-fluid px-3 px-md-4">
    <!-- Folder Header -->
    <div class="folder-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <i class="fas fa-chart-bar folder-icon"></i>
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
                <span class="stat-number">{{ $totalEvaluasi }}</span>
                <span class="stat-label">Total Evaluasi</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $evaluasiAktif }}</span>
                <span class="stat-label">Aktif/Selesai</span>
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
                Semua Evaluasi
            </a>
            <a href="{{ route('spmi.evaluasi.index', ['tipe' => 'ami']) }}" class="filter-tab {{ request('tipe') == 'ami' ? 'active' : '' }}">
                AMI
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
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
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
                                <div class="evaluasi-info">
                                    <div class="evaluasi-icon {{ $item->tipe_evaluasi }}">
                                        @switch($item->tipe_evaluasi)
                                            @case('ami')<i class="fas fa-search"></i>@break
                                            @case('edom')<i class="fas fa-chalkboard-teacher"></i>@break
                                            @case('evaluasi_layanan')<i class="fas fa-concierge-bell"></i>@break
                                            @case('evaluasi_kinerja')<i class="fas fa-chart-line"></i>@break
                                            @default<i class="fas fa-chart-bar"></i>
                                        @endswitch
                                    </div>
                                    <div class="evaluasi-details">
                                        <h6>{{ $item->nama_evaluasi }}</h6>
                                        <small>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $item->unitKerja->nama ?? 'Tidak ada unit kerja' }}
                                            @if($item->periode)
                                            • <i class="fas fa-calendar me-1"></i>{{ $item->periode }}
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
                                @if($item->status == 'aktif' || $item->status == 'selesai')
                                    <span class="status-badge badge-aktif">
                                        <i class="fas fa-check-circle me-1"></i> {{ $item->status == 'aktif' ? 'Aktif' : 'Selesai' }}
                                    </span>
                                @elseif($item->status == 'nonaktif')
                                    <span class="status-badge badge-nonaktif">
                                        <i class="fas fa-times-circle me-1"></i> Nonaktif
                                    </span>
                                @else
                                    <span class="status-badge badge-berjalan">
                                        <i class="fas fa-spinner me-1"></i> Berjalan
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
                                        
                                        <div class="upload-inline-modal" id="uploadModalEvaluasi{{ $item->id }}">
                                            <form action="{{ route('spmi.evaluasi.upload', $item->id) }}" method="POST" enctype="multipart/form-data" class="upload-inline-form" id="uploadFormEvaluasi{{ $item->id }}">
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
                                                    <button type="button" class="btn btn-sm btn-primary flex-fill" onclick="uploadInlineFile({{ $item->id }}, this)">
                                                        <i class="fas fa-upload me-1"></i> Upload
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleUploadModalEvaluasi({{ $item->id }})">
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
                                    <a href="{{ route('upload.spmi-evaluasi') }}?evaluasi_id={{ $item->id }}" class="btn-action" title="Upload dengan Konteks">
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
                <i class="fas fa-chart-bar"></i>
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
    <div class="modal-dialog">
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
                    <div class="mb-3">
                        <label class="form-label">Nama Evaluasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_evaluasi" required 
                               placeholder="Contoh: Audit Mutu Internal Prodi Teknik">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Evaluasi <span class="text-danger">*</span></label>
                            <select class="form-select" name="tipe_evaluasi" required>
                                <option value="">Pilih Tipe</option>
                                <option value="ami">Audit Mutu Internal (AMI)</option>
                                <option value="edom">Evaluasi Dosen oleh Mahasiswa (EDOM)</option>
                                <option value="evaluasi_layanan">Evaluasi Layanan</option>
                                <option value="evaluasi_kinerja">Evaluasi Kinerja</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" 
                                   value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Periode (opsional)</label>
                        <input type="text" class="form-control" name="periode" 
                               placeholder="Contoh: Semester Ganjil 2024">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="berjalan">Berjalan</option>
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
                    <i class="fas fa-chart-bar me-2"></i> Detail Evaluasi
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

<link rel="stylesheet" href="{{ asset('js/evaluasi.js') }}">