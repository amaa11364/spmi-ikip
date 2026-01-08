@extends('layouts.main')

@section('title', 'Detail Dokumen')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail Dokumen</h4>
                <a href="{{ route('spmi.penetapan.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <h5 class="mb-4">{{ $dokumen['judul'] }}</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Kode Dokumen</th>
                                <td>{{ $dokumen['kode_dokumen'] }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>
                                    @if($dokumen['kategori'] == 'peraturan_rektor')
                                        <span class="badge bg-primary">Peraturan Rektor</span>
                                    @elseif($dokumen['kategori'] == 'dokumen_spmi')
                                        <span class="badge bg-info">Dokumen SPMI</span>
                                    @else
                                        <span class="badge bg-success">Standar Pendidikan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Versi</th>
                                <td>{{ $dokumen['versi'] }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Penanggung Jawab</th>
                                <td>{{ $dokumen['penanggung_jawab'] }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Terbit</th>
                                <td>{{ $dokumen['tanggal_terbit'] }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6>Deskripsi:</h6>
                    <p>{{ $dokumen['deskripsi'] }}</p>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection