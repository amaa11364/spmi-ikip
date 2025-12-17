{{-- resources/views/layouts/sidebar.blade.php --}}
<div class="col-md-3 col-lg-2 sidebar">
    <div class="sidebar-header">
        <!-- Logo dan Teks dalam satu container -->
        <div class="d-flex flex-column align-items-center text-center">
            <div class="logo-container mb-2">
                <img src="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}" alt="Q-TRACK Logo" class="logo-img" style="max-width: 70px; height: auto;">
            </div>
            <h3 class="fw-bold mb-0">SPMI</h3>
            <small class="opacity-75">SPMI Digital</small>
        </div>
    </div>
    
    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') || request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('upload-dokumen*') ? 'active' : '' }}" href="{{ route('upload-dokumen.create') }}">
                    <i class="fas fa-upload"></i>Upload Dokumen
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dokumen-saya*') ? 'active' : '' }}" href="{{ route('dokumen-saya') }}">
                    <i class="fas fa-folder"></i>Dokumen Saya
                </a>
            </li>

            <!-- ADMIN MENU - Hanya tampil untuk admin -->
            @if(auth()->check() && auth()->user()->is_admin)
            <li class="nav-item mt-3">
                <small class="text-muted px-3">ADMINISTRATOR</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/berita*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                    <i class="fas fa-newspaper"></i>Kelola Berita
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/jadwal*') ? 'active' : '' }}" href="{{ route('admin.jadwal.index') }}">
                    <i class="fas fa-calendar-alt"></i>Kelola Jadwal
                </a>
            </li>
            @endif

            <!-- Setting Menu -->
            <li class="nav-item mt-3">
                <small class="text-muted px-3">PENGATURAN SISTEM</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings/iku*') ? 'active' : '' }}" href="{{ route('settings.iku.index') }}">
                    <i class="fas fa-chart-line"></i>Kelola IKU
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/settings/unit-kerja*') ? 'active' : '' }}" href="{{ route('settings.unit-kerja.index') }}">
                    <i class="fas fa-building"></i>Kelola Unit Kerja
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</div>