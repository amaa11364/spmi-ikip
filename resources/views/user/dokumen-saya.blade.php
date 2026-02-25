@extends('layouts.main')

@section('title', 'Manajemen Dokumen SPMI')

@push('styles')
<style>
    :root {
        --primary: #4361ee;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --light: #f8fafc;
        --gray: #64748b;
    }

    .documents-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-icon.pending { background: rgba(245, 158, 11, 0.15); color: var(--warning); }
    .stat-icon.approved { background: rgba(16, 185, 129, 0.15); color: var(--success); }
    .stat-icon.rejected { background: rgba(239, 68, 68, 0.15); color: var(--danger); }
    .stat-icon.total { background: rgba(67, 97, 238, 0.15); color: var(--primary); }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-info p {
        color: var(--gray);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .filter-group {
        position: relative;
    }

    .filter-group i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        z-index: 1;
    }

    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 0.95rem;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .search-box input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 0.95rem;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table-header {
        padding: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-header h4 i {
        color: var(--primary);
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        padding: 1.2rem 1.5rem;
        background: #f8fafc;
        color: var(--dark);
        font-weight: 600;
        font-size: 0.9rem;
        border-bottom: 2px solid #e2e8f0;
    }

    td {
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        color: var(--dark);
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background: #f8fafc;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        gap: 0.5rem;
    }

    .status-badge.pending {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning);
    }

    .status-badge.approved {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success);
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, 0.15);
        color: var(--danger);
    }

    /* Tahapan Badges */
    .tahapan-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        gap: 0.4rem;
    }

    .tahapan-badge.penetapan { background: rgba(67, 97, 238, 0.1); color: var(--primary); }
    .tahapan-badge.pelaksanaan { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .tahapan-badge.evaluasi { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
    .tahapan-badge.pengendalian { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .tahapan-badge.peningkatan { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e2e8f0;
        background: white;
        color: var(--gray);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-icon:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-2px);
    }

    .btn-icon.view:hover { border-color: var(--info); color: var(--info); }
    .btn-icon.verify:hover { border-color: var(--success); color: var(--success); }
    .btn-icon.reject:hover { border-color: var(--danger); color: var(--danger); }

    /* Document Info */
    .doc-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .doc-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .doc-icon.pdf { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .doc-icon.word { background: rgba(59, 130, 246, 0.1); color: var(--info); }
    .doc-icon.excel { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .doc-icon.image { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .doc-details h6 {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: var(--dark);
    }

    .doc-details small {
        color: var(--gray);
        font-size: 0.8rem;
    }

    /* Verification Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        border-radius: 24px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h5 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modal-header h5 i {
        color: var(--primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray);
        padding: 0.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: #f1f5f9;
        color: var(--danger);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 2px solid #e2e8f0;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 14px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #3a0ca3);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(67, 97, 238, 0.4);
    }

    .btn-success {
        background: var(--success);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
    }

    .btn-outline {
        background: white;
        border: 2px solid #e2e8f0;
        color: var(--gray);
    }

    .btn-outline:hover {
        border-color: var(--dark);
        color: var(--dark);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .documents-container {
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            padding: 1rem;
            gap: 1rem;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 1.3rem;
        }

        .stat-info h3 {
            font-size: 1.3rem;
        }

        .stat-info p {
            font-size: 0.8rem;
        }

        th, td {
            padding: 1rem;
        }

        .doc-info {
            gap: 0.75rem;
        }

        .doc-icon {
            width: 35px;
            height: 35px;
            font-size: 1.1rem;
        }

        .doc-details h6 {
            font-size: 0.9rem;
        }

        .action-buttons {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .modal-content {
            width: 95%;
        }
    }
</style>
@endpush

@section('content')
<div class="documents-container">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['total'] ?? 0 }}</h3>
                <p>Total Dokumen</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['pending'] ?? 0 }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon approved">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['approved'] ?? 0 }}</h3>
                <p>Disetujui</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon rejected">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $statistics['rejected'] ?? 0 }}</h3>
                <p>Ditolak</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.dokumen.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari dokumen..." value="{{ request('search') }}">
                </div>
                
                <div class="filter-group">
                    <i class="fas fa-layer-group"></i>
                    <select name="tahapan" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Tahapan</option>
                        <option value="penetapan" {{ request('tahapan') == 'penetapan' ? 'selected' : '' }}>Penetapan</option>
                        <option value="pelaksanaan" {{ request('tahapan') == 'pelaksanaan' ? 'selected' : '' }}>Pelaksanaan</option>
                        <option value="evaluasi" {{ request('tahapan') == 'evaluasi' ? 'selected' : '' }}>Evaluasi</option>
                        <option value="pengendalian" {{ request('tahapan') == 'pengendalian' ? 'selected' : '' }}>Pengendalian</option>
                        <option value="peningkatan" {{ request('tahapan') == 'peningkatan' ? 'selected' : '' }}>Peningkatan</option>
                    </select>
                </div>

                <div class="filter-group">
                    <i class="fas fa-tag"></i>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="filter-group">
                    <i class="fas fa-calendar"></i>
                    <select name="sort" class="filter-select" onchange="this.form.submit()">
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Documents Table -->
    <div class="table-card">
        <div class="table-header">
            <h4>
                <i class="fas fa-list"></i>
                Daftar Dokumen
            </h4>
            <a href="{{ route('user.upload-dokumen.store') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                <i class="fas fa-plus-circle me-2"></i>
                Upload Baru
            </a>
        </div>

        @if($dokumens->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Dokumen</th>
                        <th>Tahapan</th>
                        <th>Uploader</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dokumens as $dokumen)
                    <tr>
                        <td>
                            <div class="doc-info">
                                @php
                                    $ext = strtolower($dokumen->file_extension);
                                    $iconClass = 'fas fa-file-alt';
                                    $iconBg = 'doc-icon';
                                    
                                    if ($ext == 'pdf') {
                                        $iconClass = 'fas fa-file-pdf';
                                        $iconBg .= ' pdf';
                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                        $iconClass = 'fas fa-file-word';
                                        $iconBg .= ' word';
                                    } elseif (in_array($ext, ['xls', 'xlsx'])) {
                                        $iconClass = 'fas fa-file-excel';
                                        $iconBg .= ' excel';
                                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                        $iconClass = 'fas fa-file-image';
                                        $iconBg .= ' image';
                                    }
                                @endphp
                                <div class="{{ $iconBg }}">
                                    <i class="{{ $iconClass }}"></i>
                                </div>
                                <div class="doc-details">
                                    <h6>{{ $dokumen->nama_dokumen }}</h6>
                                    <small>
                                        <i class="fas fa-file me-1"></i>
                                        {{ $dokumen->file_name }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="tahapan-badge {{ $dokumen->tahapan ?? 'umum' }}">
                                <i class="fas 
                                    @switch($dokumen->tahapan)
                                        @case('penetapan') fa-folder-open @break
                                        @case('pelaksanaan') fa-play-circle @break
                                        @case('evaluasi') fa-chart-line @break
                                        @case('pengendalian') fa-tasks @break
                                        @case('peningkatan') fa-chart-bar @break
                                        @default fa-file
                                    @endswitch
                                "></i>
                                {{ ucfirst($dokumen->tahapan ?? 'Umum') }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $dokumen->uploader->name ?? 'System' }}</strong>
                                <br>
                                <small>{{ $dokumen->uploader->role ?? '-' }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $dokumen->created_at->format('d/m/Y') }}</strong>
                                <br>
                                <small>{{ $dokumen->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $dokumen->status ?? 'pending' }}">
                                <i class="fas 
                                    @switch($dokumen->status)
                                        @case('approved') fa-check-circle @break
                                        @case('rejected') fa-times-circle @break
                                        @default fa-clock
                                    @endswitch
                                "></i>
                                @switch($dokumen->status)
                                    @case('approved') Disetujui @break
                                    @case('rejected') Ditolak @break
                                    @default Pending
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon view" onclick="viewDocument({{ $dokumen->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($dokumen->status == 'pending')
                                <button class="btn-icon verify" onclick="verifyDocument({{ $dokumen->id }}, 'approved')" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn-icon reject" onclick="verifyDocument({{ $dokumen->id }}, 'rejected')" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif

                                <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" class="btn-icon" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dokumens->hasPages())
        <div style="padding: 1.5rem; border-top: 2px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div style="color: var(--gray); font-size: 0.9rem;">
                    Menampilkan {{ $dokumens->firstItem() }} - {{ $dokumens->lastItem() }} 
                    dari {{ $dokumens->total() }} dokumen
                </div>
                <div>
                    {{ $dokumens->links() }}
                </div>
            </div>
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h5>Belum Ada Dokumen</h5>
            <p>Mulai dengan mengupload dokumen SPMI pertama Anda</p>
            <a href="{{ route('user.upload-dokumen.store') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>
                Upload Dokumen
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Verification Modal -->
<div class="modal" id="verificationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>
                <i class="fas fa-check-circle"></i>
                <span id="modalTitle">Verifikasi Dokumen</span>
            </h5>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="verificationForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <input type="hidden" name="status" id="verificationStatus">
                <div class="form-group">
                    <label class="form-label" for="catatan">
                        <i class="fas fa-comment"></i>
                        Catatan Verifikasi
                    </label>
                    <textarea class="form-control" id="catatan" name="catatan" 
                              rows="4" placeholder="Tambahkan catatan untuk verifikasi ini..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn" id="modalActionBtn">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentDocumentId = null;

    function viewDocument(id) {
        window.open(`/dokumen/${id}/preview`, '_blank');
    }

    function verifyDocument(id, status) {
        currentDocumentId = id;
        const modal = document.getElementById('verificationModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalActionBtn = document.getElementById('modalActionBtn');
        const statusInput = document.getElementById('verificationStatus');
        
        statusInput.value = status;
        
        if (status === 'approved') {
            modalTitle.innerHTML = '<i class="fas fa-check-circle"></i> Setujui Dokumen';
            modalActionBtn.className = 'btn btn-success';
            modalActionBtn.innerHTML = '<i class="fas fa-check me-2"></i> Setujui';
        } else {
            modalTitle.innerHTML = '<i class="fas fa-times-circle"></i> Tolak Dokumen';
            modalActionBtn.className = 'btn btn-danger';
            modalActionBtn.innerHTML = '<i class="fas fa-times me-2"></i> Tolak';
        }
        
        const form = document.getElementById('verificationForm');
        form.action = `/dokumen/${id}/verify`;
        
        modal.classList.add('show');
    }

    function closeModal() {
        document.getElementById('verificationModal').classList.remove('show');
        document.getElementById('catatan').value = '';
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        const modal = document.getElementById('verificationModal');
        if (e.target === modal) {
            closeModal();
        }
    });
</script>
@endpush
@endsection