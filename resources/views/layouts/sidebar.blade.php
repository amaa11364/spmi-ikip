@php
    use App\Models\Dokumen;
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();
    $role = $user ? strtolower($user->role) : null;
    $isAdmin = $role === 'admin';
    $isVerifikator = $role === 'verifikator';
    $isUser = $role === 'user';
    
    // Fungsi untuk cek apakah route terdaftar
    function routeExists($routeName) {
        return Route::has($routeName);
    }
    
    // Dashboard route berdasarkan role
    if ($isAdmin) {
        $dashboardRoute = routeExists('admin.dashboard') ? route('admin.dashboard') : '#';
    } elseif ($isVerifikator) {
        $dashboardRoute = routeExists('verifikator.dashboard') ? route('verifikator.dashboard') : '#';
    } else {
        $dashboardRoute = routeExists('user.dashboard') ? route('user.dashboard') : '#';
    }
    
    // Hitung pending documents untuk verifikator
    $pendingCount = 0;
    if ($isVerifikator && $user->unit_kerja_id) {
        try {
            $pendingCount = Dokumen::where('status', 'pending')
                ->where('unit_kerja_id', $user->unit_kerja_id)
                ->count();
        } catch (\Exception $e) {
            $pendingCount = 0;
        }
    }
    
    // Hitung total dokumen user
    $userDocumentsCount = 0;
    if ($isUser) {
        try {
            $userDocumentsCount = $user->dokumens()->count();
        } catch (\Exception $e) {
            $userDocumentsCount = 0;
        }
    }
@endphp

