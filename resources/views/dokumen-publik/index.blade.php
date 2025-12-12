@extends('layouts.app')

@section('title', 'Dokumen Publik SPMI')

@push('styles')
<style>
    .public-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .filter-toggle {
        background: white;
        border: 2px solid var(--primary-brown);
        color: var(--primary-brown);
        border-radius: 8px;
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .filter-toggle:hover {
        background: var(--primary-brown);
        color: white;
    }
    
    .filter-toggle.active {
        background: var(--primary-brown);
        color: white;
    }
    
    .file-icon-cell {
        width: 50px;
        text-align: center;
    }
    
    .file-icon {
        font-size: 1.5rem;
    }
    
    .actions-cell {
        width: 120px;
        text-align: center;
    }
    
    .no-documents {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }
    
    .document-name {
        font-weight: 600;
        color: #495057;
    }
    
    .document-type {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .guest-notice {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffecb5;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 2rem;
    }

    .login-modal .modal-content {
        border-radius: 15px;
        border: none;
    }
    
    .login-modal .modal-header {
        background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        border: none;
    }

    .results-info {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-brown);
    }

    /* Responsive table */
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }

    .table-custom th {
        background: #f8f9fa;
        border-bottom: 2px solid var(--primary-brown);
        font-weight: 600;
        color: #495057;
    }

    /* Pastikan tabel tetap konsisten meski data sedikit */
    .table-custom {
        min-height: 400px;
    }

    .table-custom tbody {
        min-height: 300px;
    }

    /* Mobile card view */
    .mobile-card {
        display: none;
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }

    .mobile-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .mobile-file-icon {
        font-size: 2rem;
        margin-right: 1rem;
        color: var(--primary-brown);
    }

    .mobile-document-info {
        flex: 1;
    }

    .mobile-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    /* Responsive buttons */
    .btn-responsive {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        display: none;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid var(--primary-brown);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .public-header {
            padding: 1rem;
            text-align: center;
        }
        
        .public-header .text-md-end {
            text-align: center !important;
            margin-top: 1rem;
        }
        
        .filter-section {
            padding: 1rem;
        }
        
        .table-desktop {
            display: none;
        }
        
        .mobile-card {
            display: block;
        }
        
        .results-info h5 {
            font-size: 1rem;
        }
        
        .guest-notice .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        .guest-notice i {
            margin-bottom: 0.5rem;
            margin-right: 0 !important;
        }
        
        /* Mobile button group */
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .public-header h2 {
            font-size: 1.5rem;
        }
        
        .filter-section .col-md-8,
        .filter-section .col-md-4 {
            margin-bottom: 1rem;
        }
        
        .input-group .btn {
            padding: 0.5rem 1rem;
        }
        
        .mobile-card {
            padding: 0.75rem;
        }
        
        .mobile-file-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
    }

    /* Desktop optimizations */
    @media (min-width: 769px) {
        .mobile-card {
            display: none;
        }
        
        .table-desktop {
            display: table;
        }
    }
</style>
@endpush

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Public Header -->
<div class="public-header">
    <div class="row align-items-center">
        <div class="col-md-8 col-12">
            <h2 class="fw-bold mb-2">
                <i class="fas fa-globe me-2"></i>Dokumen Publik SPMI
            </h2>
            <p class="mb-0">Akses dokumen SPMI yang tersedia untuk umum</p>
        </div>
        <div class="col-md-4 col-12 text-md-end text-center mt-2 mt-md-0">
            @auth
                <a href="{{ route('dokumen-saya') }}" class="btn btn-light btn-responsive me-2 mb-2 mb-md-0">
                    <i class="fas fa-folder me-2"></i>Dokumen Saya
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-responsive">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            @else
                <a href="{{ route('landing.page') }}" class="btn btn-light btn-responsive me-2 mb-2 mb-md-0">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
                <a href="{{ route('masuk') }}" class="btn btn-outline-light btn-responsive"
                   onclick="sessionStorage.setItem('login_redirect', window.location.href)">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Guest Notice -->
