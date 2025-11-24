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
                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/upload-dokumen') ? 'active' : '' }}" href="{{ route('upload-dokumen.create') }}">
                    <i class="fas fa-upload"></i>Upload Dokumen
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin/dokumen-saya') ? 'active' : '' }}" href="{{ route('dokumen-saya') }}">
                    <i class="fas fa-folder"></i>Dokumen Saya
                </a>
            </li>
            
            <!-- Setting Menu -->
            <li class="nav-item mt-3">
                <small class="text-muted px-3">PENGATURAN</small>
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
        </ul>
    </nav>
</div>