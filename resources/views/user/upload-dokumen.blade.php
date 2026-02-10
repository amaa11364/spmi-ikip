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
    
    /* Alert Styling untuk Konteks */
    .context-alert {
        border-radius: 10px;
        border-left: 5px solid;
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
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
                @if(isset($context) && $context == 'spmi-penetapan')
                    <a href="{{ route('spmi.penetapan.show', $penetapanId ?? '') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Penetapan
                    </a>
                @else
                    <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-folder me-2"></i>Dokumen Saya
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </div>
            <div>
                @if(isset($context))
                    <span class="badge bg-primary fs-6 p-2">
                        @switch($context)
                            @case('spmi-penetapan')
                                <i class="fas fa-folder-open me-1"></i> SPMI Penetapan
                                @break
                            @case('spmi-pelaksanaan')
                                <i class="fas fa-play-circle me-1"></i> SPMI Pelaksanaan
                                @break
                            @case('spmi-evaluasi')
                                <i class="fas fa-chart-line me-1"></i> SPMI Evaluasi
                                @break
                            @case('spmi-pengendalian')
                                <i class="fas fa-tasks me-1"></i> SPMI Pengendalian
                                @break
                            @case('spmi-peningkatan')
                                <i class="fas fa-chart-line me-1"></i> SPMI Peningkatan
                                @break
                        @endswitch
                    </span>
                @endif
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
                @if(isset($context))
                    @switch($context)
                        @case('spmi-penetapan') <i class="fas fa-folder-open"></i> @break
                        @case('spmi-pelaksanaan') <i class="fas fa-play-circle"></i> @break
                        @case('spmi-evaluasi') <i class="fas fa-chart-line"></i> @break
                        @case('spmi-pengendalian') <i class="fas fa-tasks"></i> @break
                        @case('spmi-peningkatan') <i class="fas fa-chart-line"></i> @break
                        @default <i class="fas fa-cloud-upload-alt"></i>
                    @endswitch
                @else
                    <i class="fas fa-cloud-upload-alt"></i>
                @endif
            </div>
            
            <!-- ===================== BAGIAN KONTEKS ===================== -->
            <!-- Ini adalah bagian yang harus ditambahkan di dalam form -->
            
            @if(isset($context) && $context == 'spmi-penetapan')
                <div class="alert alert-info context-alert mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-folder-open fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-upload me-2"></i>Upload ke Repository Penetapan SPMI
                            </h5>
                            <div class="mb-2">
                                <strong>Komponen:</strong> {{ $komponenNama ?? 'Tidak ditentukan' }}
                            </div>
                            <div class="mb-1">
                                <strong>Lokasi Penyimpanan:</strong> 
                                <code>storage/app/public/dokumen/spmi/penetapan/{{ $tipePenetapan ?? 'general' }}/{{ $tahun ?? date('Y') }}/</code>
                            </div>
                            <div class="small text-muted">
                                Dokumen akan otomatis terkait dengan komponen ini dan metadata akan tersimpan
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($context) && $context == 'spmi-pelaksanaan')
                <div class="alert alert-warning context-alert mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-play-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-upload me-2"></i>Upload ke Tahap Pelaksanaan SPMI
                            </h5>
                            <p class="mb-0">Dokumen implementasi dan monitoring sistem penjaminan mutu</p>
                            <div class="small text-muted mt-1">
                                <strong>Lokasi:</strong> <code>storage/app/public/dokumen/spmi/pelaksanaan/</code>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($context) && $context == 'spmi-evaluasi')
                <div class="alert alert-primary context-alert mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-upload me-2"></i>Upload ke Tahap Evaluasi SPMI
                            </h5>
                            <p class="mb-0">Dokumen audit, evaluasi, dan penilaian mutu</p>
                            <div class="small text-muted mt-1">
                                <strong>Lokasi:</strong> <code>storage/app/public/dokumen/spmi/evaluasi/</code>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($context) && $context == 'spmi-pengendalian')
                <div class="alert alert-success context-alert mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tasks fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-upload me-2"></i>Upload ke Tahap Pengendalian SPMI
                            </h5>
                            <p class="mb-0">Dokumen tindakan korektif dan pengendalian mutu</p>
                            <div class="small text-muted mt-1">
                                <strong>Lokasi:</strong> <code>storage/app/public/dokumen/spmi/pengendalian/</code>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($context) && $context == 'spmi-peningkatan')
                <div class="alert alert-danger context-alert mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-upload me-2"></i>Upload ke Tahap Peningkatan SPMI
                            </h5>
                            <p class="mb-0">Dokumen program peningkatan dan pengembangan mutu berkelanjutan</p>
                            <div class="small text-muted mt-1">
                                <strong>Lokasi:</strong> <code>storage/app/public/dokumen/spmi/peningkatan/</code>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- ===================== AKHIR BAGIAN KONTEKS ===================== -->
            
            <form action="{{ route('upload-dokumen.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                
                <!-- Hidden Fields untuk Konteks -->
                @if(isset($context) && $context == 'spmi-penetapan')
                    <input type="hidden" name="penetapan_id" value="{{ $penetapanId ?? '' }}">
                    <input type="hidden" name="tahapan" value="penetapan">
                    @if(isset($tipePenetapan))
                        <input type="hidden" name="metadata[tipe_penetapan]" value="{{ $tipePenetapan }}">
                    @endif
                    @if(isset($tahun))
                        <input type="hidden" name="metadata[tahun]" value="{{ $tahun }}">
                    @endif
                    @if(isset($komponenNama))
                        <input type="hidden" name="metadata[nama_komponen]" value="{{ $komponenNama }}">
                    @endif
                @endif
                
                @if(isset($context) && in_array($context, ['spmi-pelaksanaan', 'spmi-evaluasi', 'spmi-pengendalian', 'spmi-peningkatan']))
                    <input type="hidden" name="tahapan" value="{{ str_replace('spmi-', '', $context) }}">
                @endif

                <!-- DROPDOWN UNIT KERJA -->
                <div class="mb-4">
                    <label for="unit_kerja_id" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                    <select class="form-select" id="unit_kerja_id" name="unit_kerja_id" required>
                        <option value="" selected disabled>Pilih Unit Kerja</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" 
                                @if(isset($context) && $context == 'spmi-penetapan' && $unit->kode == 'LPM') selected @endif>
                                {{ $unit->nama }} ({{ $unit->kode }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        @if(isset($context) && $context == 'spmi-penetapan')
                            <i class="fas fa-info-circle me-1"></i> Direkomendasikan: LPM (Lembaga Penjaminan Mutu)
                        @else
                            Pilih unit kerja Anda
                        @endif
                    </div>
                </div>

                <!-- DROPDOWN IKU -->
                <div class="mb-4">
                    <label for="iku_id" class="form-label">Indikator Kinerja Utama (IKU) <span class="text-danger">*</span></label>
                    <select class="form-select" id="iku_id" name="iku_id" required>
                        <option value="" selected disabled>Pilih IKU</option>
                        @foreach($ikus as $iku)
                            <option value="{{ $iku->id }}"
                                @if(isset($context) && $iku->kode == 'IKU-SPMI') selected @endif>
                                {{ $iku->kode }} - {{ $iku->nama }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">
                        @if(isset($context))
                            <i class="fas fa-info-circle me-1"></i> Direkomendasikan: IKU-SPMI (Indikator Kinerja SPMI)
                        @else
                            Pilih IKU yang relevan dengan dokumen ini
                        @endif
                    </div>
                </div>

                <!-- JENIS DOKUMEN -->
                <div class="mb-4">
                    <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                    <select class="form-select" id="jenis_dokumen" name="jenis_dokumen">
                        <option value="">Pilih Jenis Dokumen</option>
                        @if(isset($context) && $context == 'spmi-penetapan')
                            <option value="Kebijakan">Kebijakan</option>
                            <option value="Pedoman">Pedoman</option>
                            <option value="Standar">Standar</option>
                            <option value="Prosedur">Prosedur</option>
                            <option value="Instruksi Kerja">Instruksi Kerja</option>
                            <option value="Formulir">Formulir</option>
                        @elseif(isset($context) && $context == 'spmi-pelaksanaan')
                            <option value="Rencana Kerja">Rencana Kerja</option>
                            <option value="Laporan Pelaksanaan">Laporan Pelaksanaan</option>
                            <option value="Monitoring">Dokumen Monitoring</option>
                            <option value="Checklist">Checklist</option>
                        @elseif(isset($context) && $context == 'spmi-evaluasi')
                            <option value="Instrumen Audit">Instrumen Audit</option>
                            <option value="Laporan Audit">Laporan Audit</option>
                            <option value="Evaluasi Dosen">Evaluasi Dosen</option>
                            <option value="Survey Kepuasan">Survey Kepuasan</option>
                        @elseif(isset($context))
                            <option value="Laporan">Laporan</option>
                            <option value="Formulir">Formulir</option>
                            <option value="Checklist">Checklist</option>
                            <option value="Template">Template</option>
                        @else
                            <option value="Laporan">Laporan</option>
                            <option value="Dokumen">Dokumen</option>
                            <option value="Formulir">Formulir</option>
                            <option value="Presentasi">Presentasi</option>
                        @endif
                    </select>
                    <div class="form-text">
                        Pilih jenis dokumen (opsional, tapi direkomendasikan)
                    </div>
                </div>

                <!-- RADIO BUTTON JENIS UPLOAD -->
                <div class="mb-4">
                    <label class="form-label">Jenis Upload <span class="text-danger">*</span></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_upload" id="jenis_file" value="file" checked>
                        <label class="form-check-label" for="jenis_file">
                            Upload File
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenis_upload" id="jenis_link" value="link">
                        <label class="form-check-label" for="jenis_link">
                            Sertakan Link
                        </label>
                    </div>
                    <div class="form-text">
                        Pilih apakah akan mengupload file atau menyertakan link dokumen
                    </div>
                </div>

                <!-- INPUT UPLOAD FILE -->
                <div class="mb-4" id="fileUploadSection">
                    <label for="file_dokumen" class="form-label">File Dokumen <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="file_dokumen" name="file_dokumen" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    <div class="form-text">
                        Format file yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG. Maksimal ukuran: 10MB.
                    </div>
                    @error('file_dokumen')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- INPUT LINK DOKUMEN -->
                <div class="mb-4 d-none" id="linkUploadSection">
                    <label for="link_dokumen" class="form-label">Link Dokumen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="link_dokumen" name="link_dokumen" 
                           placeholder="https://example.com/dokumen.pdf">
                    <div class="form-text">
                        Masukkan URL lengkap dokumen (contoh: Google Drive, Dropbox, dll)
                    </div>
                    @error('link_dokumen')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- INPUT NAMA DOKUMEN -->
                <div class="mb-4">
                    <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" 
                           placeholder="Nama dokumen akan terisi otomatis" required>
                    <div class="form-text">
                        Beri nama yang jelas untuk dokumen ini
                        @if(isset($context) && $context == 'spmi-penetapan')
                            <br><small class="text-info">Contoh: "{{ $komponenNama ?? 'Komponen' }} - {{ date('Y-m-d') }}"</small>
                        @endif
                    </div>
                </div>

                <!-- KETERANGAN -->
                <div class="mb-4">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                              placeholder="Tambahkan keterangan tentang dokumen ini (opsional)"></textarea>
                    <div class="form-text">
                        Deskripsi singkat tentang isi atau tujuan dokumen
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

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1" checked>
                        <label class="form-check-label" for="is_public">
                            <strong>Jadikan dokumen publik</strong>
                        </label>
                    </div>
                    <div class="form-text">
                        Dokumen dapat diakses oleh semua pengunjung tanpa login
                        @if(isset($context))
                            <br><small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> 
                            Untuk dokumen SPMI, sebaiknya tetap publik agar dapat diakses stakeholder</small>
                        @endif
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4 mb-3">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        @if(isset($context))
                            <i class="fas fa-upload me-2"></i>Upload ke Repository
                        @else
                            <i class="fas fa-upload me-2"></i>Upload Dokumen
                        @endif
                    </button>
                    @if(isset($context) && $context == 'spmi-penetapan')
                        <a href="{{ route('spmi.penetapan.show', $penetapanId ?? '') }}" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    @else
                        <a href="{{ route('dokumen-saya') }}" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle antara file upload dan link
    document.querySelectorAll('input[name="jenis_upload"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const fileSection = document.getElementById('fileUploadSection');
            const linkSection = document.getElementById('linkUploadSection');
            const fileInput = document.getElementById('file_dokumen');
            const linkInput = document.getElementById('link_dokumen');
            const namaDokumenInput = document.getElementById('nama_dokumen');
            
            if (this.value === 'file') {
                fileSection.classList.remove('d-none');
                linkSection.classList.add('d-none');
                fileInput.required = true;
                linkInput.required = false;
                namaDokumenInput.placeholder = "Nama dokumen akan terisi otomatis";
            } else {
                fileSection.classList.add('d-none');
                linkSection.classList.remove('d-none');
                fileInput.required = false;
                linkInput.required = true;
                namaDokumenInput.placeholder = "Masukkan nama dokumen";
                namaDokumenInput.value = ''; // Clear value ketika switch ke link
            }
        });
    });

    // File info preview dan auto-fill nama dokumen (hanya untuk file upload)
    document.getElementById('file_dokumen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileIcon = document.getElementById('fileIcon');
        const namaDokumenInput = document.getElementById('nama_dokumen');
        const jenisDokumenSelect = document.getElementById('jenis_dokumen');
        
        if (file) {
            // Validasi file type
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.jpg', '.jpeg', '.png'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Format file tidak didukung. Hanya file PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG yang diperbolehkan.');
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
            
            // Jika ada konteks SPMI Penetapan, tambahkan prefix
            @if(isset($context) && $context == 'spmi-penetapan' && isset($komponenNama))
                namaDokumenInput.value = '{{ $komponenNama }} - ' + fileNameWithoutExt;
            @else
                namaDokumenInput.value = fileNameWithoutExt;
            @endif
            
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
            
            // Auto select jenis dokumen berdasarkan ekstensi (jika belum dipilih)
            if (jenisDokumenSelect.value === '') {
                if (extension === 'pdf') {
                    jenisDokumenSelect.value = 'PDF Document';
                } else if (['doc', 'docx'].includes(extension)) {
                    jenisDokumenSelect.value = 'Word Document';
                } else if (['xls', 'xlsx'].includes(extension)) {
                    jenisDokumenSelect.value = 'Excel Document';
                } else if (['ppt', 'pptx'].includes(extension)) {
                    jenisDokumenSelect.value = 'PowerPoint';
                } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
                    jenisDokumenSelect.value = 'Gambar';
                }
            }
            
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
            case 'ppt':
            case 'pptx': return 'fa-file-powerpoint text-warning';
            case 'jpg':
            case 'jpeg':
            case 'png': return 'fa-file-image text-info';
            default: return 'fa-file text-secondary';
        }
    }

    // Validasi sebelum submit form
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const jenisUpload = document.querySelector('input[name="jenis_upload"]:checked').value;
        
        if (jenisUpload === 'file') {
            const fileInput = document.getElementById('file_dokumen');
            const file = fileInput.files[0];
            
            if (!file) {
                e.preventDefault();
                alert('Silakan pilih file untuk diupload.');
                return false;
            }
            
            const allowedExtensions = ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.jpg', '.jpeg', '.png'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedExtensions.includes(fileExtension)) {
                e.preventDefault();
                alert('Format file tidak didukung. Hanya file PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG yang diperbolehkan.');
                return false;
            }
            
            if (file.size > 10 * 1024 * 1024) {
                e.preventDefault();
                alert('Ukuran file terlalu besar. Maksimal 10MB.');
                return false;
            }
        } else {
            const linkInput = document.getElementById('link_dokumen');
            if (!linkInput.value) {
                e.preventDefault();
                alert('Silakan masukkan link dokumen.');
                return false;
            }
            
            // Validasi format link
            const linkPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
            if (!linkPattern.test(linkInput.value)) {
                e.preventDefault();
                alert('Format link tidak valid. Harap masukkan URL yang benar (contoh: https://example.com/dokumen.pdf)');
                return false;
            }
        }

        // Validasi nama dokumen
        const namaDokumenInput = document.getElementById('nama_dokumen');
        if (!namaDokumenInput.value.trim()) {
            e.preventDefault();
            alert('Silakan isi nama dokumen.');
            return false;
        }
        
        // Validasi unit kerja dan IKU
        const unitKerjaSelect = document.getElementById('unit_kerja_id');
        const ikuSelect = document.getElementById('iku_id');
        
        if (!unitKerjaSelect.value) {
            e.preventDefault();
            alert('Silakan pilih Unit Kerja.');
            return false;
        }
        
        if (!ikuSelect.value) {
            e.preventDefault();
            alert('Silakan pilih IKU.');
            return false;
        }
        
        // Tampilkan loading jika semua valid
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
        submitButton.disabled = true;
    });
    
    // Auto-fill nama dokumen berdasarkan konteks jika file tidak diupload
    @if(isset($context) && $context == 'spmi-penetapan' && isset($komponenNama))
        document.addEventListener('DOMContentLoaded', function() {
            const namaDokumenInput = document.getElementById('nama_dokumen');
            const jenisUploadFile = document.getElementById('jenis_file');
            const jenisUploadLink = document.getElementById('jenis_link');
            
            // Jika switch ke link, tetap isi dengan nama komponen
            jenisUploadLink.addEventListener('change', function() {
                if (this.checked) {
                    namaDokumenInput.value = '{{ $komponenNama }} - Link Dokumen';
                }
            });
            
            // Jika belum ada file, isi dengan nama komponen
            if (!document.getElementById('file_dokumen').files.length) {
                namaDokumenInput.value = '{{ $komponenNama }} - {{ date("Y-m-d") }}';
            }
        });
    @endif
</script>
@endpush