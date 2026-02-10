@extends('layouts.main')

@section('title', 'Repository Pengendalian SPMI')

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
    
    /* Tindakan Info */
    .tindakan-info {
        display: flex;
        align-items: flex-start;
    }
    
    .tindakan-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.2rem;
        background-color: #fce4ec;
        color: #c2185b;
    }
    
    .tindakan-details h6 {
        margin: 0;
        font-weight: 600;
        color: #212529;
        font-size: 0.95rem;
    }
    
    .tindakan-details small {
        color: #6c757d;
        font-size: 0.8rem;
        display: block;
        margin-top: 2px;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-rencana { background-color: #e9ecef; color: #495057; }
    .badge-berjalan { background-color: #cff4fc; color: #055160; }
    .badge-selesai { background-color: #d1e7dd; color: #0a3622; }
    .badge-terverifikasi { background-color: #d1ecf1; color: #0c5460; }
    .badge-tertunda { background-color: #fff3cd; color: #856404; }
    
    .badge-valid { background-color: #d1ecf1; color: #0c5460; }
    .badge-belum_valid { background-color: #f8d7da; color: #721c24; }
    .badge-dalam_review { background-color: #fff3cd; color: #856404; }
    
    /* Progress Bar */
    .progress-container {
        width: 80px;
        height: 6px;
        background-color: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        margin-right: 8px;
    }
    
    .progress-bar {
        height: 100%;
        background-color: #28a745;
        transition: width 0.3s ease;
    }
    
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
        
        .tindakan-icon {
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
                <i class="fas fa-tasks folder-icon"></i>
                <div>
                    <h4 class="mb-1">Repository Pengendalian SPMI</h4>
                    <p class="mb-0 opacity-75">Sistem manajemen tindak lanjut hasil evaluasi mutu</p>
                </div>
            </div>
            <div>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Tindakan
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="folder-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $totalPengendalian }}</span>
                <span class="stat-label">Total Tindakan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $selesai }}</span>
                <span class="stat-label">Selesai</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $berjalan }}</span>
                <span class="stat-label">Berjalan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $tertunda }}</span>
                <span class="stat-label">Tertunda</span>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('spmi.pengendalian.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Cari tindakan..." 
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
                        <option value="rencana" {{ request('status') == 'rencana' ? 'selected' : '' }}>Rencana</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="tertunda" {{ request('status') == 'tertunda' ? 'selected' : '' }}>Tertunda</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="unit_kerja_id">
                        <option value="all">Semua Unit</option>
                        @foreach($unitKerjaList as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                        <a href="{{ route('spmi.pengendalian.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Table -->
    <div class="table-folder">
        @if($pengendalian->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="30%">Tindakan Perbaikan</th>
                            <th width="15%">Penanggung Jawab</th>
                            <th width="10%">Target</th>
                            <th width="15%">Status</th>
                            <th width="10%">Progress</th>
                            <th width="10%">Dokumen</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengendalian as $item)
                        <tr>
                            <!-- Tindakan Column -->
                            <td>
                                <div class="tindakan-info">
                                    <div class="tindakan-icon">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <div class="tindakan-details">
                                        <h6>{{ Str::limit($item->nama_tindakan, 60) }}</h6>
                                        <small>
                                            <i class="fas fa-building me-1"></i>
                                            {{ $item->unitKerja->nama ?? 'Tidak ada unit kerja' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Penanggung Jawab Column -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->penanggung_jawab }}</span>
                            </td>
                            
                            <!-- Target Column -->
                            <td>
                                @if($item->target_waktu)
                                    <span class="badge {{ $item->target_waktu < now() ? 'bg-danger' : 'bg-info' }} text-white">
                                        {{ \Carbon\Carbon::parse($item->target_waktu)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Belum ditentukan</span>
                                @endif
                            </td>
                            
                            <!-- Status Column -->
                            <td>
                                @if($item->status_pelaksanaan == 'rencana')
                                    <span class="status-badge badge-rencana">
                                        <i class="fas fa-clock me-1"></i> Rencana
                                    </span>
                                @elseif($item->status_pelaksanaan == 'berjalan')
                                    <span class="status-badge badge-berjalan">
                                        <i class="fas fa-play-circle me-1"></i> Berjalan
                                    </span>
                                @elseif($item->status_pelaksanaan == 'selesai')
                                    <span class="status-badge badge-selesai">
                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                    </span>
                                @elseif($item->status_pelaksanaan == 'terverifikasi')
                                    <span class="status-badge badge-terverifikasi">
                                        <i class="fas fa-check-double me-1"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="status-badge badge-tertunda">
                                        <i class="fas fa-exclamation-circle me-1"></i> Tertunda
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Progress Column -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress-container">
                                        <div class="progress-bar" style="width: {{ $item->progress }}%; background-color: {{ 
                                            $item->progress >= 100 ? '#28a745' : 
                                            ($item->progress >= 70 ? '#17a2b8' : 
                                            ($item->progress >= 40 ? '#ffc107' : '#dc3545')) 
                                        }}"></div>
                                    </div>
                                    <span class="small">{{ $item->progress }}%</span>
                                </div>
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
                                    <a href="{{ route('spmi.pengendalian.show', $item->id) }}" 
                                       class="btn-action btn-view" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('spmi.pengendalian.edit', $item->id) }}" 
                                       class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Upload Button -->
                                    <a href="{{ route('upload.spmi-pengendalian', $item->id) }}" 
                                       class="btn-action btn-upload" title="Upload Dokumen">
                                        <i class="fas fa-upload"></i>
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('spmi.pengendalian.destroy', $item->id) }}" 
                                          method="POST" class="d-inline delete-form">
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
            @if($pengendalian->hasPages())
            <div class="pagination-container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $pengendalian->firstItem() }} - {{ $pengendalian->lastItem() }} dari {{ $pengendalian->total() }} tindakan
                    </div>
                    <div>
                        {{ $pengendalian->links() }}
                    </div>
                </div>
            </div>
            @endif
            
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-tasks"></i>
                <h5 class="mb-2">Belum Ada Tindakan Pengendalian</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan tindakan perbaikan baru.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Tindakan Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('spmi.pengendalian.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Tindakan Pengendalian
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Tindakan Perbaikan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_tindakan" required 
                               placeholder="Contoh: Perbaikan Kurikulum Prodi XYZ">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sumber Evaluasi</label>
                            <input type="text" class="form-control" name="sumber_evaluasi" 
                                   placeholder="Contoh: Hasil Audit Mutu Internal 2024">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" 
                                   value="{{ date('Y') }}" min="2000" max="{{ date('Y') + 5 }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="deskripsi_masalah" rows="3" required 
                                  placeholder="Jelaskan masalah yang ditemukan dalam evaluasi"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tindakan Perbaikan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="tindakan_perbaikan" rows="3" required 
                                  placeholder="Jelaskan tindakan perbaikan yang akan dilakukan"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="penanggung_jawab" required 
                                   placeholder="Nama penanggung jawab tindakan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Waktu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="target_waktu" required 
                                   value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Pelaksanaan <span class="text-danger">*</span></label>
                            <select class="form-select" name="status_pelaksanaan" required>
                                <option value="rencana">Rencana</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="terverifikasi">Terverifikasi</option>
                                <option value="tertunda">Tertunda</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="progress" 
                                   value="0" min="0" max="100" required>
                        </div>
                        <div class="col-md-4 mb-3">
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
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="catatan" rows="2" 
                                  placeholder="Catatan tambahan (opsional)"></textarea>
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
@endsection

@push('scripts')
<script>
    // Confirm Delete
    function confirmDelete(button) {
        if (confirm('Apakah Anda yakin ingin menghapus tindakan pengendalian ini?')) {
            button.closest('.delete-form').submit();
        }
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush