{{-- resources/views/layouts/sidebar.blade.php --}}
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

    <li class="nav-item mt-3">
        <small class="text-muted px-3">SPMI - PPEPP</small>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard/spmi/penetapan*') ? 'active' : '' }}" 
           href="{{ route('spmi.penetapan.index') }}">
            <i class="fas fa-file-signature"></i>Penetapan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard/spmi/pelaksanaan*') ? 'active' : '' }}" 
           href="{{ route('spmi.pelaksanaan.index') }}">
            <i class="fas fa-play-circle"></i>Pelaksanaan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard/spmi/evaluasi*') ? 'active' : '' }}" 
           href="{{ route('spmi.evaluasi.index') }}">
            <i class="fas fa-chart-bar"></i>Evaluasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard/spmi/pengendalian*') ? 'active' : '' }}" 
           href="{{ route('spmi.pengendalian.index') }}">
            <i class="fas fa-sliders-h"></i>Pengendalian
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard/spmi/peningkatan*') ? 'active' : '' }}" 
           href="{{ route('spmi.peningkatan.index') }}">
            <i class="fas fa-chart-line"></i>Peningkatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard/spmi/akreditasi*') ? 'active' : '' }}" 
           href="{{ route('spmi.akreditasi.index') }}">
            <i class="fas fa-award"></i>Akreditasi
        </a>
    </li>

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
    
    <!-- Spacer -->
    <li class="nav-item mt-4"></li>
    
    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>Keluar
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>