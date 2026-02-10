@extends('layouts.verifikator')

@section('title', 'Review Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Review Dokumen</h1>
            <p class="text-muted">Verifikasi dokumen dari unit kerja Anda</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Jenis Dokumen</label>
                    <input type="text" name="jenis" class="form-control" placeholder="Jenis dokumen" 
                           value="{{ request('jenis') }}">
                </div>
                <div class="col-md-2">
                    <label>Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-2">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('verifikator.dokumen.index') }}" class="btn btn-secondary ms-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Cepat -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dokumen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dokumens->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Review</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dokumens->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dokumens->where('status', 'approved')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Perlu Revisi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $dokumens->where('status', 'revision')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-redo fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Dokumen -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Dokumen</th>
                            <th>Uploader</th>
                            <th>Jenis</th>
                            <th>Tanggal Upload</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumens as $dokumen)
                        <tr>
                            <td>{{ $loop->iteration + ($dokumens->perPage() * ($dokumens->currentPage() - 1)) }}</td>
                            <td>
                                <strong>{{ $dokumen->nama_dokumen }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($dokumen->deskripsi, 50) }}</small>
                            </td>
                            <td>{{ $dokumen->user->name ?? '-' }}</td>
                            <td>{{ $dokumen->jenis_dokumen }}</td>
                            <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $dokumen->status_badge }}">
                                    {{ ucfirst($dokumen->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('verifikator.dokumen.show', $dokumen->id) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Review
                                </a>
                                @if($dokumen->status == 'pending')
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal{{ $dokumen->id }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#revisionModal{{ $dokumen->id }}">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $dokumen->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endif
                            </td>
                        </tr>

                        <!-- Modals -->
                        @include('verifikator.dokumen.modals.approve', ['dokumen' => $dokumen])
                        @include('verifikator.dokumen.modals.revision', ['dokumen' => $dokumen])
                        @include('verifikator.dokumen.modals.reject', ['dokumen' => $dokumen])
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada dokumen untuk direview</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $dokumens->links() }}
            </div>
        </div>
    </div>
</div>
@endsection