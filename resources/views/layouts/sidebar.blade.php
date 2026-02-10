<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard') || request()->is('admin/dashboard') || request()->is('user/dashboard') || request()->is('verifikator/dashboard') ? 'active' : '' }}" 
           href="{{ 
                auth()->user()->is_admin ? route('admin.dashboard') : 
                (auth()->user()->is_verifikator ? route('verifikator.dashboard') : 
                route('user.dashboard')) 
           }}">
            <i class="fas fa-home"></i>Dashboard
        </a>
    </li>
    
    <!-- Hanya tampilkan menu upload untuk user biasa (non-admin dan non-verifikator) -->
    @if(auth()->check() && !auth()->user()->is_admin && !auth()->user()->is_verifikator)
    <li class="nav-item">
        <a class="nav-link {{ request()->is('upload-dokumen*') ? 'active' : '' }}" href="{{ route('dokumen-saya.create') }}">
            <i class="fas fa-upload"></i>Upload Dokumen
        </a>
    </li>
    @endif
    
    <li class="nav-item">
        <a class="nav-link {{ request()->is('dokumen-saya*') ? 'active' : '' }}" href="{{ route('dokumen-saya.index') }}">
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
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="fas fa-users-cog"></i>Kelola User
        </a>
    </li>
    @endif

    <!-- VERIFIKATOR MENU - Hanya tampil untuk verifikator -->
    @if(auth()->check() && auth()->user()->is_verifikator)
    <li class="nav-item mt-3">
        <small class="text-muted px-3">VERIFIKATOR</small>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('verifikator/review*') ? 'active' : '' }}" href="{{ route('verifikator.review.pending') }}">
            <i class="fas fa-check-circle"></i>Review Dokumen
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('verifikator/statistik*') ? 'active' : '' }}" href="{{ route('verifikator.statistik.index') }}">
            <i class="fas fa-chart-bar"></i>Statistik
        </a>
    </li>
    @endif

    <li class="nav-item mt-3">
        <small class="text-muted px-3">SPMI - PPEPP</small>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('spmi/penetapan*') ? 'active' : '' }}" 
           href="{{ route('spmi.penetapan.index') }}">
            <i class="fas fa-file-signature"></i>Penetapan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('spmi/pelaksanaan*') ? 'active' : '' }}" 
           href="{{ route('spmi.pelaksanaan.index') }}">
            <i class="fas fa-play-circle"></i>Pelaksanaan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('spmi/evaluasi*') ? 'active' : '' }}" 
           href="{{ route('spmi.evaluasi.index') }}">
            <i class="fas fa-chart-bar"></i>Evaluasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('spmi/pengendalian*') ? 'active' : '' }}" 
           href="{{ route('spmi.pengendalian.index') }}">
            <i class="fas fa-sliders-h"></i>Pengendalian
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('spmi/peningkatan*') ? 'active' : '' }}" 
           href="{{ route('spmi.peningkatan.index') }}">
            <i class="fas fa-chart-line"></i>Peningkatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('spmi/akreditasi*') ? 'active' : '' }}" 
           href="{{ route('spmi.akreditasi.index') }}">
            <i class="fas fa-award"></i>Akreditasi
        </a>
    </li>

    <!-- Setting Menu - Hanya untuk Admin -->
    @if(auth()->check() && auth()->user()->is_admin)
    <li class="nav-item mt-3">
        <small class="text-muted px-3">PENGATURAN SISTEM</small>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/settings/iku*') ? 'active' : '' }}" href="{{ route('admin.settings.iku.index') }}">
            <i class="fas fa-chart-line"></i>Kelola IKU
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/settings/unit-kerja*') ? 'active' : '' }}" href="{{ route('admin.settings.unit-kerja.index') }}">
            <i class="fas fa-building"></i>Kelola Unit Kerja
        </a>
    </li>
    @endif
    
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