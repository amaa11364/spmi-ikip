<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen - Q-TRACK SPMI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-brown: #996600;
            --secondary-brown: #b37400;
            --dark-brown: #7a5200;
            --light-brown: #fff9e6;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            min-height: 100vh;
            padding: 0;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 1.5rem;
            margin: 4px 0;
            border-radius: 0;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid white;
        }
        
        .sidebar-menu .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 0;
        }
        
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        
        .search-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            padding: 8px 20px;
        }
        
        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            width: 250px;
        }
        
        .user-profile {
            background: var(--light-brown);
            border-radius: 50px;
            padding: 8px 15px 8px 8px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .upload-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }
        
        .document-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #e9ecef;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-brown);
            box-shadow: 0 0 0 0.2rem rgba(153, 102, 0, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-brown) 0%, var(--primary-brown) 100%);
        }
        
        .document-item {
            border-left: 4px solid var(--primary-brown);
            padding-left: 15px;
            margin-bottom: 15px;
        }
        
        .file-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        
        .avatar-color-0 { background: #996600; }
        .avatar-color-1 { background: #aa7700; }
        .avatar-color-2 { background: #bb8800; }
        .avatar-color-3 { background: #cc9900; }
        .avatar-color-4 { background: #ddaa00; }
        .avatar-color-5 { background: #eebb00; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="sidebar-header">
                    <h3 class="fw-bold mb-0">Q-TRACK</h3>
                    <small class="opacity-75">SPMI Digital</small>
                </div>
                
                <nav class="sidebar-menu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>Home page
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('upload-dokumen') }}">
                                <i class="fas fa-upload"></i>Upload Dokumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i>Pengaturan
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Top Navigation -->
                <nav class="navbar-custom">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center w-100">
                            <div class="search-box me-auto">
                                <i class="fas fa-search text-muted me-2"></i>
                                <input type="text" placeholder="Search...">
                            </div>
                            
                            <div class="user-profile">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar avatar-color-{{ auth()->user()->id % 6 }}">
                                        @php
                                            $name = auth()->user()->name;
                                            $words = explode(' ', $name);
                                            $initials = '';
                                            foreach($words as $word) {
                                                $initials .= strtoupper(substr($word, 0, 1));
                                            }
                                            echo substr($initials, 0, 2);
                                        @endphp
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">
                                            {{ auth()->user()->role ?? 'Administrator' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content Area -->
                <div class="container-fluid mt-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold">Upload Dokumen Mutu</h4>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Upload Form -->
                        <div class="col-lg-6">
                            <div class="upload-card">
                                <h5 class="fw-bold mb-4">Upload Dokumen Baru</h5>
                                <form action="{{ route('upload-dokumen.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label for="program_studi" class="form-label">Program Studi</label>
                                        <select class="form-select" id="program_studi" name="program_studi" required>
                                            <option value="" selected disabled>Pilih Program Studi</option>
                                            @foreach($programStudi as $prodi)
                                                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                                        <select class="form-select" id="jenis_dokumen" name="jenis_dokumen" required>
                                            <option value="" selected disabled>Pilih Jenis Dokumen</option>
                                            <option value="standar_mutu">Standar Mutu</option>
                                            <option value="laporan_audit">Laporan Audit</option>
                                            <option value="dokumen_akreditasi">Dokumen Akreditasi</option>
                                            <option value="prosedur_operasional">Prosedur Operasional</option>
                                            <option value="lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                                        <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" 
                                               placeholder="Masukkan nama dokumen" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="file_dokumen" class="form-label">File Dokumen</label>
                                        <input type="file" class="form-control" id="file_dokumen" name="file_dokumen" 
                                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                                        <div class="form-text">
                                            Format file yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal ukuran: 5MB.
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-upload me-2"></i>Upload Dokumen
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Documents List -->
                        <div class="col-lg-6">
                            <div class="upload-card">
                                <h5 class="fw-bold mb-4">Daftar Dokumen Saya</h5>
                                
                                @if($dokumens->count() > 0)
                                    @foreach($dokumens as $dokumen)
                                        <div class="document-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center">
                                                        <span class="file-icon">
                                                            <i class="{{ $dokumen->file_icon }}"></i>
                                                        </span>
                                                        <div>
                                                            <h6 class="mb-1">{{ $dokumen->nama_dokumen }}</h6>
                                                            <small class="text-muted">
                                                                Program Studi: {{ $dokumen->programStudi->nama }} | 
                                                                Jenis: {{ $dokumen->jenis_dokumen }} | 
                                                                Ukuran: {{ $dokumen->file_size_formatted }} | 
                                                                Upload: {{ $dokumen->created_at->format('d/m/Y H:i') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ms-3">
                                                    <a href="{{ route('upload-dokumen.download', $dokumen->id) }}" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <form action="{{ route('upload-dokumen.destroy', $dokumen->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Yakin ingin menghapus dokumen ini?')"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada dokumen yang diupload.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>