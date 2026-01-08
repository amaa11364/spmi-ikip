@extends('layouts.main')

@section('title', 'Evaluasi SPMI')

@section('content')
<div class="row">
    <div class="col-12">
        <h3 class="fw-bold mb-3">
            <i class="fas fa-chart-bar me-2 text-primary"></i>Evaluasi SPMI
        </h3>
        
        <!-- Menu Evaluasi -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('spmi.evaluasi.show', 'ami') }}" class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="fas fa-search fa-2x"></i>
                            </div>
                            <h6 class="fw-bold">Audit Mutu Internal</h6>
                            <small class="text-muted">Hasil audit mutu</small>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('spmi.evaluasi.show', 'edom') }}" class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-success text-white rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                            </div>
                            <h6 class="fw-bold">EDOM</h6>
                            <small class="text-muted">Evaluasi Dosen</small>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('spmi.evaluasi.show', 'evaluasi_layanan') }}" class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-info text-white rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="fas fa-concierge-bell fa-2x"></i>
                            </div>
                            <h6 class="fw-bold">Evaluasi Layanan</h6>
                            <small class="text-muted">Kualitas layanan</small>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('spmi.evaluasi.show', 'evaluasi_kinerja') }}" class="text-decoration-none">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-warning text-white rounded-circle p-3 mb-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                            <h6 class="fw-bold">Evaluasi Kinerja</h6>
                            <small class="text-muted">Kinerja organisasi</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Ringkasan Evaluasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Audit Mutu Internal (AMI):</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Hasil</th>
                                        <th>Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluations['ami'] as $ami)
                                    <tr>
                                        <td>{{ $ami['tahun'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $ami['hasil'] == 'Baik' ? 'success' : 'warning' }}">
                                                {{ $ami['hasil'] }}
                                            </span>
                                        </td>
                                        <td>{{ $ami['hasil'] == 'Baik' ? '3' : '5' }} item</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Evaluasi Dosen oleh Mahasiswa (EDOM):</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Semester</th>
                                        <th>Rata-rata</th>
                                        <th>Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluations['edom'] as $edom)
                                    <tr>
                                        <td>{{ $edom['semester'] }}</td>
                                        <td>
                                            <strong>{{ $edom['rata_rata'] }}</strong>/5.0
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Sangat Baik</span>
                                        </td>
                                    </tr>
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
@endsection