<div class="sidebar-inner">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="d-flex flex-column align-items-center text-center">
            <div class="logo-container mb-3">
                <img src="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}" 
                     alt="IKIP Logo" 
                     class="logo-img" 
                     style="max-width: 70px; height: auto;">
            </div>
            <h4 class="fw-bold mb-1 text-white">SPMI</h4>
            <small class="opacity-75 text-white-50">Q-TRACK Digital</small>
            <div class="mt-2">
                <span class="badge bg-light text-dark">
                    @if($isAdmin)
                        <i class="fas fa-crown me-1"></i>Administrator
                    @elseif($isVerifikator)
                        <i class="fas fa-check-circle me-1"></i>Verifikator
                    @else
                        <i class="fas fa-user me-1"></i>User
                    @endif
                </span>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Menu -->
    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            {{-- DASHBOARD (UNTUK SEMUA ROLE) --}}
            @if($dashboardRoute != '#')
            <li class="nav-item mt-2">
                <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}" 
                   href="{{ $dashboardRoute }}">
                    <i class="fas fa-home"></i>Dashboard
                </a>
            </li>
            @endif

            {{-- MENU UNTUK ROLE USER --}}
            @if($isUser)
            <li class="nav-item mt-3">
                <small class="text-muted px-3">MANAJEMEN DOKUMEN</small>
            </li>
            
            @if(routeExists('user.upload-dokumen.create'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.upload-dokumen.create') ? 'active' : '' }}" 
                   href="{{ route('user.upload-dokumen.create') }}">
                    <i class="fas fa-cloud-upload-alt"></i>Upload Dokumen
                </a>
            </li>
            @endif
            
            @if(routeExists('user.dokumen.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.dokumen.*') ? 'active' : '' }}" 
                   href="{{ route('user.dokumen.index') }}">
                    <i class="fas fa-folder-open"></i>Dokumen Saya
                    @if($userDocumentsCount > 0)
                        <span class="badge bg-info ms-2">{{ $userDocumentsCount }}</span>
                    @endif
                </a>
            </li>
            @endif
            
            @if(routeExists('user.dokumen.status'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.dokumen.status') ? 'active' : '' }}" 
                   href="{{ route('user.dokumen.status') }}">
                    <i class="fas fa-chart-pie"></i>Status Dokumen
                </a>
            </li>
            @endif
            @endif
            
            {{-- MENU UNTUK ROLE VERIFIKATOR --}}
            @if($isVerifikator)
            <li class="nav-item mt-3">
                <small class="text-muted px-3">VERIFIKASI DOKUMEN</small>
            </li>
            
            @if(routeExists('verifikator.review.pending'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('verifikator.review.*') ? 'active' : '' }}" 
                   href="{{ route('verifikator.review.pending') }}">
                    <i class="fas fa-clipboard-list"></i>Perlu Verifikasi
                    @if($pendingCount > 0)
                        <span class="badge bg-warning ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            @endif
            
            @if(routeExists('verifikator.dokumen.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('verifikator.dokumen.*') ? 'active' : '' }}" 
                   href="{{ route('verifikator.dokumen.index') }}">
                    <i class="fas fa-file-alt"></i>Semua Dokumen
                </a>
            </li>
            @endif
            
            @if(routeExists('verifikator.dokumen.approved'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('verifikator.dokumen.approved') ? 'active' : '' }}" 
                   href="{{ route('verifikator.dokumen.approved') }}">
                    <i class="fas fa-check-circle text-success"></i>Dokumen Disetujui
                </a>
            </li>
            @endif
            
            @if(routeExists('verifikator.dokumen.rejected'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('verifikator.dokumen.rejected') ? 'active' : '' }}" 
                   href="{{ route('verifikator.dokumen.rejected') }}">
                    <i class="fas fa-times-circle text-danger"></i>Dokumen Ditolak
                </a>
            </li>
            @endif
            
            @if(routeExists('verifikator.statistik.index'))
            <li class="nav-item mt-3">
                <small class="text-muted px-3">STATISTIK</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('verifikator.statistik.*') ? 'active' : '' }}" 
                   href="{{ route('verifikator.statistik.index') }}">
                    <i class="fas fa-chart-bar"></i>Laporan Verifikasi
                </a>
            </li>
            @endif
            @endif
            
            {{-- MENU UNTUK ROLE ADMIN --}}
            @if($isAdmin)
            <li class="nav-item mt-3">
                <small class="text-muted px-3">MANAJEMEN USER</small>
            </li>
            
            @if(routeExists('admin.users.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users-cog"></i>Kelola User
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.roles.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" 
                   href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-tag"></i>Manajemen Role
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.unit-kerja.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.unit-kerja.*') ? 'active' : '' }}" 
                   href="{{ route('admin.unit-kerja.index') }}">
                    <i class="fas fa-building"></i>Unit Kerja
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <small class="text-muted px-3">KONTEN & BERITA</small>
            </li>
            
            @if(routeExists('admin.berita.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" 
                   href="{{ route('admin.berita.index') }}">
                    <i class="fas fa-newspaper"></i>Kelola Berita
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.kategori-berita.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kategori-berita.*') ? 'active' : '' }}" 
                   href="{{ route('admin.kategori-berita.index') }}">
                    <i class="fas fa-tags"></i>Kategori Berita
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <small class="text-muted px-3">MANAJEMEN JADWAL</small>
            </li>
            
            @if(routeExists('admin.jadwal.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}" 
                   href="{{ route('admin.jadwal.index') }}">
                    <i class="fas fa-calendar-alt"></i>Kelola Jadwal
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.kalender.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kalender.*') ? 'active' : '' }}" 
                   href="{{ route('admin.kalender.index') }}">
                    <i class="fas fa-calendar-check"></i>Kalender Akademik
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <small class="text-muted px-3">MANAJEMEN DOKUMEN</small>
            </li>
            
            @if(routeExists('admin.dokumen.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('.dokumen.*') ? 'active' : '' }}" 
                   href="{{ route('admin.dokumen.index') }}">
                    <i class="fas fa-file"></i>Semua Dokumen
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.dokumen.statistik'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dokumen.statistik') ? 'active' : '' }}" 
                   href="{{ route('admin.dokumen.statistik') }}">
                    <i class="fas fa-chart-line"></i>Statistik Dokumen
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.kategori-dokumen.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.kategori-dokumen.*') ? 'active' : '' }}" 
                   href="{{ route('admin.kategori-dokumen.index') }}">
                    <i class="fas fa-folder"></i>Kategori Dokumen
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <small class="text-muted px-3">LAPORAN & AUDIT</small>
            </li>
            
            @if(routeExists('admin.laporan.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" 
                   href="{{ route('admin.laporan.index') }}">
                    <i class="fas fa-file-pdf"></i>Generate Laporan
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.audit-log.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}" 
                   href="{{ route('admin.audit-log.index') }}">
                    <i class="fas fa-history"></i>Audit Log
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.backup.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}" 
                   href="{{ route('admin.backup.index') }}">
                    <i class="fas fa-database"></i>Backup Database
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <small class="text-muted px-3">PENGATURAN</small>
            </li>
            
            @if(routeExists('admin.settings.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                   href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i>Pengaturan Sistem
                </a>
            </li>
            @endif
            
            @if(routeExists('admin.profile.edit'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}" 
                   href="{{ route('admin.profile.edit') }}">
                    <i class="fas fa-user-circle"></i>Profil Admin
                </a>
            </li>
            @endif
            @endif

            {{-- MENU SPMI (UNTUK SEMUA ROLE) --}}
            <li class="nav-item mt-3">
                <small class="text-muted px-3">SIKLUS PPEPP</small>
            </li>
            
            @if(routeExists('spmi.penetapan.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spmi.penetapan.*') ? 'active' : '' }}" 
                   href="{{ route('spmi.penetapan.index') }}">
                    <i class="fas fa-pen-fancy"></i>Penetapan
                </a>
            </li>
            @endif
            
            @if(routeExists('spmi.pelaksanaan.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spmi.pelaksanaan.*') ? 'active' : '' }}" 
                   href="{{ route('spmi.pelaksanaan.index') }}">
                    <i class="fas fa-play"></i>Pelaksanaan
                </a>
            </li>
            @endif
            
            @if(routeExists('spmi.evaluasi.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spmi.evaluasi.*') ? 'active' : '' }}" 
                   href="{{ route('spmi.evaluasi.index') }}">
                    <i class="fas fa-chart-simple"></i>Evaluasi
                </a>
            </li>
            @endif
            
            @if(routeExists('spmi.pengendalian.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spmi.pengendalian.*') ? 'active' : '' }}" 
                   href="{{ route('spmi.pengendalian.index') }}">
                    <i class="fas fa-sliders"></i>Pengendalian
                </a>
            </li>
            @endif
            
            @if(routeExists('spmi.peningkatan.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spmi.peningkatan.*') ? 'active' : '' }}" 
                   href="{{ route('spmi.peningkatan.index') }}">
                    <i class="fas fa-arrow-up"></i>Peningkatan
                </a>
            </li>
            @endif

            {{-- MENU BANTUAN (UNTUK SEMUA ROLE) --}}
            <li class="nav-item mt-3">
                <small class="text-muted px-3">BANTUAN</small>
            </li>
            
            @if(routeExists('help.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('help.*') ? 'active' : '' }}" 
                   href="{{ route('help.index') }}">
                    <i class="fas fa-question-circle"></i>Panduan Pengguna
                </a>
            </li>
            @endif
            
            @if(routeExists('faq.index'))
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('faq.*') ? 'active' : '' }}" 
                   href="{{ route('faq.index') }}">
                    <i class="fas fa-comments"></i>FAQ
                </a>
            </li>
            @endif
        </ul>
    </nav>
    
    <!-- Sidebar Footer with Logout -->
    <div class="sidebar-footer">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>Keluar
                </a>
                <form id="logout-form" action="{{ routeExists('logout') ? route('logout') : '#' }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>