@guest
<div class="guest-notice">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle fa-2x text-warning me-3"></i>
        <div>
            <h6 class="fw-bold mb-1">Anda sedang mengakses sebagai tamu</h6>
            <p class="mb-0">Anda dapat melihat daftar dan detail dokumen. Untuk mengunduh atau melihat preview dokumen, silakan login terlebih dahulu.</p>
        </div>
    </div>
</div>
@endguest

<!-- Search & Filter Section -->
<div class="filter-section">
    <form id="searchForm" class="row g-3 align-items-end">
        <div class="col-md-8 col-12">
            <label class="form-label fw-semibold">Cari Dokumen</label>
            <div class="input-group">
                <input type="text" class="form-control" name="search" id="searchInput"
                       placeholder="Ketik nama dokumen, jenis, unit kerja, atau IKU..." 
                       value="{{ request('search') }}"
                       autocomplete="off">
                <button type="button" class="btn btn-primary" id="searchButton">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
        </div>
        
        <div class="col-md-4 col-12">
            <button type="button" class="btn filter-toggle" id="filterToggle">
                <i class="fas fa-filter me-2"></i>Filter Lanjutan
            </button>
        </div>

        <!-- Advanced Filters -->
        <div class="col-12 mt-3" id="advancedFilters" style="display: none;">
            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold">Unit Kerja</label>
                    <select class="form-select" name="unit_kerja" id="unitKerjaFilter">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold">IKU</label>
                    <select class="form-select" name="iku_id" id="ikuFilter">
                        <option value="">Semua IKU</option>
                        @foreach($ikus as $iku)
                            <option value="{{ $iku->id }}" {{ request('iku_id') == $iku->id ? 'selected' : '' }}>
                                {{ $iku->kode }} - {{ $iku->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4 col-12 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-primary flex-grow-1" id="applyFilter">
                            <i class="fas fa-filter me-1"></i>Terapkan Filter
                        </button>
                        @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                            <a href="{{ route('dokumen-publik.index') }}" class="btn btn-outline-danger" id="resetFilter">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Results Info -->
<div class="results-info">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0" id="resultsText">
                @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
                    <i class="fas fa-search me-2"></i>
                    Hasil Pencarian
                    @if(request('search'))
                        untuk "{{ request('search') }}"
                    @endif
                    <span class="badge bg-primary ms-2" id="resultsCount">{{ $dokumens->total() }} dokumen ditemukan</span>
                @else
                    <i class="fas fa-files me-2"></i>
                    Semua Dokumen Publik
                    <span class="badge bg-success ms-2" id="resultsCount">{{ $dokumens->total() }} dokumen</span>
                @endif
            </h5>
        </div>
        @if(request()->hasAny(['search', 'unit_kerja', 'iku_id']))
            <a href="{{ route('dokumen-publik.index') }}" class="btn btn-outline-secondary btn-sm" id="resetResults">
                <i class="fas fa-times me-1"></i>Reset
            </a>
        @endif
    </div>
</div>

<!-- Desktop Table View -->
<div class="table-responsive table-desktop">
    <table class="table table-custom table-hover">
        <thead>
            <tr>
                <th class="file-icon-cell"></th>
                <th>Nama Dokumen</th>
                <th>Unit Kerja</th>
                <th>IKU</th>
                <th>Ukuran</th>
                <th>Uploader</th>
                <th>Tanggal Upload</th>
                <th class="actions-cell">Aksi</th>
            </tr>
        </thead>
        <tbody id="dokumenTableBody">
            @include('dokumen-publik.partials.dokumen-list')
        </tbody>
    </table>
</div>

<!-- Mobile Card Container -->
<div id="mobileCardView" class="d-md-none">
    <!-- Mobile cards akan diisi via JS -->
</div>

<!-- Pagination Container -->
<div id="paginationContainer">
    @include('dokumen-publik.partials.pagination')
</div>

<!-- Login Required Modal -->
<div class="modal fade login-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="fas fa-lock me-2"></i>Login Diperlukan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-user-lock fa-3x text-warning mb-3"></i>
                <h5 class="mb-3">Akses Terbatas</h5>
                <p class="text-muted mb-4">
                    Untuk mengakses fitur preview dan download dokumen, Anda perlu login terlebih dahulu.
                    Silakan login untuk melanjutkan.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('masuk') }}" class="btn btn-primary" id="loginRedirectBtn">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ============================================
