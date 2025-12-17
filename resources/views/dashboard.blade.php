@extends('layouts.main')

@section('title', 'Dashboard')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .welcome-card h2 {
        font-size: 2.2rem;
        background: linear-gradient(45deg, #fff, #ffeb3b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stats-badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }
    
    .stats-badge i {
        font-size: 1.2rem;
        margin-right: 8px;
        color: #ffeb3b;
    }
    
    .stats-card {
        border-left: 4px solid var(--primary-brown);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .program-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
    }
    
    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        border-color: var(--primary-brown);
    }
    
    .program-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
        color: white;
    }
    
    /* Program colors */
    .program-1 { background: linear-gradient(135deg, #996600 0%, #b37400 100%); }
    .program-2 { background: linear-gradient(135deg, #aa7700 0%, #cc8800 100%); }
    .program-3 { background: linear-gradient(135deg, #bb8800 0%, #dd9900 100%); }
    .program-4 { background: linear-gradient(135deg, #cc9900 0%, #eeaa00 100%); }
    .program-5 { background: linear-gradient(135deg, #ddaa00 0%, #ffbb00 100%); }
    .program-6 { background: linear-gradient(135deg, #eebb00 0%, #ffcc00 100%); color: #333 !important; }
    
    /* Admin Panel Styles (Hanya untuk admin) */
    .admin-panel {
        border: 2px solid var(--primary-brown);
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .management-card {
        background: white;
        border-radius: 10px;
        padding: 1.25rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.06);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .management-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        border-color: var(--primary-brown);
    }
    
    .management-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
    }
    
    .quick-actions {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    
    .btn-purple:hover {
        background-color: #5a32a3;
        border-color: #5a32a3;
        color: white;
    }
    
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    
    .admin-badge {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

.admin-stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 4px 8px rgba(0,0,0,0.06);
    }
    
    .admin-stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        border-color: var(--primary-brown);
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    /* Recent Activity */
    .activity-item {
        padding: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }
    
    .activity-item:hover {
        background-color: #f8f9fa;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        margin-right: 1rem;
    }
    
    .activity-icon.news { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
    .activity-icon.schedule { background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%); }
    .activity-icon.document { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
    .activity-icon.user { background: linear-gradient(135deg, #6f42c1 0%, #9b4dca 100%); }
    
    /* Quick Actions Grid */
    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .quick-action-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Section -->
<div class="row">
    <div class="col-12">
        <div class="welcome-card p-lg-4 p-3">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-12 text-center text-lg-start">
                    <h4 class="fw-bold mb-2 opacity-90">LPM Smart Sistem</h4>
                    <h2 class="fw-bold mb-3">Selamat datang, {{ auth()->user()->name }}</h2>
                    <p class="mb-0 opacity-90 fs-5">
                        Kamu dapat melakukan pemberkasan dengan lebih mudah dan untuk saat ini terdapat 
                        <span class="fw-bold text-warning">6 Program Studi</span> yang terdaftar pada sistem.
                        
                        @if(auth()->user()->role === 'admin')
                            <br>
                            <small class="opacity-90 d-block mt-2">
                                <i class="fas fa-crown me-1 text-warning"></i>
                                Anda login sebagai <strong>Administrator</strong> - Anda dapat mengelola berita dan jadwal.
                            </small>
                        @endif
                    </p>
                </div>
                <div class="col-lg-4 col-md-12 text-center text-lg-end mt-3 mt-lg-0">
                    <div class="stats-badge d-inline-block">
                        <i class="fas fa-university me-1"></i> 6 Program Studi Aktif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="row text-center g-0">
                    <div class="col-lg-3 col-md-6 col-6 border-end">
                        <div class="p-4 stats-card">
                            <h3 class="fw-bold text-primary mb-1">12</h3>
                            <p class="text-muted mb-0">Total Standar</p>
                            <small class="text-primary fw-semibold">Standar Mutu</small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 border-end">
                        <div class="p-4 stats-card">
                            <h3 class="fw-bold text-success mb-1">8</h3>
                            <p class="text-muted mb-0">Audit Selesai</p>
                            <small class="text-success fw-semibold">Tercapai</small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 border-end">
                        <div class="p-4 stats-card">
                            <h3 class="fw-bold text-warning mb-1">24</h3>
                            <p class="text-muted mb-0">Dokumen Mutu</p>
                            <small class="text-warning fw-semibold">Terkelola</small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6">
                        <div class="p-4 stats-card">
                            <h3 class="fw-bold text-info mb-1">6</h3>
                            <p class="text-muted mb-0">Program Studi</p>
                            <small class="text-info fw-semibold">Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Dashboard Stats - Hanya untuk admin -->
@if(auth()->check() && auth()->user()->role === 'admin')
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-crown me-2 text-warning"></i>Statistik Admin
                    <span class="badge bg-warning ms-2">ADMIN</span>
                </h5>
                <div class="quick-action-grid">
                    <a href="{{ route('admin.berita.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle me-1"></i>Tambah Berita
                    </a>
                    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-sm btn-info">
                        <i class="fas fa-calendar-plus me-1"></i>Tambah Jadwal
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $totalBerita = \App\Models\Berita::count();
                        $publishedBerita = \App\Models\Berita::where('is_published', true)->count();
                        $draftBerita = \App\Models\Berita::where('is_published', false)->count();
                        $totalJadwal = \App\Models\Jadwal::count();
                        $activeJadwal = \App\Models\Jadwal::where('is_active', true)->count();
                        $draftJadwal = \App\Models\Jadwal::where('is_active', false)->count();
                    @endphp
                    
                    <!-- Berita Stats -->
                    <div class="col-md-4">
                        <div class="admin-stats-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="stats-number text-primary">{{ $totalBerita }}</div>
                                    <h6 class="fw-semibold mb-1">Total Berita</h6>
                                </div>
                                <div class="activity-icon news">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $publishedBerita }} Published
                                </small>
                                <small class="text-warning">
                                    <i class="fas fa-pencil-alt me-1"></i>
                                    {{ $draftBerita }} Draft
                                </small>
                            </div>
                            <a href="{{ route('admin.berita.index') }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                <i class="fas fa-list me-1"></i>Kelola Berita
                            </a>
                        </div>
                    </div>
                    
                    <!-- Jadwal Stats -->
                    <div class="col-md-4">
                        <div class="admin-stats-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="stats-number text-info">{{ $totalJadwal }}</div>
                                    <h6 class="fw-semibold mb-1">Total Jadwal</h6>
                                </div>
                                <div class="activity-icon schedule">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $activeJadwal }} Active
                                </small>
                                <small class="text-warning">
                                    <i class="fas fa-pencil-alt me-1"></i>
                                    {{ $draftJadwal }} Draft
                                </small>
                            </div>
                            <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-info w-100 mt-3">
                                <i class="fas fa-list me-1"></i>Kelola Jadwal
                            </a>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="col-md-4">
                        <div class="admin-stats-card">
                            <h6 class="fw-semibold mb-3">
                                <i class="fas fa-history me-2"></i>Aktivitas Terbaru
                            </h6>
                            <div class="activity-list">
                                @php
                                    $recentBerita = \App\Models\Berita::latest()->limit(3)->get();
                                @endphp
                                
                                @foreach($recentBerita as $activity)
                                <div class="activity-item">
                                    <div class="d-flex align-items-center">
                                        <div class="activity-icon news">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <strong class="small">{{ $activity->judul }}</strong>
                                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                            </div>
                                            <small class="text-muted">
                                                Status: 
                                                <span class="badge bg-{{ $activity->is_published ? 'success' : 'warning' }}">
                                                    {{ $activity->is_published ? 'Published' : 'Draft' }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($recentBerita->isEmpty())
                                <div class="text-center py-3">
                                    <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small mb-0">Belum ada aktivitas</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Program Studi Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2 text-primary"></i>Program Studi
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $programStudi = [
                            ['nama' => 'Ilmu Pendidikan', 'kode' => 'IPD', 'icon' => 'graduation-cap', 'mahasiswa' => '120'],
                            ['nama' => 'Pendidikan Bahasa', 'kode' => 'PBI', 'icon' => 'language', 'mahasiswa' => '95'],
                            ['nama' => 'Matematika & Sains', 'kode' => 'MTS', 'icon' => 'calculator', 'mahasiswa' => '85'],
                            ['nama' => 'Program Khusus', 'kode' => 'PKH', 'icon' => 'user-graduate', 'mahasiswa' => '45'],
                            ['nama' => 'Pascasarjana', 'kode' => 'PSC', 'icon' => 'user-tie', 'mahasiswa' => '60'],
                            ['nama' => 'LPM Smart Sistem', 'kode' => 'LPM', 'icon' => 'laptop-code', 'mahasiswa' => 'N/A']
                        ];
                    @endphp
                    
                    @foreach($programStudi as $program)
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="program-card">
                            <div class="program-icon {{ 'program-' . $loop->iteration }}">
                                <i class="fas fa-{{ $program['icon'] }}"></i>
                            </div>
                            <h6 class="fw-semibold mb-1">{{ $program['nama'] }}</h6>
                            <small class="text-muted d-block mb-2">{{ $program['kode'] }}</small>
                            <div class="badge bg-light text-dark">
                                <i class="fas fa-users me-1"></i>{{ $program['mahasiswa'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="fas fa-upload me-2 text-primary"></i>Aksi Dokumen
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('upload-dokumen.create') }}" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Upload Dokumen Baru
                    </a>
                    <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-primary">
                        <i class="fas fa-folder-open me-2"></i>Lihat Dokumen Saya
                    </a>
                </div>
                
                <div class="mt-4">
                    <h6 class="fw-semibold mb-3">Tips Upload Dokumen:</h6>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pastikan format file PDF, DOC, atau XLS</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Ukuran maksimal file 10MB</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pilih unit kerja dan IKU yang sesuai</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Isi deskripsi dengan jelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="fas fa-cog me-2 text-primary"></i>Pengaturan Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <a href="{{ route('settings.iku.index') }}" class="btn btn-outline-success w-100 text-start mb-2">
                            <i class="fas fa-chart-line me-2"></i>Kelola IKU
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('settings.unit-kerja.index') }}" class="btn btn-outline-info w-100 text-start mb-2">
                            <i class="fas fa-building me-2"></i>Kelola Unit Kerja
                        </a>
                    </div>
                    
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="col-md-6">
                        <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-success w-100 text-start mb-2">
                            <i class="fas fa-newspaper me-2"></i>Kelola Berita
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-info w-100 text-start mb-2">
                            <i class="fas fa-calendar-alt me-2"></i>Kelola Jadwal
                        </a>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-semibold mb-3">Status Akun:</h6>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @php
                                $avatarColor = 'avatar-color-' . (auth()->user()->id % 6);
                            @endphp
                            <div class="user-avatar {{ $avatarColor }}">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <strong>{{ auth()->user()->name }}</strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>{{ auth()->user()->email }}
                                <br>
                                <i class="fas fa-user-tag me-1 mt-1"></i>
                                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pengguna' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Content Section (For All Users) -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-newspaper me-2 text-primary"></i>Berita Terbaru
                </h6>
                <a href="{{ route('berita.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @php
                    $latestBerita = \App\Models\Berita::published()->latest()->limit(3)->get();
                @endphp
                
                @if($latestBerita->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($latestBerita as $berita)
                        <a href="{{ route('berita.show', $berita->slug) }}" 
                           class="list-group-item list-group-item-action border-0 px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-semibold mb-1">{{ Str::limit($berita->judul, 50) }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $berita->created_at->format('d M Y') }}
                                    </small>
                                </div>
                                <span class="badge bg-primary">
                                    {{ $berita->views }} <i class="fas fa-eye ms-1"></i>
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada berita</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Jadwal Mendatang
                </h6>
                @if(auth()->check() && auth()->user()->is_admin)
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-sm btn-outline-primary">
                        Kelola
                    </a>
                @endif
            </div>
            <div class="card-body">
                @php
                    $upcomingJadwals = \App\Models\Jadwal::active()->upcoming(5)->get();
                @endphp
                
                @if($upcomingJadwals->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingJadwals as $jadwal)
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex">
                                <div class="jadwal-date-small me-3">
                                    <div class="text-center bg-light rounded p-2">
                                        <div class="fw-bold text-primary">{{ $jadwal->hari }}</div>
                                        <small class="text-muted">{{ $jadwal->bulanSingkat }}</small>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">{{ $jadwal->kegiatan }}</h6>
                                    <small class="text-muted">
                                        @if($jadwal->waktu)
                                            <i class="fas fa-clock me-1"></i>{{ $jadwal->waktu->format('H:i') }}
                                        @endif
                                        @if($jadwal->tempat)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $jadwal->tempat }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada jadwal mendatang</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection