@extends('layouts.main')

@section('title', 'Upload Dokumen SPMI')

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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f1f5f9;
        font-family: 'Inter', sans-serif;
    }

    .upload-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    /* Header Card */
    .header-card {
        background: linear-gradient(135deg, var(--primary), #3a0ca3);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 30px -10px rgba(67, 97, 238, 0.3);
        position: relative;
        overflow: hidden;
    }

    .header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-title i {
        font-size: 2.2rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.75rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-left: 4rem;
    }

    /* Main Card */
    .upload-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .upload-card:hover {
        box-shadow: 0 25px 40px -10px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-bottom: 2px solid #e2e8f0;
    }

    .card-header h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header h3 i {
        color: var(--primary);
        background: rgba(67, 97, 238, 0.1);
        padding: 0.5rem;
        border-radius: 12px;
    }

    .card-body {
        padding: 2rem;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .form-label i {
        margin-right: 0.5rem;
        color: var(--primary);
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.85rem 1.2rem;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: white;
        color: var(--dark);
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    /* Tahapan Selector */
    .tahapan-selector {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 2px solid #e2e8f0;
    }

    .tahapan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .tahapan-option {
        position: relative;
    }

    .tahapan-option input[type="radio"] {
        display: none;
    }

    .tahapan-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.25rem 1rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .tahapan-option input[type="radio"]:checked + label {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02));
        box-shadow: 0 10px 20px -8px rgba(67, 97, 238, 0.3);
        transform: translateY(-2px);
    }

    .tahapan-option label i {
        font-size: 1.8rem;
        margin-bottom: 0.75rem;
        color: var(--primary);
        background: rgba(67, 97, 238, 0.1);
        padding: 0.75rem;
        border-radius: 14px;
        transition: all 0.2s ease;
    }

    .tahapan-option input[type="radio"]:checked + label i {
        background: var(--primary);
        color: white;
    }

    .tahapan-option label span {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    /* Dynamic Fields */
    .dynamic-fields {
        background: #f8fafc;
        border-radius: 20px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border: 2px dashed #e2e8f0;
        transition: all 0.3s ease;
    }

    .dynamic-fields-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        color: var(--dark);
        font-weight: 600;
        font-size: 1rem;
    }

    .dynamic-fields-title i {
        color: var(--primary);
    }

    .field-group {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* File Upload */
    .file-upload-area {
        border: 3px dashed #e2e8f0;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .file-upload-area:hover {
        border-color: var(--primary);
        background: rgba(67, 97, 238, 0.02);
    }

    .file-upload-area i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1rem;
        opacity: 0.7;
    }

    .file-upload-area h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .file-upload-area p {
        color: var(--gray);
        font-size: 0.9rem;
    }

    .file-info {
        display: none;
        background: #e8f0fe;
        border-radius: 16px;
        padding: 1rem;
        margin-top: 1rem;
        align-items: center;
        gap: 1rem;
    }

    .file-info.show {
        display: flex;
    }

    .file-info i {
        font-size: 2rem;
        color: var(--primary);
        margin: 0;
    }

    .file-details {
        flex: 1;
    }

    .file-name {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .file-size {
        font-size: 0.8rem;
        color: var(--gray);
    }

    .file-remove {
        color: var(--danger);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .file-remove:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    /* Submit Button */
    .submit-btn {
        width: 100%;
        padding: 1.2rem;
        background: linear-gradient(135deg, var(--primary), #3a0ca3);
        color: white;
        border: none;
        border-radius: 18px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        box-shadow: 0 10px 20px -5px rgba(67, 97, 238, 0.4);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(67, 97, 238, 0.5);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .submit-btn i {
        font-size: 1.2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .upload-container {
            margin: 1rem auto;
            padding: 0 1rem;
        }

        .header-card {
            padding: 1.5rem;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .header-title i {
            font-size: 1.8rem;
            padding: 0.5rem;
        }

        .header-subtitle {
            margin-left: 0;
        }

        .card-header, .card-body {
            padding: 1.5rem;
        }

        .tahapan-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .file-upload-area {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .tahapan-grid {
            grid-template-columns: 1fr;
        }

        .dynamic-fields {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="upload-container">
    <!-- Header Card -->
    <div class="header-card">
        <div class="header-content">
            <div class="header-title">
                <i class="fas fa-cloud-upload-alt"></i>
                <span>Upload Dokumen SPMI</span>
            </div>
            <div class="header-subtitle">
                Unggah dokumen mutu sesuai tahapan SPMI yang dipilih
            </div>
        </div>
    </div>

    <!-- Upload Form Card -->
    <div class="upload-card">
        <div class="card-header">
            <h3>
                <i class="fas fa-file-alt"></i>
                Form Upload Dokumen
            </h3>
        </div>
        <div class="card-body">
            <form id="uploadForm" action="{{ route('user.upload-dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- TAMBAHKAN INI: Hidden field untuk jenis upload -->
                <input type="hidden" name="jenis_upload" value="file">

                <!-- Tahapan Selector -->
                <div class="tahapan-selector">
                    <label class="form-label">
                        <i class="fas fa-layer-group"></i>
                        Pilih Tahapan SPMI
                    </label>
                    <div class="tahapan-grid">
                        <div class="tahapan-option">
                            <input type="radio" name="tahapan" id="tahapan-penetapan" value="penetapan" checked>
                            <label for="tahapan-penetapan">
                                <i class="fas fa-folder-open"></i>
                                <span>Penetapan</span>
                            </label>
                        </div>
                        <div class="tahapan-option">
                            <input type="radio" name="tahapan" id="tahapan-pelaksanaan" value="pelaksanaan">
                            <label for="tahapan-pelaksanaan">
                                <i class="fas fa-play-circle"></i>
                                <span>Pelaksanaan</span>
                            </label>
                        </div>
                        <div class="tahapan-option">
                            <input type="radio" name="tahapan" id="tahapan-evaluasi" value="evaluasi">
                            <label for="tahapan-evaluasi">
                                <i class="fas fa-chart-line"></i>
                                <span>Evaluasi</span>
                            </label>
                        </div>
                        <div class="tahapan-option">
                            <input type="radio" name="tahapan" id="tahapan-pengendalian" value="pengendalian">
                            <label for="tahapan-pengendalian">
                                <i class="fas fa-tasks"></i>
                                <span>Pengendalian</span>
                            </label>
                        </div>
                        <div class="tahapan-option">
                            <input type="radio" name="tahapan" id="tahapan-peningkatan" value="peningkatan">
                            <label for="tahapan-peningkatan">
                                <i class="fas fa-chart-bar"></i>
                                <span>Peningkatan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Field Umum -->
                <div class="form-group">
                    <label class="form-label" for="nama_dokumen">
                        <i class="fas fa-heading"></i>
                        Nama Dokumen <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" 
                           placeholder="Contoh: Kebijakan SPMI 2024" required>
                </div>

                <!-- Unit Kerja & IKU -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="unit_kerja_id">
                                <i class="fas fa-building"></i>
                                Unit Kerja <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="unit_kerja_id" name="unit_kerja_id" required>
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($unitKerjas as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="iku_id">
                                <i class="fas fa-chart-line"></i>
                                IKU Terkait
                            </label>
                            <select class="form-select" id="iku_id" name="iku_id">
                                <option value="">Pilih IKU</option>
                                @foreach($ikus as $iku)
                                    <option value="{{ $iku->id }}">{{ $iku->kode }} - {{ $iku->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields Container -->
                <div class="dynamic-fields" id="dynamicFields">
                    <div class="dynamic-fields-title">
                        <i class="fas fa-cog"></i>
                        <span>Field Tambahan <span id="tahapanLabel">(Penetapan)</span></span>
                    </div>
                    <div id="fieldContainer">
                        {{-- Fields akan diisi oleh JavaScript --}}
                    </div>
                </div>

                <!-- File Upload -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file"></i>
                        Upload File <span class="text-danger">*</span>
                    </label>
                    <div class="file-upload-area" onclick="document.getElementById('file_dokumen').click()">
                        <input type="file" id="file_dokumen" name="file_dokumen" style="display: none;" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>Klik untuk memilih file</h4>
                        <p>atau drag and drop file di sini</p>
                        <small>Maksimal 10MB. Format: PDF, DOC, XLS, PPT, JPG, PNG</small>
                    </div>
                    <div class="file-info" id="fileInfo">
                        <i class="fas fa-file-pdf"></i>
                        <div class="file-details">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <div class="file-remove" onclick="removeFile()">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>

                <!-- Hidden Field untuk Keterangan -->
                <input type="hidden" name="keterangan" id="keterangan" value="Upload dokumen SPMI">

                <!-- Submit Button -->
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-upload"></i>
                    <span>Upload Dokumen</span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Field definitions for each tahapan
    const fieldDefinitions = {
        penetapan: [
            { type: 'select', name: 'kode_penetapan', label: 'Kode Penetapan', icon: 'fa-barcode', required: true,
              options: [
                { value: 'SPMI-PEN-001', label: 'SPMI-PEN-001 - Kebijakan Mutu' },
                { value: 'SPMI-PEN-002', label: 'SPMI-PEN-002 - Manual Mutu' },
                { value: 'SPMI-PEN-003', label: 'SPMI-PEN-003 - Standar Mutu' }
              ] },
            { type: 'number', name: 'tahun_penetapan', label: 'Tahun Penetapan', icon: 'fa-calendar', placeholder: '2024', required: true },
            { type: 'select', name: 'status_penetapan', label: 'Status Penetapan', icon: 'fa-tag', required: true,
              options: [
                { value: 'aktif', label: 'Aktif' },
                { value: 'revisi', label: 'Revisi' },
                { value: 'kadaluarsa', label: 'Kadaluarsa' }
              ] }
        ],
        pelaksanaan: [
            { type: 'text', name: 'keterangan_pelaksanaan', label: 'Keterangan Dokumen', icon: 'fa-info-circle', placeholder: 'Deskripsi singkat tentang dokumen ini', required: true }
        ],
        evaluasi: [
            { type: 'text', name: 'periode_evaluasi', label: 'Periode Evaluasi', icon: 'fa-clock', placeholder: 'Contoh: Semester Ganjil 2024', required: true },
            { type: 'textarea', name: 'hasil_evaluasi', label: 'Hasil Evaluasi', icon: 'fa-chart-bar', placeholder: 'Ringkasan hasil evaluasi...', rows: 3, required: true }
        ],
        pengendalian: [
            { type: 'text', name: 'sumber_temuan', label: 'Sumber Temuan', icon: 'fa-search', placeholder: 'Contoh: Audit Mutu Internal', required: true },
            { type: 'select', name: 'prioritas', label: 'Prioritas', icon: 'fa-exclamation-triangle', required: true,
              options: [
                { value: 'tinggi', label: 'Tinggi' },
                { value: 'sedang', label: 'Sedang' },
                { value: 'rendah', label: 'Rendah' }
              ] },
            { type: 'date', name: 'target_selesai', label: 'Target Selesai', icon: 'fa-calendar-check', required: true }
        ],
        peningkatan: [
            { type: 'text', name: 'program_peningkatan', label: 'Program Peningkatan', icon: 'fa-chart-line', placeholder: 'Nama program peningkatan', required: true },
            { type: 'number', name: 'anggaran', label: 'Anggaran (Rp)', icon: 'fa-money-bill', placeholder: '0', required: true },
            { type: 'select', name: 'jenis_peningkatan', label: 'Jenis Peningkatan', icon: 'fa-tag', required: true,
              options: [
                { value: 'strategis', label: 'Strategis' },
                { value: 'operasional', label: 'Operasional' },
                { value: 'perbaikan', label: 'Perbaikan' }
              ] }
        ]
    };

    // Element references
    const tahapanRadios = document.querySelectorAll('input[name="tahapan"]');
    const fieldContainer = document.getElementById('fieldContainer');
    const tahapanLabel = document.getElementById('tahapanLabel');
    const fileInput = document.getElementById('file_dokumen');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Tahapan labels for display
    const tahapanLabels = {
        penetapan: 'Penetapan',
        pelaksanaan: 'Pelaksanaan',
        evaluasi: 'Evaluasi',
        pengendalian: 'Pengendalian',
        peningkatan: 'Peningkatan'
    };

    // Function to render dynamic fields
    function renderFields(tahapan) {
        const fields = fieldDefinitions[tahapan] || [];
        tahapanLabel.textContent = `(${tahapanLabels[tahapan]})`;
        
        if (fields.length === 0) {
            fieldContainer.innerHTML = '';
            return;
        }

        let html = '<div class="field-group">';
        
        fields.forEach(field => {
            html += `<div class="form-group">`;
            html += `<label class="form-label" for="${field.name}">`;
            html += `<i class="fas ${field.icon}"></i> ${field.label}`;
            if (field.required) html += ` <span class="text-danger">*</span>`;
            html += `</label>`;

            if (field.type === 'select') {
                html += `<select class="form-control" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''}>`;
                html += `<option value="">Pilih ${field.label}</option>`;
                field.options.forEach(option => {
                    html += `<option value="${option.value}">${option.label}</option>`;
                });
                html += `</select>`;
            } else if (field.type === 'textarea') {
                html += `<textarea class="form-control" id="${field.name}" name="${field.name}" 
                          placeholder="${field.placeholder}" rows="${field.rows || 3}" 
                          ${field.required ? 'required' : ''}></textarea>`;
            } else {
                html += `<input type="${field.type}" class="form-control" id="${field.name}" 
                          name="${field.name}" placeholder="${field.placeholder || ''}" 
                          ${field.required ? 'required' : ''}>`;
            }
            
            html += `</div>`;
        });

        html += '</div>';
        fieldContainer.innerHTML = html;
    }

    // Event listeners for tahapan changes
    tahapanRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            renderFields(e.target.value);
        });
    });

    // File input handling
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            
            // Format file size
            let size = file.size;
            if (size < 1024) {
                fileSize.textContent = size + ' bytes';
            } else if (size < 1024 * 1024) {
                fileSize.textContent = (size / 1024).toFixed(2) + ' KB';
            } else {
                fileSize.textContent = (size / (1024 * 1024)).toFixed(2) + ' MB';
            }
            
            fileInfo.classList.add('show');
            
            // Update icon based on file type
            const icon = fileInfo.querySelector('i:first-child');
            const extension = file.name.split('.').pop().toLowerCase();
            
            if (extension === 'pdf') {
                icon.className = 'fas fa-file-pdf';
                icon.style.color = '#ef4444';
            } else if (['doc', 'docx'].includes(extension)) {
                icon.className = 'fas fa-file-word';
                icon.style.color = '#3b82f6';
            } else if (['xls', 'xlsx'].includes(extension)) {
                icon.className = 'fas fa-file-excel';
                icon.style.color = '#10b981';
            } else if (['ppt', 'pptx'].includes(extension)) {
                icon.className = 'fas fa-file-powerpoint';
                icon.style.color = '#f59e0b';
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                icon.className = 'fas fa-file-image';
                icon.style.color = '#8b5cf6';
            } else {
                icon.className = 'fas fa-file-alt';
                icon.style.color = '#64748b';
            }
        }
    });

    // Remove file function
    function removeFile() {
        fileInput.value = '';
        fileInfo.classList.remove('show');
    }

    // Drag and drop handling
    const uploadArea = document.querySelector('.file-upload-area');
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#4361ee';
        uploadArea.style.background = 'rgba(67, 97, 238, 0.02)';
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#e2e8f0';
        uploadArea.style.background = '#f8fafc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Form submission with loading state
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
        submitBtn.disabled = true;
        
        // Form will submit normally, but we show loading state
        // You can add AJAX submission here if needed
    });

    // Initialize with default tahapan (penetapan)
    renderFields('penetapan');
</script>
@endpush
@endsection