// GLOBAL FUNCTIONS FOR INLINE ONCLICK
// ============================================

// Function untuk pagination links di blade template
window.handlePaginationGlobal = function(event, url) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('📄 Handling pagination via inline onclick:', url);
    
    // Extract page number from URL
    const urlObj = new URL(url, window.location.origin);
    const page = urlObj.searchParams.get('page') || 1;
    
    // Load data for the page
    loadData(page);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    return false;
};

// Function untuk search button
window.performSearch = function() {
    loadData();
};

// ============================================
// DOKUMEN PUBLIK MANAGER
// ============================================

// Deklarasi variabel global
let loginModal = null;
let currentPage = 1;

// ============================================
// SETUP EVENT LISTENERS
// ============================================

function setupEventListeners() {
    console.log('🔄 Setting up event listeners');
    
    // Filter toggle
    const filterToggle = document.getElementById('filterToggle');
    const advancedFilters = document.getElementById('advancedFilters');
    
    if (filterToggle && advancedFilters) {
        filterToggle.addEventListener('click', function() {
            const isHidden = advancedFilters.style.display === 'none';
            advancedFilters.style.display = isHidden ? 'block' : 'none';
            filterToggle.classList.toggle('active');
            filterToggle.innerHTML = isHidden 
                ? '<i class="fas fa-times me-2"></i>Tutup Filter'
                : '<i class="fas fa-filter me-2"></i>Filter Lanjutan';
        });
    }
    
    // Search input - enter key
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                loadData();
            }
        });
        
        // Real-time search with debounce
        let searchTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    loadData();
                }
            }, 500);
        });
    }
    
    // Search button
    const searchButton = document.getElementById('searchButton');
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            loadData();
        });
    }
    
    // Apply filter button
    const applyFilter = document.getElementById('applyFilter');
    if (applyFilter) {
        applyFilter.addEventListener('click', function(e) {
            e.preventDefault();
            loadData();
        });
    }
    
    // Reset buttons
    const resetButtons = document.querySelectorAll('#resetFilter, #resetResults');
    resetButtons.forEach(button => {
        if (button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                resetFilters();
            });
        }
    });
    
    // Filter changes
    const unitFilter = document.getElementById('unitKerjaFilter');
    const ikuFilter = document.getElementById('ikuFilter');
    
    if (unitFilter) {
        unitFilter.addEventListener('change', function() {
            loadData();
        });
    }
    
    if (ikuFilter) {
        ikuFilter.addEventListener('change', function() {
            loadData();
        });
    }
    
    // Login redirect button in modal
    const loginRedirectBtn = document.getElementById('loginRedirectBtn');
    if (loginRedirectBtn) {
        loginRedirectBtn.addEventListener('click', function() {
            sessionStorage.setItem('login_redirect', window.location.href);
        });
    }
}

// ============================================
// MAIN DATA LOADING FUNCTION
// ============================================

