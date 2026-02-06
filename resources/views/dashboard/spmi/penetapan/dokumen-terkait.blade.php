@extends('layouts.main')

@section('title', 'Dokumen Terkait - ' . $penetapan->nama_komponen)

@push('styles')
<style>
    .dokumen-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .dokumen-header {
        background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .dokumen-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .dokumen-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #8B4513;
    }
    
    .file-icon {
        font-size: 2.5rem;
        color: #8B4513;
        margin-right: 1rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    
    .badge-jenis {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .upload-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Header -->
    <div class="dokumen-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-2">
                    <i class="fas fa-file-alt me-2"></i>
                    Dokumen Terkait
                </h4>
                <div class="d-flex align-items-center">
                    <a href="{{ route('spmi.penetapan.show', $penetapan->id) }}" class="text-white me-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
                    </a>
                    <span class="badge bg-light text-dark">{{ $penetapan->nama_komponen }}</span>
                    <span class="badge bg-info ms-2">{{ $penetapan->kode_penetapan }}</span>
                </div>
            </div>
            <div>
                <a href="{{ route('upload.spmi-penetapan', $penetapan->id) }}" class="btn btn-light">
                    <i class="fas fa-upload me-2"></i> Upload Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Dokumen Container -->
    <div class="dokumen-container">
        <!-- Info Summary -->
        @php
            // Pastikan dokumen collection ada
            $dokumenCollection = $penetapan->dokumen ?? collect([]);
            $fileCount = $dokumenCollection->where('jenis_upload', 'file')->count();
            $linkCount = $dokumenCollection->where('jenis_upload', 'link')->count();
            $totalSize = $dokumenCollection->sum('file_size');
            $totalSizeMB = $totalSize ? number_format($totalSize / 1024 / 1024, 2) : 0;
        @endphp
        
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card">
                    <h5 class="mb-1 text-primary">{{ $dokumenCollection->count() }}</h5>
                    <small class="text-muted">Total Dokumen</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card">
                    <h5 class="mb-1 text-success">{{ $fileCount }}</h5>
                    <small class="text-muted">File Upload</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card">
                    <h5 class="mb-1 text-info">{{ $linkCount }}</h5>
                    <small class="text-muted">Link Dokumen</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stats-card">
                    <h5 class="mb-1 text-warning">
                        {{ $totalSizeMB }} MB
                    </h5>
                    <small class="text-muted">Total Ukuran</small>
                </div>
            </div>
        </div>

        <!-- Dokumen List -->
        @if($dokumenCollection->count() > 0)
            <div class="row">
                @foreach($dokumenCollection as $dokumen)
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="dokumen-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex flex-grow-1">
                                <div class="file-icon">
                                    <i class="{{ $dokumen->file_icon ?? 'fas fa-file' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" title="{{ $dokumen->nama_dokumen }}">
                                        {{ Str::limit($dokumen->nama_dokumen, 40) }}
                                    </h6>
                                    @if($dokumen->jenis_dokumen)
                                    <div class="mb-2">
                                        <span class="badge-jenis">
                                            <i class="fas fa-tag me-1"></i> {{ $dokumen->jenis_dokumen }}
                                        </span>
                                    </div>
                                    @endif
                                    <div class="d-flex flex-wrap gap-2">
                                        <small class="text-muted">
                                            <i class="far fa-calendar me-1"></i>
                                            {{ $dokumen->created_at->format('d/m/Y') }}
                                        </small>
                                        @if($dokumen->jenis_upload == 'file')
                                        <small class="text-muted">
                                            <i class="fas fa-weight me-1"></i>
                                            {{ $dokumen->file_size_formatted ?? '0 KB' }}
                                        </small>
                                        @endif
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $dokumen->user->name ?? 'Anonim' }}
                                        </small>
                                    </div>
                                    @if($dokumen->keterangan)
                                    <p class="small text-muted mt-2 mb-0">
                                        {{ Str::limit($dokumen->keterangan, 60) }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons mt-3 pt-2 border-top">
                            @if($dokumen->jenis_upload == 'file')
                                @if($dokumen->is_pdf ?? false)
                                <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Preview" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                                   class="btn btn-sm btn-outline-success" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            @elseif($dokumen->jenis_upload == 'link')
                                <a href="{{ $dokumen->file_path }}" 
                                   class="btn btn-sm btn-outline-info" title="Buka Link" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @endif
                            
                            <button class="btn btn-sm btn-outline-secondary" 
                                    onclick="showDokumenDetail({{ $dokumen->id }})" 
                                    title="Detail">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            
                            @if(auth()->user()->is_admin || auth()->id() == ($dokumen->user_id ?? null))
                            <form action="{{ route('dokumen-saya.destroy', $dokumen->id) }}" 
                                  method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete(this)" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h5 class="mb-2">Belum ada dokumen</h5>
                <p class="text-muted mb-4">Upload dokumen pertama untuk {{ $penetapan->nama_komponen }}</p>
                <a href="{{ route('upload.spmi-penetapan', $penetapan->id) }}" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i> Upload Dokumen Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- FAB Button untuk Upload -->
<a href="{{ route('upload.spmi-penetapan', $penetapan->id) }}" class="btn btn-primary btn-lg upload-fab" title="Upload Dokumen Baru">
    <i class="fas fa-plus"></i> Upload
</a>

<!-- Modal untuk Detail Dokumen -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i> Detail Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Content akan diisi via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tampilkan detail dokumen
    function showDokumenDetail(id) {
        // Ini adalah placeholder, bisa diganti dengan AJAX call ke controller
        alert('Detail dokumen akan ditampilkan di sini. Implementasi AJAX dapat ditambahkan.');
    }
    
    // Confirm delete
    function confirmDelete(button) {
        if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
            button.closest('.delete-form').submit();
        }
    }
</script>
@endpush