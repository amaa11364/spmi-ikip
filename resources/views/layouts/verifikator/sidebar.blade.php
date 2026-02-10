<!-- resources/views/layouts/verifikator/sidebar.blade.php -->
<nav id="sidebar" class="sidebar">
    <div class="sidebar-content">
        <a class="sidebar-brand" href="{{ route('verifikator.dashboard') }}">
            <i class="fas fa-user-check"></i> 
            <span>Dashboard Verifikator</span>
        </a>
        
        <ul class="sidebar-nav">
            <li class="sidebar-item {{ request()->routeIs('verifikator.dashboard') ? 'active' : '' }}">
                <a href="{{ route('verifikator.dashboard') }}" class="sidebar-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="sidebar-header">Manajemen Dokumen</li>
            
            <li class="sidebar-item {{ request()->routeIs('verifikator.dokumen.index') ? 'active' : '' }}">
                <a href="{{ route('verifikator.dokumen.index') }}" class="sidebar-link">
                    <i class="fas fa-file-alt"></i> Review Dokumen
                    @php
                        $pendingCount = \App\Models\Dokumen::where('unit_kerja_id', auth()->user()->unit_kerja_id)
                            ->where('status', 'pending')
                            ->count();
                    @endphp
                    @if($pendingCount > 0)
                    <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            
            <li class="sidebar-item {{ request()->routeIs('verifikator.dokumen.statistics') ? 'active' : '' }}">
                <a href="{{ route('verifikator.dokumen.statistics') }}" class="sidebar-link">
                    <i class="fas fa-chart-bar"></i> Statistik
                </a>
            </li>
            
            <li class="sidebar-header">Akun</li>
            
            <li class="sidebar-item">
                <a href="{{ route('profile.edit') }}" class="sidebar-link">
                    <i class="fas fa-user"></i> Profil
                </a>
            </li>
            
            <li class="sidebar-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link btn btn-link" style="text-align: left;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>