function loadData(page = 1) {
    console.log('🔍 Loading data, page:', page);
    currentPage = page;
    
    // Show loading
    showLoading(true);
    
    // Get search values
    const search = document.getElementById('searchInput')?.value || '';
    const unitKerja = document.getElementById('unitKerjaFilter')?.value || '';
    const iku = document.getElementById('ikuFilter')?.value || '';
    
    // Build URL
    const url = '{{ route("dokumen-publik.index") }}';
    const params = new URLSearchParams();
    
    if (search) params.append('search', search);
    if (unitKerja) params.append('unit_kerja', unitKerja);
    if (iku) params.append('iku_id', iku);
    if (page > 1) params.append('page', page);
    params.append('ajax', '1');
    
    const requestUrl = `${url}?${params.toString()}`;
    
    console.log('📡 Request URL:', requestUrl);
    
    // Update browser URL
    updateBrowserURL(search, unitKerja, iku, page);
    
    // Make AJAX request
    fetch(requestUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        cache: 'no-store'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Response received:', { 
            success: data.success,
            total: data.total,
            htmlLength: data.html?.length || 0
        });
        
        if (data.success) {
            // Update table body
            updateTableBody(data.html);
            
            // Update mobile cards
            updateMobileCards(data.html);
            
            // Update pagination
            updatePagination(data.pagination);
            
            // Update results info
            updateResultsInfo(data.total, search);
            
            // Re-attach dynamic event listeners
            attachDynamicEventListeners();
        } else {
            showError('Gagal memuat data: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('❌ AJAX Error:', error);
        showError('Gagal memuat data: ' + error.message);
    })
    .finally(() => {
        showLoading(false);
    });
}

// ============================================
// PAGINATION HANDLER
// ============================================

function handlePagination(event, pageUrl) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('📄 Handling pagination:', pageUrl);
    
    // Extract page number from URL
    const url = new URL(pageUrl, window.location.origin);
    const page = url.searchParams.get('page') || 1;
    
    // Load data for the page
    loadData(page);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    return false;
}

// ============================================
// UI UPDATE FUNCTIONS
// ============================================

function updateTableBody(html) {
    const tableBody = document.getElementById('dokumenTableBody');
    if (tableBody) {
        tableBody.innerHTML = html;
        console.log('🔄 Table body updated');
    }
}

function updateMobileCards(html) {
    // Create temporary element to parse HTML
    const temp = document.createElement('div');
    temp.innerHTML = html;
    
    // Find all mobile cards in the response
    const mobileCards = temp.querySelectorAll('.mobile-card');
    const mobileContainer = document.getElementById('mobileCardView');
    
    if (mobileContainer) {
        if (mobileCards.length > 0) {
            mobileContainer.innerHTML = '';
            mobileCards.forEach(card => {
                mobileContainer.appendChild(card.cloneNode(true));
            });
            console.log('📱 Mobile cards updated:', mobileCards.length);
        } else {
            // If no mobile cards found, check for no-documents message
            const noDocuments = temp.querySelector('.no-documents');
            if (noDocuments) {
                mobileContainer.innerHTML = noDocuments.outerHTML;
            }
        }
    }
}

function updatePagination(paginationHtml) {
    const paginationContainer = document.getElementById('paginationContainer');
    if (paginationContainer) {
        paginationContainer.innerHTML = paginationHtml || '';
        console.log('🔗 Pagination updated');
    }
}

function updateResultsInfo(total, search) {
    const resultsCount = document.getElementById('resultsCount');
    const resultsText = document.getElementById('resultsText');
    
    if (resultsCount) {
        resultsCount.textContent = `${total} dokumen ditemukan`;
    }
    
    if (resultsText) {
        const unitKerja = document.getElementById('unitKerjaFilter')?.value || '';
        const iku = document.getElementById('ikuFilter')?.value || '';
        
        if (search || unitKerja || iku) {
            let text = '<i class="fas fa-search me-2"></i>Hasil Pencarian';
            
            if (search) {
                text += ` untuk "${search}"`;
            }
            
            text += ` <span class="badge bg-primary ms-2">${total} dokumen</span>`;
            resultsText.innerHTML = text;
        } else {
            resultsText.innerHTML = `<i class="fas fa-files me-2"></i>Semua Dokumen Publik <span class="badge bg-success ms-2">${total} dokumen</span>`;
        }
    }
}

// ============================================
// HELPER FUNCTIONS
// ============================================

function showLoading(show) {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const searchButton = document.getElementById('searchButton');
    
    if (loadingOverlay) {
        loadingOverlay.style.display = show ? 'flex' : 'none';
    }
    
    if (searchButton) {
        if (show) {
            searchButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
            searchButton.disabled = true;
        } else {
            searchButton.innerHTML = '<i class="fas fa-search me-1"></i>Cari';
            searchButton.disabled = false;
        }
    }
}

