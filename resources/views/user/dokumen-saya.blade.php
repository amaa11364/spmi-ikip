@extends('layouts.main')

@section('title', 'Dokumen Saya')

@push('styles')
<style>
    .drive-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .file-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .file-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .file-actions {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .file-card:hover .file-actions {
        opacity: 1;
    }
    
    .view-grid { display: block; }
    .view-list { display: none; }
    
    .list-view .file-card {
        height: auto;
        margin-bottom: 1rem;
    }
    
    .list-view .file-icon {
        font-size: 2rem;
        margin-bottom: 0;
        margin-right: 1rem;
    }

    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .iku-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .filter-active-info {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 10px 15px;
        margin-top: 10px;
        border-left: 4px solid white;
    }
    .file-actions {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .file-card:hover .file-actions {
        opacity: 1;
    }
    
    /* Responsive saja */
    @media (max-width: 768px) {
        .drive-header {
            padding: 1rem;
        }
        
        .file-actions {
            opacity: 1; /* Selalu tampil di mobile */
        }
        
        .filter-section .col-md-3,
        .filter-section .col-md-4 {
            margin-bottom: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .drive-header .text-end {
            text-align: left !important;
            margin-top: 1rem;
        }
        
        .view-toggle {
            margin-top: 1rem;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .list-view .file-card .row {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<!-- Drive Header -->
<div class="drive-header">
    <div class="row align-items-center">
        <div class="col-md-8 col-12">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-cloud me-2"></i>Dokumen Saya
            </h2>
            <p class="mb-0">Kelola semua dokumen SPMI Anda</p>
        </div>
       <div class="col-md-4 col-12 text-md-end text-start mt-2 mt-md-0">
            <a href="{{ route('upload-dokumen.create') }}" class="btn btn-light btn-lg me-2">
                <i class="fas fa-plus me-2"></i>Upload Baru
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>
</div>

<!-- ... content tetap sama ... -->

<!-- Filters & Search -->
<div class="filter-section">
    <form action="{{ route('dokumen-saya') }}" method="GET" class="row g-3">
        <div class="col-md-3 col-12">
            <label class="form-label fw-semibold">Unit Kerja</label>
            <select class="form-select" name="unit_kerja">
                <option value="">Semua Unit</option>
                @foreach($unitKerjas as $unit)
                    <option value="{{ $unit->id }}" {{ request('unit_kerja') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 col-12">
            <label class="form-label fw-semibold">IKU</label>
            <select class="form-select" name="iku_id">
                <option value="">Semua IKU</option>
                @foreach($ikus as $iku)
                    <option value="{{ $iku->id }}" {{ request('iku_id') == $iku->id ? 'selected' : '' }}>
                        {{ $iku->kode }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-4 col-12">
            <label class="form-label fw-semibold">Cari Dokumen</label>
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Cari berdasarkan nama dokumen..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <div class="col-md-2 col-12 d-flex align-items-end">
            <div class="d-grid w-100">
                @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                    <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                @else
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- View Toggle & Results Info -->
<div class="row mb-4">
    <div class="col-md-6 col-12">
        <div class="d-flex align-items-center">
            <span class="text-muted me-3">
                <i class="fas fa-files me-1"></i> 
                {{ $dokumens->count() }} dokumen ditemukan
            </span>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <div class="btn-group">
            <button class="btn btn-outline-secondary active" onclick="setView('grid')">
                <i class="fas fa-th"></i> Grid
            </button>
            <button class="btn btn-outline-secondary" onclick="setView('list')">
                <i class="fas fa-list"></i> List
            </button>
        </div>
    </div>
</div>

<!-- Documents Grid/List -->
<div class="view-grid" id="gridView">
    <div class="row g-4">
        @forelse($dokumens as $dokumen)
      <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="file-card">
                <div class="file-icon text-center">
                    <i class="{{ $dokumen->file_icon }}"></i>
                </div>
                
                <!-- Tampilkan Badge IKU jika ada -->
                @if($dokumen->iku)
                <div class="mb-2 text-center">
                    <span class="iku-badge" title="{{ $dokumen->iku->nama }}">
                        <i class="fas fa-chart-line me-1"></i>{{ $dokumen->iku->kode }}
                    </span>
                </div>
                @endif

                <!-- Di dalam file-card (grid view) tambahkan setelah badge IKU -->
@if($dokumen->jenis_upload === 'link')
<div class="mb-2 text-center">
    <span class="badge bg-warning text-dark" title="Dokumen Link">
        <i class="fas fa-link me-1"></i>Link
    </span>
</div>
@endif

<!-- Di dalam list view tambahkan kolom untuk jenis upload -->
<div class="col-md-1 col-2 text-center">
    @if($dokumen->jenis_upload === 'link')
    <span class="badge bg-warning text-dark" title="Dokumen Link">
        <i class="fas fa-link"></i>
    </span>
    @else
    <span class="badge bg-info text-white" title="Dokumen File">
        <i class="fas fa-file"></i>
    </span>
    @endif
</div>
                
                <h6 class="fw-bold text-truncate" title="{{ $dokumen->nama_dokumen }}">
                    {{ $dokumen->nama_dokumen }}
                </h6>
                <small class="text-muted d-block mb-2">
                    <i class="fas fa-folder me-1"></i>{{ $dokumen->unitKerja->nama }}
                </small>
                <small class="text-muted d-block mb-2">
                    <i class="fas fa-file me-1"></i>{{ $dokumen->file_size_formatted }}
                </small>
                <small class="text-muted d-block">
                    <i class="fas fa-clock me-1"></i>{{ $dokumen->upload_time_ago }}
                </small>
                
                <div class="file-actions mt-3 text-center">
                    <div class="btn-group btn-group-sm">
                        @if($dokumen->is_pdf)
                        <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                           class="btn btn-outline-primary" target="_blank" title="Preview">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                        <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                           class="btn btn-outline-success" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        <form action="{{ route('dokumen-saya.destroy', $dokumen->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Hapus dokumen ini?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">
                    @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                        Tidak ada dokumen yang sesuai dengan filter
                    @else
                        Belum ada dokumen
                    @endif
                </h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                        Coba ubah filter pencarian Anda
                    @else
                        Upload dokumen pertama Anda untuk memulai
                    @endif
                </p>
                <a href="{{ route('upload-dokumen.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Upload Dokumen
                </a>
                @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                    <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-2"></i>Reset Filter
                    </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- List View -->
<div class="view-list list-view" id="listView">
    @forelse($dokumens as $dokumen)
    <div class="file-card">
        <div class="row align-items-center">
            <div class="col-md-1 text-center">
                <i class="{{ $dokumen->file_icon }} file-icon"></i>
            </div>
            <div class="col-md-3">
                <h6 class="fw-bold mb-1">{{ $dokumen->nama_dokumen }}</h6>
                <small class="text-muted">{{ $dokumen->unitKerja->nama }}</small>
            </div>
            <div class="col-md-1 col-2 text-center">
                @if($dokumen->iku)
                <span class="iku-badge" title="{{ $dokumen->iku->nama }}">
                    {{ $dokumen->iku->kode }}
                </span>
                @else
                <span class="text-muted">-</span>
                @endif
            </div>
            <div class="col-md-2">
                <small class="text-muted">{{ $dokumen->file_size_formatted }}</small>
            </div>
            <div class="col-md-2">
                <small class="text-muted">{{ $dokumen->upload_time_ago }}</small>
            </div>
            <div class="col-md-3 text-end">
                <div class="btn-group btn-group-sm">
                    @if($dokumen->is_pdf)
                    <a href="{{ route('dokumen-saya.preview', $dokumen->id) }}" 
                       class="btn btn-outline-primary" target="_blank" title="Preview">
                        <i class="fas fa-eye"></i>
                    </a>
                    @endif
                    <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" 
                       class="btn btn-outline-success" title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                    <form action="{{ route('dokumen-saya.destroy', $dokumen->id) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Hapus dokumen ini?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">
            @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                Tidak ada dokumen yang sesuai dengan filter
            @else
                Belum ada dokumen
            @endif
        </h5>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    function setView(view) {
        if (view === 'grid') {
            document.getElementById('gridView').style.display = 'block';
            document.getElementById('listView').style.display = 'none';
            document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        } else {
            document.getElementById('gridView').style.display = 'none';
            document.getElementById('listView').style.display = 'block';
            document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
    }

    // Auto submit form when filter changes
    document.querySelector('select[name="unit_kerja"]')?.addEventListener('change', function() {
        this.form.submit();
    });

    document.querySelector('select[name="iku_id"]')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endpush