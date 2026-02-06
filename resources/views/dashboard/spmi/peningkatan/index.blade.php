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
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .badge-draft { background-color: #e2e3e5; color: #383d41; }
    .badge-disetujui { background-color: #d4edda; color: #155724; }
    .badge-berjalan { background-color: #d1ecf1; color: #0c5460; }
    .badge-selesai { background-color: #cce5ff; color: #004085; }
    
    .progress-container {
        width: 100%;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 8px;
        border-radius: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Folder Header -->
    <div class="folder-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <i class="fas fa-chart-line fa-2x me-3"></i>
                <div>
                    <h4 class="mb-1">Program Peningkatan SPMI</h4>
                    <p class="mb-0 opacity-75">Manajemen program peningkatan mutu</p>
                </div>
            </div>
            <div>
                <a href="{{ route('spmi.peningkatan.create') }}" class="btn btn-light">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Program
                </a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="folder-stats">
            <div class="stat-item">
                <span class="stat-number">{{ $totalPeningkatan ?? 0 }}</span>
                <span class="stat-label">Total Program</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $peningkatanAktif ?? 0 }}</span>
                <span class="stat-label">Aktif</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $dokumenValid ?? 0 }}</span>
                <span class="stat-label">Dokumen Valid</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $dokumenBelumValid ?? 0 }}</span>
                <span class="stat-label">Belum Valid</span>
            </div>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('spmi.peningkatan.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Cari program..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="tipe">
                            <option value="all">Semua Tipe</option>
                            <option value="strategis" {{ request('tipe') == 'strategis' ? 'selected' : '' }}>Strategis</option>
                            <option value="operasional" {{ request('tipe') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="perbaikan" {{ request('tipe') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            <option value="pengembangan" {{ request('tipe') == 'pengembangan' ? 'selected' : '' }}>Pengembangan</option>
                            <option value="inovasi" {{ request('tipe') == 'inovasi' ? 'selected' : '' }}>Inovasi</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="tahun">
                            <option value="all">Semua Tahun</option>
                            @foreach($tahunList ?? [] as $tahunItem)
                                <option value="{{ $tahunItem->tahun }}" {{ request('tahun') == $tahunItem->tahun ? 'selected' : '' }}>
                                    {{ $tahunItem->tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
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
                            <th width="30%">Program</th>
                            <th width="15%">Kode</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Status</th>
                            <th width="15%">Progress</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peningkatan as $item)
                        <tr>
                            <td>
                                <div class="program-info">
                                    <div class="program-icon {{ $item->tipe_peningkatan ?? 'strategis' }}">
                                        @switch($item->tipe_pembangan ?? 'strategis')
                                            @case('strategis')<i class="fas fa-flag"></i>@break
                                            @case('operasional')<i class="fas fa-cogs"></i>@break
                                            @case('perbaikan')<i class="fas fa-tools"></i>@break
                                            @case('pengembangan')<i class="fas fa-chart-line"></i>@break
                                            @case('inovasi')<i class="fas fa-lightbulb"></i>@break
                                            @default<i class="fas fa-file-alt"></i>
                                        @endswitch
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $item->nama_program }}</h6>
                                        <small class="text-muted">
                                            {{ $item->unitKerja->nama ?? 'Tidak ada unit kerja' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <span class="badge bg-light text-dark">{{ $item->kode_peningkatan ?? 'PEN-001' }}</span>
                            </td>
                            
                            <td>
                                <span class="badge bg-info">{{ $item->tahun ?? date('Y') }}</span>
                            </td>
                            
                            <td>
                                @if(($item->status ?? 'draft') == 'disetujui')
                                    <span class="status-badge badge-disetujui">
                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif(($item->status ?? 'draft') == 'berjalan')
                                    <span class="status-badge badge-berjalan">
                                        <i class="fas fa-play-circle me-1"></i> Berjalan
                                    </span>
                                @elseif(($item->status ?? 'draft') == 'selesai')
                                    <span class="status-badge badge-selesai">
                                        <i class="fas fa-flag-checkered me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="status-badge badge-draft">
                                        <i class="fas fa-edit me-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            
                            <td>
                                <div class="progress-container">
                                    <div class="progress-bar bg-success" style="width: {{ $item->progress ?? 0 }}%"></div>
                                </div>
                                <small class="d-block mt-1">{{ $item->progress ?? 0 }}%</small>
                            </td>
                            
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('spmi.peningkatan.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('spmi.peningkatan.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('spmi.peningkatan.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus program?')">
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
            
            @if($peningkatan->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $peningkatan->firstItem() }} - {{ $peningkatan->lastItem() }} dari {{ $peningkatan->total() }}
                    </div>
                    <div>
                        {{ $peningkatan->links() }}
                    </div>
                </div>
            </div>
            @endif
            
        @else
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Program Peningkatan</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan program baru</p>
                <a href="{{ route('spmi.peningkatan.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i> Tambah Program
                </a>
            </div>
        @endif
    </div>
</div>
@endsection