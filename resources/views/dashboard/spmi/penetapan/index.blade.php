@extends('layouts.main')

@section('title', 'Penetapan SPMI')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-file-signature me-2"></i>Penetapan SPMI
                </h4>
            </div>
            <div class="card-body">
                <p class="mb-4">Dokumen peraturan dan standar SPMI</p>
                
                <!-- Filter Sederhana -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Cari dokumen...">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select">
                            <option>Semua Kategori</option>
                            <option>Peraturan Rektor</option>
                            <option>Dokumen SPMI</option>
                            <option>Standar Pendidikan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Revisi</option>
                        </select>
                    </div>
                </div>
                
                <!-- Tabel Sederhana -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Dokumen</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dokumen['peraturan_rektor'] as $key => $doc)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <strong>{{ $doc['judul'] }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($doc['deskripsi'], 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">Peraturan Rektor</span>
                                </td>
                                <td>{{ $doc['tanggal_terbit'] }}</td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <a href="{{ route('spmi.penetapan.show', $doc['id']) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            
                            @foreach($dokumen['dokumen_spmi'] as $key => $doc)
                            <tr>
                                <td>{{ count($dokumen['peraturan_rektor']) + $key + 1 }}</td>
                                <td>
                                    <strong>{{ $doc['judul'] }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($doc['deskripsi'], 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">Dokumen SPMI</span>
                                </td>
                                <td>{{ $doc['tanggal_terbit'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $doc['status'] == 'aktif' ? 'success' : 'warning' }}">
                                        {{ ucfirst($doc['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('spmi.penetapan.show', $doc['id']) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            
                            @foreach($dokumen['standar_pendidikan'] as $key => $doc)
                            <tr>
                                <td>{{ count($dokumen['peraturan_rektor']) + count($dokumen['dokumen_spmi']) + $key + 1 }}</td>
                                <td>
                                    <strong>{{ $doc['judul'] }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($doc['deskripsi'], 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Standar Pendidikan</span>
                                </td>
                                <td>{{ $doc['tanggal_terbit'] }}</td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <a href="{{ route('spmi.penetapan.show', $doc['id']) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Tombol Sederhana -->
                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Dokumen
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection