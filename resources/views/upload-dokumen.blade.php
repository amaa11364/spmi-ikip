@extends('layouts.main')

@section('title', 'Upload Dokumen')

@push('styles')
<style>
    .upload-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        border: 2px dashed #e9ecef;
        transition: all 0.3s ease;
    }
    
    .upload-container:hover {
        border-color: var(--primary-brown);
    }
    
    .upload-icon {
        font-size: 4rem;
        color: var(--primary-brown);
        margin-bottom: 1rem;
    }
    
    .file-info {
        background: var(--light-brown);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
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
    .upload-container {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        border: 2px dashed #e9ecef;
        transition: all 0.3s ease;
    }
    
    .upload-container:hover {
        border-color: var(--primary-brown);
    }
    
    .upload-icon {
        font-size: 4rem;
        color: var(--primary-brown);
        margin-bottom: 1rem;
    }
    
    .file-info {
        background: var(--light-brown);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
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
    
    /* Hanya responsive */
    @media (max-width: 768px) {
        .upload-container {
            padding: 1.5rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .upload-container {
            padding: 1rem;
        }
        
        .upload-icon {
            font-size: 3rem;
        }
        
        .d-grid.d-md-flex {
            flex-direction: column;
        }
        
        .d-grid.d-md-flex .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
           
            <div>
                <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-folder me-2"></i>Dokumen Saya
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </div>
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

<div class="row justify-content-center">
    <div class="col-lg-8 col-12">
        <div class="upload-container">
            <div class="upload-icon text-center">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            
            <form action="{{ route('upload-dokumen.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <!-- DROPDOWN UNIT KERJA -->
                <div class="mb-4">
                    <label for="unit_kerja_id" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                    <select class="form-select" id="unit_kerja_id" name="unit_kerja_id" required>
                        <option value="" selected disabled>Pilih Unit Kerja</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->nama }} ({{ $unit->kode }})</option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        Pilih unit kerja Anda
                    </div>
                </div>

                <!-- DROPDOWN IKU -->
                <div class="mb-4">
                    <label for="iku_id" class="form-label">Indikator Kinerja Utama (IKU) <span class="text-danger">*</span></label>
                    <select class="form-select" id="iku_id" name="iku_id" required>
                        <option value="" selected disabled>Pilih IKU</option>
                        @foreach($ikus as $iku)
                            <option value="{{ $iku->id }}">{{ $iku->kode }} - {{ $iku->nama }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        Pilih IKU yang relevan dengan dokumen ini
                    </div>
                </div>

                <!-- INPUT UPLOAD FILE -->
                <div class="mb-4">
                    <label for="file_dokumen" class="form-label">File Dokumen <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="file_dokumen" name="file_dokumen" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                    <div class="form-text">
                        Format file yang didukung: PDF, DOC, DOCX, XLS, XLSX. Maksimal ukuran: 10MB.
                    </div>
                    @error('file_dokumen')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- INPUT NAMA DOKUMEN (OTOMATIS DARI NAMA FILE) -->
                <div class="mb-4">
                    <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" 
                           placeholder="Nama dokumen akan terisi otomatis" readonly required>
                    <div class="form-text">
                        Nama dokumen akan terisi otomatis dari nama file
                    </div>
                </div>

                <!-- File Info Preview -->
                <div class="file-info d-none" id="fileInfo">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file me-3" id="fileIcon"></i>
                        <div class="text-start">
                            <h6 class="mb-1" id="fileName"></h6>
                            <small class="text-muted" id="fileSize"></small>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-upload me-2"></i>Upload Dokumen
                    </button>
                    <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File info preview dan auto-fill nama dokumen
    document.getElementById('file_dokumen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');
        const namaDokumenInput = document.getElementById('nama_dokumen');
        
        if (file) {
            // Validasi file type
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.xls', '.xlsx'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Format file tidak didukung. Hanya file PDF, DOC, DOCX, XLS, dan XLSX yang diperbolehkan.');
                this.value = ''; // Clear input file
                fileInfo.classList.add('d-none');
                namaDokumenInput.value = '';
                return;
            }
            
            // Validasi file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 10MB.');
                this.value = ''; // Clear input file
                fileInfo.classList.add('d-none');
                namaDokumenInput.value = '';
                return;
            }
            
            // Set file name untuk preview
            fileName.textContent = file.name;
            
            // Auto-fill nama dokumen (tanpa ekstensi)
            const fileNameWithoutExt = file.name.replace(/\.[^/.]+$/, "");
            namaDokumenInput.value = fileNameWithoutExt;
            
            // Set file size
            const size = file.size;
            let sizeText = '';
            if (size >= 1048576) {
                sizeText = (size / 1048576).toFixed(2) + ' MB';
            } else if (size >= 1024) {
                sizeText = (size / 1024).toFixed(2) + ' KB';
            } else {
                sizeText = size + ' bytes';
            }
            fileSize.textContent = sizeText;
            
            // Set file icon based on extension
            const extension = file.name.split('.').pop().toLowerCase();
            fileIcon.className = 'fas me-3 ' + getFileIconClass(extension);
            
            // Show file info
            fileInfo.classList.remove('d-none');
        } else {
            fileInfo.classList.add('d-none');
            namaDokumenInput.value = '';
        }
    });

    function getFileIconClass(extension) {
        switch(extension) {
            case 'pdf': return 'fa-file-pdf text-danger';
            case 'doc':
            case 'docx': return 'fa-file-word text-primary';
            case 'xls':
            case 'xlsx': return 'fa-file-excel text-success';
            default: return 'fa-file text-secondary';
        }
    }

    // Validasi sebelum submit form
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file_dokumen');
        const file = fileInput.files[0];
        
        if (file) {
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.xls', '.xlsx'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedExtensions.includes(fileExtension)) {
                e.preventDefault();
                alert('Format file tidak didukung. Hanya file PDF, DOC, DOCX, XLS, dan XLSX yang diperbolehkan.');
                return false;
            }
            
            if (file.size > 10 * 1024 * 1024) {
                e.preventDefault();
                alert('Ukuran file terlalu besar. Maksimal 10MB.');
                return false;
            }
        }
    });
</script>
@endpush