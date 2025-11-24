<nav class="navbar-custom">
    <div class="container-fluid">
        <div class="d-flex align-items-center w-100">
            <!-- Search Box -->
            <div class="search-box me-auto">
                <i class="fas fa-search text-muted me-2"></i>
                <input type="text" placeholder="Search...">
            </div>
            
         <!-- User Profile Dropdown -->
<div class="user-profile dropdown" id="userProfileDropdown">
    <div class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="user-avatar avatar-color-{{ auth()->user()->id % 6 }}"
             style="@if(auth()->user()->avatar && auth()->user()->getAvatarUrl()) background-image: url('{{ auth()->user()->getAvatarUrl() }}'); background-size: cover; @endif">
            @if(!auth()->user()->avatar || !auth()->user()->getAvatarUrl())
                {{ auth()->user()->getInitials() }}
            @endif
        </div>
        <div class="user-info">
            <div class="fw-semibold">{{ auth()->user()->name }}</div>
            <small class="text-muted">
                {{ auth()->user()->role ?? 'Administrator' }}
            </small>
        </div>
        <i class="fas fa-chevron-down ms-2 text-muted small"></i>
    </div>
    
    <!-- Dropdown Menu -->
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userProfileDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="fas fa-user-edit me-2"></i> Edit Profil
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </form>
        </li>
    </ul>
</div>
        </div>
    </div>
</nav>

<style>
    .navbar-custom {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 1rem 2rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .search-box {
        position: relative;
        max-width: 500px;
        width: 100%;
    }
    
    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 25px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .search-box input:focus {
        outline: none;
        border-color: var(--primary-brown);
        background-color: white;
        box-shadow: 0 0 0 0.2rem rgba(153, 102, 0, 0.1);
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .user-profile {
        position: relative;
        cursor: pointer;
        padding: 5px 10px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .user-profile:hover {
        background-color: #f8f9fa;
    }
    
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 12px;
        border: 3px solid #f8f9fa;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    /* Warna avatar berdasarkan ID user */
    .avatar-color-0 { background-color: #996600; }
    .avatar-color-1 { background-color: #aa7700; }
    .avatar-color-2 { background-color: #bb8800; }
    .avatar-color-3 { background-color: #cc9900; }
    .avatar-color-4 { background-color: #ddaa00; }
    .avatar-color-5 { background-color: #eebb00; }
    
    .user-info {
        text-align: left;
    }
    
    .user-info .fw-semibold {
        font-size: 0.95rem;
        color: #495057;
        margin-bottom: 2px;
    }
    
    .user-info .text-muted {
        font-size: 0.8rem;
        color: #6c757d !important;
    }
    
    /* Dropdown Styles */
    .dropdown-menu {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        padding: 8px 0;
        margin-top: 10px;
        min-width: 200px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .dropdown-item {
        padding: 10px 16px;
        color: #495057;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }
    
    .dropdown-item:hover {
        background-color: rgba(153, 102, 0, 0.08);
        color: var(--primary-brown);
    }
    
    .dropdown-item i {
        width: 18px;
        margin-right: 10px;
        color: var(--primary-brown);
    }
    
    .dropdown-divider {
        margin: 6px 0;
        border-color: #e9ecef;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .navbar-custom {
            padding: 1rem;
        }
        
        .search-box {
            max-width: 300px;
            margin-right: 15px;
        }
        
        .search-box input {
            padding: 8px 15px 8px 35px;
            font-size: 0.85rem;
        }
        
        .user-info {
            display: none;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            margin-right: 0;
            font-size: 0.9rem;
        }
        
        .user-profile .fa-chevron-down {
            display: none;
        }
    }
    
    @media (max-width: 576px) {
        .navbar-custom {
            padding: 0.75rem 1rem;
        }
        
        .search-box {
            max-width: 200px;
        }
        
        .search-box input {
            padding: 6px 12px 6px 35px;
            font-size: 0.8rem;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }
        
        .dropdown-menu {
            min-width: 180px;
            right: -10px !important;
        }
    }
    
    @media (max-width: 400px) {
        .search-box {
            max-width: 150px;
        }
        
        .search-box input::placeholder {
            font-size: 0.75rem;
        }
    }
    
    /* Animation for dropdown */
    .dropdown-menu {
        animation: dropdownFadeIn 0.2s ease-in-out;
    }
    
    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Active state for user profile */
    .user-profile.show {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Initialize Bootstrap dropdown
    document.addEventListener('DOMContentLoaded', function() {
        const userProfile = document.getElementById('userProfileDropdown');
        const dropdown = new bootstrap.Dropdown(userProfile);
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!userProfile.contains(event.target)) {
                dropdown.hide();
            }
        });
        
        // Add active class when dropdown is shown
        userProfile.addEventListener('show.bs.dropdown', function() {
            this.classList.add('show');
        });
        
        userProfile.addEventListener('hide.bs.dropdown', function() {
            this.classList.remove('show');
        });
    });
    
    // Search functionality (optional)
    document.querySelector('.search-box input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm) {
                // Implement search functionality here
                console.log('Searching for:', searchTerm);
                // window.location.href = '/search?q=' + encodeURIComponent(searchTerm);
            }
        }
    });
    
    // Logout confirmation
    document.getElementById('logoutForm').addEventListener('submit', function(e) {
        if (!confirm('Apakah Anda yakin ingin logout?')) {
            e.preventDefault();
        }
    });
</script>