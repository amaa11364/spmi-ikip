@extends('layouts.main')

@section('title', 'Penetapan SPMI')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-signature me-2"></i>Penetapan SPMI
                    </h4>
                    <a href="{{ route('spmi.penetapan.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Data
                    </a>
                </div>
                <div class="card-body">
                    
                    <!-- Tabs untuk Kelompok Penetapan -->
                    <ul class="nav nav-tabs mb-4" id="penetapanTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pengelolaan" type="button">
                                Pengelolaan SPMI
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#organisasi" type="button">
                                Organisasi Pengelola
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pelaksanaan" type="button">
                                Standar Pelaksanaan
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="penetapanTabContent">
                        
                        <!-- Tab Pengelolaan -->
                        <div class="tab-pane fade show active" id="pengelolaan">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Nama Komponen</th>
                                            <th width="100">Tahun</th>
                                            <th width="120">Status</th>
                                            <th width="150">Status Dokumen</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kelompok['pengelolaan'] as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->nama_komponen }}</strong>
                                                @if($item->deskripsi)
                                                <br><small class="text-muted">{{ Str::limit($item->deskripsi, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>
                                                @if($item->status == 'aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($item->status == 'revisi')
                                                    <span class="badge bg-warning">Revisi</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status_dokumen == 'valid')
                                                    <span class="badge bg-success">Valid</span>
                                                @elseif($item->status_dokumen == 'dalam_review')
                                                    <span class="badge bg-warning">Review</span>
                                                @else
                                                    <span class="badge bg-danger">Belum Valid</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Tombol Upload -->
                                                    <button type="button" class="btn btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#uploadModal{{ $item->id }}"
                                                            title="Upload Dokumen">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                    
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('spmi.penetapan.edit', $item->id) }}" 
                                                       class="btn btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('spmi.penetapan.destroy', $item->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" 
                                                                onclick="return confirm('Hapus data ini?')"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Tombol View Dokumen -->
                                                    @if($item->dokumen_id)
                                                    <a href="{{ Storage::url($item->dokumen->file_path ?? '') }}" 
                                                       target="_blank" class="btn btn-success" title="Lihat Dokumen">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                                
                                                <!-- Modal Upload -->
                                                <div class="modal fade" id="uploadModal{{ $item->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('spmi.penetapan.upload', $item->id) }}" 
                                                                  method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Upload Dokumen Penetapan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Komponen:</label>
                                                                        <input type="text" class="form-control" 
                                                                               value="{{ $item->nama_komponen }}" readonly>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Tahun:</label>
                                                                        <input type="text" class="form-control" 
                                                                               value="{{ $item->tahun }}" readonly>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>File Dokumen *</label>
                                                                        <input type="file" class="form-control" 
                                                                               name="file_dokumen" accept=".pdf,.doc,.docx" required>
                                                                        <small class="text-muted">Format: PDF, DOC, DOCX (max: 10MB)</small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" 
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                        @if(count($kelompok['pengelolaan']) == 0)
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                Belum ada data pengelolaan SPMI.
                                                <a href="{{ route('spmi.penetapan.create') }}">Tambah data</a>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Tab Organisasi -->
                        <div class="tab-pane fade" id="organisasi">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Komponen</th>
                                            <th>Tahun</th>
                                            <th>Status</th>
                                            <th>Status Dokumen</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kelompok['organisasi'] as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->nama_komponen }}</td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>
                                                <span class="badge bg-{{ $item->status == 'aktif' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $item->status_dokumen == 'valid' ? 'success' : 'danger' }}">
                                                    {{ $item->status_dokumen == 'valid' ? 'Valid' : 'Belum Valid' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <!-- Tombol aksi sama seperti di atas -->
                                                    <button type="button" class="btn btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#uploadModal{{ $item->id }}">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                    <a href="{{ route('spmi.penetapan.edit', $item->id) }}" 
                                                       class="btn btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- ... -->
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Tab Pelaksanaan -->
                        <div class="tab-pane fade" id="pelaksanaan">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <!-- Struktur sama seperti tab lainnya -->
                                    <tbody>
                                        @foreach($kelompok['pelaksanaan'] as $key => $item)
                                        <!-- Data pelaksanaan -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-close modal setelah upload
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.has('upload')) {
            const modal = new bootstrap.Modal(document.getElementById('uploadModal' + urlParams.get('upload')));
            modal.show();
        }
    });
</script>
@endpush