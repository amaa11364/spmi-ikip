@php
    use App\Models\Dokumen;
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();
    $role = $user ? strtolower($user->role) : null;
    $isAdmin = $role === 'admin';
    $isVerifikator = $role === 'verifikator';
    $isUser = $role === 'user';
    
    function routeExists($routeName) {
        return Route::has($routeName);
    }
    
    // Dashboard route
    if ($isAdmin) {
        $dashboardRoute = routeExists('admin.dashboard') ? route('admin.dashboard') : '#';
    } elseif ($isVerifikator) {
        $dashboardRoute = routeExists('verifikator.dashboard') ? route('verifikator.dashboard') : '#';
    } else {
        $dashboardRoute = routeExists('user.dashboard') ? route('user.dashboard') : '#';
    }
    
    // Hitung pending documents
    $pendingCount = 0;
    if ($isVerifikator && $user->unit_kerja_id) {
        try {
            $pendingCount = Dokumen::where('status', 'pending')
                ->where('unit_kerja_id', $user->unit_kerja_id)
                ->count();
        } catch (\Exception $e) {}
    }
    
    // Hitung total dokumen user
    $userDocumentsCount = 0;
    if ($isUser) {
        try {
            $userDocumentsCount = $user->dokumens()->count();
        } catch (\Exception $e) {}
    }
@endphp