function showError(message) {
    // Create error alert
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error!</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert after results info
    const resultsInfo = document.querySelector('.results-info');
    if (resultsInfo) {
        resultsInfo.parentNode.insertBefore(alertDiv, resultsInfo.nextSibling);
    }
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

function resetFilters() {
    // Reset form values
    if (document.getElementById('searchInput')) {
        document.getElementById('searchInput').value = '';
    }
    if (document.getElementById('unitKerjaFilter')) {
        document.getElementById('unitKerjaFilter').value = '';
    }
    if (document.getElementById('ikuFilter')) {
        document.getElementById('ikuFilter').value = '';
    }
    
    // Hide advanced filters
    const advancedFilters = document.getElementById('advancedFilters');
    const filterToggle = document.getElementById('filterToggle');
    if (advancedFilters && filterToggle) {
        advancedFilters.style.display = 'none';
        filterToggle.classList.remove('active');
        filterToggle.innerHTML = '<i class="fas fa-filter me-2"></i>Filter Lanjutan';
    }
    
    // Update URL
    window.history.replaceState({}, '', '{{ route("dokumen-publik.index") }}');
    
    // Load data
    loadData();
}

function updateBrowserURL(search, unitKerja, iku, page = 1) {
    const params = new URLSearchParams();
    
    if (search) params.set('search', search);
    if (unitKerja) params.set('unit_kerja', unitKerja);
    if (iku) params.set('iku_id', iku);
    if (page > 1) params.set('page', page);
    
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.replaceState({}, '', newUrl);
    
    console.log('🔗 URL updated:', newUrl);
}

function attachDynamicEventListeners() {
    console.log('🔗 Attaching dynamic event listeners');
    
    // Event delegation untuk require-login buttons
    document.addEventListener('click', function(e) {
        const requireLoginBtn = e.target.closest('.require-login');
        if (requireLoginBtn && loginModal) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('🔐 Require-login button clicked');
            
            // Simpan URL untuk redirect
            sessionStorage.setItem('login_redirect', window.location.href);
            
            // Tampilkan modal login
            loginModal.show();
            
            return false;
        }
    });
    
    // Attach pagination links
    document.querySelectorAll('.page-link[href]').forEach(link => {
        // Hapus event listener lama jika ada
        const newLink = link.cloneNode(true);
        link.parentNode.replaceChild(newLink, link);
        
        // Tambah event listener baru
        newLink.addEventListener('click', function(e) {
            e.preventDefault();
            const pageUrl = this.href;
            const urlObj = new URL(pageUrl, window.location.origin);
            const page = urlObj.searchParams.get('page') || 1;
            loadData(page);
        });
    });
    
    // Attach detail modal buttons
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const target = this.getAttribute('data-bs-target');
            const modalElement = document.querySelector(target);
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
    });
    
    console.log('✅ Dynamic event listeners attached');
}

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Dokumen Publik Manager initialized');
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    
    // Inisialisasi modal login
    const loginModalElement = document.getElementById('loginModal');
    if (loginModalElement) {
        loginModal = new bootstrap.Modal(loginModalElement);
        console.log('✅ Login modal initialized');
    } else {
        console.error('❌ Login modal element not found');
    }
    
    // Setup event listeners
    setupEventListeners();
    
    // Auto-load data if there are search params in URL
    const urlParams = new URLSearchParams(window.location.search);
    const hasSearchParams = urlParams.has('search') || 
                           urlParams.has('unit_kerja') || 
                           urlParams.has('iku_id');
    
    if (hasSearchParams) {
        console.log('🔄 Auto-loading data from URL params');
        loadData();
    }
    
    // Initial load mobile cards from existing HTML
    const tableBody = document.getElementById('dokumenTableBody');
    if (tableBody) {
        updateMobileCards(tableBody.innerHTML);
    }
    
    console.log('✅ Dokumen Publik Manager ready!');
});
</script>
@endpush