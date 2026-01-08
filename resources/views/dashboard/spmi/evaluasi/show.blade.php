@extends('layouts.main')

@section('title', 'Evaluasi - ' . $typeName)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>{{ $typeName }}
                </h3>
                <p class="text-muted mb-0">Detail hasil evaluasi</p>
            </div>
            <a href="{{ route('spmi.evaluasi.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        
        <div class="card">
            <div class="card-body">
                @if($type == 'ami')
                <h5 class="card-title mb-4">Data Audit Mutu Internal</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Auditor</th>
                                <th>Unit yang Diaudit</th>
                                <th>Temuan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024</td>
                                <td>Tim Auditor A</td>
                                <td>Prodi Ilmu Pendidikan</td>
                                <td>3 Temuan Minor</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>2023</td>
                                <td>Tim Auditor B</td>
                                <td>Prodi Pendidikan Bahasa</td>
                                <td>5 Temuan Minor</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @elseif($type == 'edom')
                <h5 class="card-title mb-4">Data Evaluasi Dosen oleh Mahasiswa</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Semester</th>
                                <th>Mata Kuliah</th>
                                <th>Dosen</th>
                                <th>Rata-rata</td>
                                <th>Jumlah Responden</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ganjil 2024</td>
                                <td>Pengembangan Kurikulum</td>
                                <td>Dr. Ahmad, M.Pd.</td>
                                <td><strong>4.5</strong>/5.0</td>
                                <td>45</td>
                            </tr>
                            <tr>
                                <td>Genap 2023</td>
                                <td>Metodologi Penelitian</td>
                                <td>Dr. Siti, M.Si.</td>
                                <td><strong>4.2</strong>/5.0</td>
                                <td>38</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-pie fa-4x text-muted mb-3"></i>
                    <h5>Data {{ $typeName }}</h5>
                    <p class="text-muted">Data sedang dalam pengembangan</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection