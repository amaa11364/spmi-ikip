@extends('layouts.main')

@section('title', 'Pencarian Dokumen')

@push('styles')
<style>
    .search-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        display: none;
    }
    
    .filter-section.show {
        display: block;
    }
    
    .filter-toggle {
        background: white;
        border: 2px solid var(--primary-brown);
        color: var(--primary-brown);
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-toggle:hover {
        background: var(--primary-brown);
        color: white;
    }
    
    .filter-toggle.active {
        background: var(--primary-brown);
        color: white;
    }
    
    .iku-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
    }
    
    .table-custom {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .table-custom thead th {
        background: var(--primary-brown);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
    }
    
    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-color: #e9ecef;
    }
    
    .table-custom tbody tr:hover {
        background-color: rgba(153, 102, 0, 0.05);
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
    
    .search-loading {
        display: none;
        text-align: center;
        padding: 40px;
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
</style>
@endpush

@section('content')
<!-- Search Header -->
<div class="search-header">
    <div class="row align-items-center">
        <div class="col-md-8 col-12">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-search me-2"></i>Pencarian Dokumen
            </h2>
            <p class="mb-0">Temukan semua dokumen SPMI dari seluruh pengguna</p>
        </div>
        <div class="col-md-4 col-12 text-md-end text-start mt-2 mt-md-0">
            <a href="{{ route('dokumen-saya') }}" class="btn btn-light btn-lg me-2">
                <i class="fas fa-folder me-2"></i>Dokumen Saya
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-home me-2"></i>Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form id="searchForm" action="{{ route('search.results') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8 col-12">
                <label class="form-label fw-semibold">Cari Dokumen</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="q" id="searchInput"
                           placeholder="Ketik nama dokumen, jenis, unit kerja, IKU, atau uploader..." 
                           value="{{ request('q') }}"
                           autocomplete="off">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                </div>
            </div>
            
            <div class="col-md-4 col-12">
                <button type="button" class="btn filter-toggle w-100" id="filterToggle">
                    <i class="fas fa-filter me-2"></i>Filter Lanjutan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Filter Section (Hidden by Default) -->
<div class="filter-section card" id="filterSection">
    <div class="card-body">
        <form action="{{ route('search.results') }}" method="GET" class="row g-3">
            <input type="hidden" name="q" value="{{ request('q') }}">
            
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
                    @if(request()->hasAny(['unit_kerja', 'iku_id']))
                        <a href="{{ route('search.index') }}" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Loading Indicator -->
<div class="search-loading" id="searchLoading">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-2 text-muted">Mencari dokumen...</p>
</div>

<!-- Results Info -->
@if(request()->hasAny(['q', 'unit_kerja', 'iku_id']))
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-search me-2"></i>
                    Hasil Pencarian
                    @if(request('q'))
                        untuk "{{ request('q') }}"
                    @endif
                    <span class="badge bg-primary ms-2">{{ $dokumens->count() }} dokumen ditemukan</span>
                </h5>
            </div>
            @if(request()->hasAny(['q', 'unit_kerja', 'iku_id']))
                <a href="{{ route('search.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Reset Pencarian
                </a>
            @endif
        </div>
    </div>
</div>
@else
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-files me-2"></i>
                    Semua Dokumen Tersedia
                    <span class="badge bg-success ms-2">{{ $dokumens->count() }} dokumen</span>
                </h5>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Documents Table -->
<div class="table-responsive">
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
        <tbody id="documentsTableBody">
            @forelse($dokumens as $dokumen)
            <tr>
                <td class="file-icon-cell">
                    <i class="{{ $dokumen->file_icon }}"></i>
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
                        @if($dokumen->is_pdf)
                        <a href="{{ route('search.dokumen.preview', $dokumen->id) }}" 
                           class="btn btn-outline-primary" target="_blank" title="Preview">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif
                        <a href="{{ route('search.dokumen.download', $dokumen->id) }}" 
                           class="btn btn-outline-success" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="no-documents">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">
                            @if(request()->hasAny(['q', 'unit_kerja', 'iku_id']))
                                Tidak ada dokumen yang sesuai dengan pencarian
                            @else
                                Belum ada dokumen
                            @endif
                        </h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['q', 'unit_kerja', 'iku_id']))
                                Coba ubah kata kunci atau filter pencarian Anda
                            @else
                                Upload dokumen pertama untuk memulai
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;
    
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchLoading = document.getElementById('searchLoading');
        const documentsTableBody = document.getElementById('documentsTableBody');
        const filterToggle = document.getElementById('filterToggle');
        const filterSection = document.getElementById('filterSection');
        
        // Real-time search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Hide loading if empty
            if (searchTerm.length === 0) {
                searchLoading.style.display = 'none';
                return;
            }
            
            // Show loading after 500ms delay
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
            }, 500);
        });
        
        // Toggle filter section
        filterToggle.addEventListener('click', function() {
            filterSection.classList.toggle('show');
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
        
        // Show filter section if filters are active
        @if(request()->hasAny(['unit_kerja', 'iku_id']))
            filterSection.classList.add('show');
            filterToggle.classList.add('active');
            filterToggle.innerHTML = '<i class="fas fa-times me-2"></i>Tutup Filter';
        @endif
    });
    
    function performSearch(searchTerm) {
        const searchLoading = document.getElementById('searchLoading');
        const documentsTableBody = document.getElementById('documentsTableBody');
        
        // Show loading
        searchLoading.style.display = 'block';
        
        // Make AJAX request
        fetch(`{{ route('search.ajax') }}?q=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                searchLoading.style.display = 'none';
                
                if (data.success) {
                    updateTableResults(data.dokumens);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchLoading.style.display = 'none';
            });
    }
    
    function updateTableResults(dokumens) {
        const documentsTableBody = document.getElementById('documentsTableBody');
        
        // Clear previous results
        documentsTableBody.innerHTML = '';
        
        // Populate results
        if (dokumens.length > 0) {
            dokumens.forEach(dokumen => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="file-icon-cell">
                        <i class="${dokumen.file_icon}"></i>
                    </td>
                    <td>
                        <div class="document-name">${dokumen.nama_dokumen}</div>
                        <div class="document-type">${dokumen.jenis_dokumen}</div>
                    </td>
                    <td>${dokumen.unit_kerja}</td>
                    <td>
                        ${dokumen.iku ? `
                        <span class="iku-badge" title="${dokumen.iku.nama}">
                            ${dokumen.iku.kode}
                        </span>
                        ` : '<span class="text-muted">-</span>'}
                    </td>
                    <td>${dokumen.file_size_formatted}</td>
                    <td>${dokumen.uploader}</td>
                    <td>${dokumen.upload_time_ago}</td>
                    <td class="actions-cell">
                        <div class="btn-group btn-group-sm">
                            ${dokumen.is_pdf ? `
                            <a href="${dokumen.preview_url}" 
                               class="btn btn-outline-primary" target="_blank" title="Preview">
                                <i class="fas fa-eye"></i>
                            </a>
                            ` : ''}
                            <a href="${dokumen.download_url}" 
                               class="btn btn-outline-success" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </td>
                `;
                documentsTableBody.appendChild(row);
            });
        } else {
            documentsTableBody.innerHTML = `
                <tr>
                    <td colspan="8">
                        <div class="no-documents">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada dokumen yang ditemukan</h5>
                            <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }
</script>
@endpush