<div class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <img src="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}" 
                 alt="IKIP Logo" 
                 class="sidebar-logo">
            <div class="sidebar-brand-text">
                <h5>SPMI</h5>
                <small>Q-TRACK Digital</small>
            </div>
        </div>
        <div class="sidebar-user-role">
            @if($isAdmin)
                <span class="role-badge admin">Administrator</span>
            @elseif($isVerifikator)
                <span class="role-badge verifikator">Verifikator</span>
            @else
                <span class="role-badge user">User</span>
            @endif
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar-menu">
        <ul class="nav flex-column">
            
            {{-- DASHBOARD UNTUK SEMUA --}}
            <li class="nav-item">
                <a href="{{ $dashboardRoute }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- ========== MENU ADMIN ========== --}}
            @if($isAdmin)
            
            <li class="nav-section">MASTER DATA</li>
            
            {{-- Unit Kerja --}}
            @if(routeExists('admin.unit-kerja.index'))
            <li class="nav-item">
                <a href="{{ route('admin.unit-kerja.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.unit-kerja.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Unit Kerja</span>
                </a>
            </li>
            @endif

            {{-- IKU --}}
            @if(routeExists('admin.iku.index'))
            <li class="nav-item">
                <a href="{{ route('admin.iku.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.iku.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>IKU</span>
                </a>
            </li>
            @endif

            <li class="nav-section">MANAJEMEN</li>
            
            {{-- Kelola Akun/User --}}
            @if(routeExists('admin.users.index'))
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Kelola Akun</span>
                </a>
            </li>
            @endif

            {{-- Kelola Dokumen --}}
            @if(routeExists('admin.dokumen.index'))
            <li class="nav-item">
                <a href="{{ route('admin.dokumen.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Kelola Dokumen</span>
                </a>
            </li>
            @endif

            <li class="nav-section">KONTEN</li>
            
            {{-- Kelola Berita --}}
            @if(routeExists('admin.berita.index'))
            <li class="nav-item">
                <a href="{{ route('admin.berita.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Kelola Berita</span>
                </a>
            </li>
            @endif

            {{-- Kelola Jadwal --}}
            @if(routeExists('admin.jadwal.index'))
            <li class="nav-item">
                <a href="{{ route('admin.jadwal.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Kelola Jadwal</span>
                </a>
            </li>
            @endif

            {{-- Kelola IKU --}}
            @if(routeExists('admin.settings.iku.index'))
            <li class="nav-item">
                <a href="{{ route('admin.settings.iku.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.settings.iku.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Kelola IKU</span>
                </a>
            </li>
            @endif

             {{-- Kelola Unit Kerja --}}
            @if(routeExists('admin.settings.unit-kerja.index'))
            <li class="nav-item">
                <a href="{{ route('admin.settings.unit-kerja.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.settings.unit-kerja.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Kelola Unit Kerja</span>
                </a>
            </li>
            @endif

            @endif {{-- End Admin --}}

            {{-- ========== MENU VERIFIKATOR ========== --}}
            @if($isVerifikator)
            
            <li class="nav-section">VERIFIKASI</li>
            
            {{-- Perlu Verifikasi --}}
            <li class="nav-item">
                <a href="{{ routeExists('verifikator.review.pending') ? route('verifikator.review.pending') : '#' }}" 
                   class="nav-link {{ request()->routeIs('verifikator.review.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Perlu Verifikasi</span>
                    @if($pendingCount > 0)
                        <span class="badge warning">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            {{-- Semua Dokumen --}}
            <li class="nav-item">
                <a href="{{ routeExists('verifikator.dokumen.index') ? route('verifikator.dokumen.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('verifikator.dokumen.index') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Semua Dokumen</span>
                </a>
            </li>

            @endif {{-- End Verifikator --}}

            {{-- ========== MENU USER ========== --}}
            @if($isUser)
            
            <li class="nav-section">DOKUMEN SAYA</li>
            
            {{-- Upload Dokumen --}}
            <li class="nav-item">
                <a href="{{ routeExists('user.upload-dokumen.create') ? route('user.upload-dokumen.create') : '#' }}" 
                   class="nav-link {{ request()->routeIs('user.upload-dokumen.create') ? 'active' : '' }}">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Upload Dokumen</span>
                </a>
            </li>

            {{-- Daftar Dokumen --}}
            <li class="nav-item">
                <a href="{{ routeExists('user.dokumen-saya.index') ? route('user.dokumen-saya.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('user.dokumen-saya.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i>
                    <span>Dokumen Saya</span>
                    @if($userDocumentsCount > 0)
                        <span class="badge">{{ $userDocumentsCount }}</span>
                    @endif
                </a>
            </li>

            @endif {{-- End User --}}

            {{-- ========== MENU SPMI UNTUK SEMUA ========== --}}
            <li class="nav-section">SIKLUS PPEPP</li>
            
            {{-- Penetapan --}}
            <li class="nav-item">
                <a href="{{ routeExists('spmi.penetapan.index') ? route('spmi.penetapan.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('spmi.penetapan.*') ? 'active' : '' }}">
                    <i class="fas fa-pen-fancy"></i>
                    <span>Penetapan</span>
                </a>
            </li>

            {{-- Pelaksanaan --}}
            <li class="nav-item">
                <a href="{{ routeExists('spmi.pelaksanaan.index') ? route('spmi.pelaksanaan.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('spmi.pelaksanaan.*') ? 'active' : '' }}">
                    <i class="fas fa-play"></i>
                    <span>Pelaksanaan</span>
                </a>
            </li>

            {{-- Evaluasi --}}
            <li class="nav-item">
                <a href="{{ routeExists('spmi.evaluasi.index') ? route('spmi.evaluasi.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('spmi.evaluasi.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-simple"></i>
                    <span>Evaluasi</span>
                </a>
            </li>

            {{-- Pengendalian --}}
            <li class="nav-item">
                <a href="{{ routeExists('spmi.pengendalian.index') ? route('spmi.pengendalian.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('spmi.pengendalian.*') ? 'active' : '' }}">
                    <i class="fas fa-sliders"></i>
                    <span>Pengendalian</span>
                </a>
            </li>

            {{-- Peningkatan --}}
            <li class="nav-item">
                <a href="{{ routeExists('spmi.peningkatan.index') ? route('spmi.peningkatan.index') : '#' }}" 
                   class="nav-link {{ request()->routeIs('spmi.peningkatan.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i>
                    <span>Peningkatan</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ routeExists('logout') ? route('logout') : '#' }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>