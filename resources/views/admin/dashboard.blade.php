@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Dashboard Administrator</h1>
        <div class="text-end">
            <small class="text-muted">Login sebagai: <strong>{{ auth()->user()->name }}</strong></small>
        </div>
    </div>

    <!-- Statistik Ringkasan -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="custom-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-muted mb-2">Total User</h5>
                        <h2 class="fw-bold mb-0">1,254</h2>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i>12% dari bulan lalu</small>
                    </div>
                    <div class="bg-primary-brown text-white rounded-circle p-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="custom-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-muted mb-2">Total Berita</h5>
                        <h2 class="fw-bold mb-0">48</h2>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i>3 baru minggu ini</small>
                    </div>
                    <div class="bg-success text-white rounded-circle p-3">
                        <i class="fas fa-newspaper fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="custom-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-muted mb-2">Jadwal Aktif</h5>
                        <h2 class="fw-bold mb-0">12</h2>
                        <small class="text-warning"><i class="fas fa-clock me-1"></i>2 akan berakhir</small>
                    </div>
                    <div class="bg-warning text-white rounded-circle p-3">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="custom-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="text-muted mb-2">Dokumen Upload</h5>
                        <h2 class="fw-bold mb-0">892</h2>
                        <small class="text-info"><i class="fas fa-file-alt me-1"></i>34 menunggu verifikasi</small>
                    </div>
                    <div class="bg-info text-white rounded-circle p-3">
                        <i class="fas fa-file-upload fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions untuk Admin -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="custom-card p-4">
                <h5 class="mb-4">Quick Actions</h5>
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-start p-3">
                            <i class="fas fa-plus-circle fa-2x me-3"></i>
                            <div class="text-start">
                                <strong>Tambah Berita</strong>
                                <small class="d-block">Posting berita baru</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-start p-3">
                            <i class="fas fa-calendar-plus fa-2x me-3"></i>
                            <div class="text-start">
                                <strong>Buat Jadwal</strong>
                                <small class="d-block">Jadwal kegiatan baru</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.settings.iku.create') }}" class="btn btn-warning w-100 d-flex align-items-center justify-content-start p-3">
                            <i class="fas fa-chart-line fa-2x me-3"></i>
                            <div class="text-start">
                                <strong>Atur IKU</strong>
                                <small class="d-block">Kelola indikator</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('admin.settings.unit-kerja.create') }}" class="btn btn-info w-100 d-flex align-items-center justify-content-start p-3">
                            <i class="fas fa-building fa-2x me-3"></i>
                            <div class="text-start">
                                <strong>Tambah Unit</strong>
                                <small class="d-block">Unit kerja baru</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Aktivitas Terbaru -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="custom-card h-100 p-4">
                <h5 class="mb-4">Aktivitas Sistem Terbaru</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10:30</td>
                                <td>Admin Sistem</td>
                                <td>Menambahkan berita baru</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>09:45</td>
                                <td>User 001</td>
                                <td>Upload dokumen SPMI</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Yesterday</td>
                                <td>Verifikator</td>
                                <td>Verifikasi 5 dokumen</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="custom-card h-100 p-4">
                <h5 class="mb-4">Menu Admin Cepat</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.berita.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-newspaper me-3 text-primary"></i>
                        <div>
                            <strong>Kelola Berita</strong>
                            <small class="d-block text-muted">Edit, hapus, publikasi</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.jadwal.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-calendar-alt me-3 text-success"></i>
                        <div>
                            <strong>Kelola Jadwal</strong>
                            <small class="d-block text-muted">Jadwal kegiatan SPMI</small>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-users-cog me-3 text-warning"></i>
                        <div>
                            <strong>Manajemen User</strong>
                            <small class="d-block text-muted">Atur hak akses user</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.settings.iku.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-chart-bar me-3 text-info"></i>
                        <div>
                            <strong>Pengaturan IKU</strong>
                            <small class="d-block text-muted">Indikator Kinerja</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection