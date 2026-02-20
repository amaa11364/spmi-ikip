@extends('layouts.main')

@section('title', 'Manajemen Dokumen')

@section('page_heading')
    <div class="d-flex align-items-center justify-content-between">
        <h1>Manajemen Dokumen</h1>
        <a href="{{ route('admin.dokumen.export') }}" class="btn btn-success">
            <i class="fas fa-download me-2"></i>Export Data
        </a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Dokumen</h6>
                                <h2 class="mb-0">{{ $statistics['total'] }}</h2>
                            </div>
                            <i class="fas fa-file fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Menunggu</h6>
                                <h2 class="mb-0">{{ $statistics['pending'] }}</h2>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Terverifikasi</h6>
                                <h2 class="mb-0">{{ $statistics['approved'] }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Ditolak</h6>
                                <h2 class="mb-0">{{ $statistics['rejected'] }}</h2>
                            </div>
                            <i class="fas fa-times-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Dokumen</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.dokumen.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search" class="form-label">Pencarian</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Nama dokumen...">
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Terverifikasi</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="tahapan" class="form-label">Tahapan SPMI</label>
                            <select class="form-select" id="tahapan" name="tahapan">
                                <option value="">Semua Tahapan</option>
                                <option value="penetapan" {{ request('tahapan') == 'penetapan' ? 'selected' : '' }}>Penetapan</option>
                                <option value="pelaksanaan" {{ request('tahapan') == 'pelaksanaan' ? 'selected' : '' }}>Pelaksanaan</option>
                                <option value="evaluasi" {{ request('tahapan') == 'evaluasi' ? 'selected' : '' }}>Evaluasi</option>
                                <option value="pengendalian" {{ request('tahapan') == 'pengendalian' ? 'selected' : '' }}>Pengendalian</option>
                                <option value="peningkatan" {{ request('tahapan') == 'peningkatan' ? 'selected' : '' }}>Peningkatan</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-3">
                            <label for="unit_kerja_id" class="form-label">Unit Kerja</label>
                            <select class="form-select" id="unit_kerja_id" name="unit_kerja_id">
                                <option value="">Semua Unit</option>
                                @foreach($unitKerjas as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama }} {{-- Ubah dari 'nama_unit' ke 'nama' --}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="prodi_id" class="form-label">Program Studi</label>
                            <select class="form-select" id="prodi_id" name="prodi_id">
                                <option value="">Semua Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="jenis_upload" class="form-label">Jenis Upload</label>
                            <select class="form-select" id="jenis_upload" name="jenis_upload">
                                <option value="">Semua</option>
                                <option value="file" {{ request('jenis_upload') == 'file' ? 'selected' : '' }}>File</option>
                                <option value="link" {{ request('jenis_upload') == 'link' ? 'selected' : '' }}>Link</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="start_date" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ request('start_date') }}">
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ request('end_date') }}">
                        </div>
                        
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dokumen Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Dokumen</th>
                                <th>Unit/Prodi</th>
                                <th>Tahapan</th>
                                <th>Status</th>
                                <th>Publik</th>
                                <th>Uploader</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokumens as $index => $dokumen)
                                <tr>
                                    <td>{{ $dokumens->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $dokumen->file_icon }} me-2 fa-lg"></i>
                                            <div>
                                                <strong>{{ $dokumen->nama_dokumen }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $dokumen->jenis_dokumen }}</small>
                                                @if($dokumen->jenis_upload === 'file')
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $dokumen->file_name }} ({{ $dokumen->file_size_formatted }})
                                                    </small>
                                                @else
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-link"></i> Link Eksternal
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($dokumen->unitKerja)
                                            <span class="badge bg-info">{{ $dokumen->unitKerja->nama }}</span> {{-- Ubah dari 'nama_unit' ke 'nama' --}}
                                        @endif
                                        @if($dokumen->prodi)
                                            <br>
                                            <small>{{ $dokumen->prodi->nama_prodi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dokumen->tahapan)
                                            <span class="badge bg-secondary">{{ $dokumen->tahapan_label }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $dokumen->verification_badge !!}
                                    </td>
                                    <td>
                                        @if($dokumen->is_public)
                                            <span class="badge bg-success">
                                                <i class="fas fa-globe me-1"></i>Publik
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-lock me-1"></i>Private
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $dokumen->uploader?->name ?? 'N/A' }}
                                            <br>
                                            <span class="text-muted">{{ $dokumen->created_at->format('d/m/Y') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $dokumen->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.dokumen.show', $dokumen->id) }}" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.dokumen.edit', $dokumen->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.dokumen.toggle-public', $dokumen->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm {{ $dokumen->is_public ? 'btn-secondary' : 'btn-success' }}"
                                                        title="{{ $dokumen->is_public ? 'Privasi' : 'Publikasikan' }}">
                                                    <i class="fas {{ $dokumen->is_public ? 'fa-lock' : 'fa-globe' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.dokumen.destroy', $dokumen->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada dokumen ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Menampilkan {{ $dokumens->firstItem() ?? 0 }} - {{ $dokumens->lastItem() ?? 0 }} 
                        dari {{ $dokumens->total() }} dokumen
                    </div>
                    <div>
                        {{ $dokumens->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .opacity-50 {
        opacity: 0.5;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit filter form when select changes
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
</script>
@endpush