@extends('layouts.main')

@section('title', 'Tambah Pengendalian SPMI')

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    
    .form-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    
    .progress-slider {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        outline: none;
        margin: 10px 0;
    }
    
    .progress-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #28a745;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .progress-value {
        font-weight: bold;
        color: #28a745;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-block;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
    }
    
    .badge-rencana { background-color: #e9ecef; color: #495057; }
    .badge-berjalan { background-color: #cff4fc; color: #055160; }
    .badge-selesai { background-color: #d1e7dd; color: #0a3622; }
    .badge-terverifikasi { background-color: #d1ecf1; color: #0c5460; }
    .badge-tertunda { background-color: #fff3cd; color: #856404; }
    
    .status-option.active {
        box-shadow: 0 0 0 2px #007bff;
    }
    
    .textarea-counter {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: right;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('spmi.pengendalian.index') }}">
                    <i class="fas fa-tasks me-1"></i> Pengendalian SPMI
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-plus-circle me-1"></i> Tambah Tindakan Pengendalian
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="form-container">
                <!-- Header -->
                <div class="form-header">
                    <h4 class="mb-2">
                        <i class="fas fa-plus-circle me-2 text-primary"></i> Tambah Tindakan Pengendalian Baru
                    </h4>
                    <p class="text-muted mb-0">Isi form berikut untuk menambahkan tindakan pengendalian baru</p>
                </div>

                <!-- Form -->
                <form action="{{ route('spmi.pengendalian.store') }}" method="POST">
                    @csrf
                    
                    <!-- Nama Tindakan -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Nama Tindakan Perbaikan</strong> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" 
                               name="nama_tindakan" required 
                               placeholder="Contoh: Perbaikan Kurikulum Prodi XYZ"
                               value="{{ old('nama_tindakan') }}">
                        <div class="form-text">Berikan nama yang jelas dan deskriptif</div>
                        @error('nama_tindakan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tahun dan Sumber Evaluasi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Tahun</strong> <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" 
                                   name="tahun" required 
                                   value="{{ old('tahun', date('Y')) }}"
                                   min="2000" max="{{ date('Y') + 5 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Sumber Evaluasi</strong>
                            </label>
                            <input type="text" class="form-control" 
                                   name="sumber_evaluasi" 
                                   placeholder="Contoh: Hasil Audit Mutu Internal 2024"
                                   value="{{ old('sumber_evaluasi') }}">
                            <div class="form-text">Dari mana tindakan ini direkomendasikan</div>
                        </div>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Deskripsi Masalah</strong> <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="deskripsi_masalah" 
                                  rows="4" required 
                                  placeholder="Jelaskan masalah yang ditemukan dalam evaluasi"
                                  oninput="updateCounter(this, 'counter-masalah')">{{ old('deskripsi_masalah') }}</textarea>
                        <div class="textarea-counter">
                            <span id="counter-masalah">0</span> karakter
                        </div>
                        @error('deskripsi_masalah')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tindakan Perbaikan -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Tindakan Perbaikan</strong> <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="tindakan_perbaikan" 
                                  rows="4" required 
                                  placeholder="Jelaskan tindakan perbaikan yang akan dilakukan"
                                  oninput="updateCounter(this, 'counter-perbaikan')">{{ old('tindakan_perbaikan') }}</textarea>
                        <div class="textarea-counter">
                            <span id="counter-perbaikan">0</span> karakter
                        </div>
                        @error('tindakan_perbaikan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Penanggung Jawab dan Target Waktu -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Penanggung Jawab</strong> <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" 
                                   name="penanggung_jawab" required 
                                   placeholder="Nama penanggung jawab tindakan"
                                   value="{{ old('penanggung_jawab') }}">
                            @error('penanggung_jawab')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Target Waktu</strong> <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" 
                                   name="target_waktu" required 
                                   value="{{ old('target_waktu', date('Y-m-d', strtotime('+30 days'))) }}">
                            <div class="form-text">Target penyelesaian tindakan</div>
                            @error('target_waktu')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Pelaksanaan -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Status Pelaksanaan</strong> <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap gap-2 mb-3" id="statusOptions">
                            <div class="status-option" data-value="rencana">
                                <span class="status-badge badge-rencana">
                                    <i class="fas fa-clock me-1"></i> Rencana
                                </span>
                            </div>
                            <div class="status-option" data-value="berjalan">
                                <span class="status-badge badge-berjalan">
                                    <i class="fas fa-play-circle me-1"></i> Berjalan
                                </span>
                            </div>
                            <div class="status-option" data-value="selesai">
                                <span class="status-badge badge-selesai">
                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                </span>
                            </div>
                            <div class="status-option" data-value="terverifikasi">
                                <span class="status-badge badge-terverifikasi">
                                    <i class="fas fa-check-double me-1"></i> Terverifikasi
                                </span>
                            </div>
                            <div class="status-option" data-value="tertunda">
                                <span class="status-badge badge-tertunda">
                                    <i class="fas fa-exclamation-circle me-1"></i> Tertunda
                                </span>
                            </div>
                        </div>
                        <input type="hidden" name="status_pelaksanaan" id="statusInput" value="{{ old('status_pelaksanaan', 'rencana') }}">
                        @error('status_pelaksanaan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Progress -->
                    <div class="mb-4">
                        <label class="form-label d-flex justify-content-between">
                            <strong>Progress Pelaksanaan</strong>
                            <span class="progress-value" id="progressValue">{{ old('progress', 0) }}%</span>
                        </label>
                        <input type="range" class="progress-slider" 
                               name="progress" id="progressSlider"
                               min="0" max="100" 
                               value="{{ old('progress', 0) }}">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">0%</small>
                            <small class="text-muted">100%</small>
                        </div>
                        <input type="hidden" name="progress" id="progressInput" value="{{ old('progress', 0) }}">
                        @error('progress')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unit Kerja dan IKU -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Unit Kerja</strong>
                            </label>
                            <select class="form-select" name="unit_kerja_id">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($unitKerjas as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>IKU Terkait</strong>
                            </label>
                            <select class="form-select" name="iku_id">
                                <option value="">Pilih IKU</option>
                                @foreach($ikus as $iku)
                                    <option value="{{ $iku->id }}" {{ old('iku_id') == $iku->id ? 'selected' : '' }}>
                                        {{ $iku->kode }} - {{ $iku->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Catatan Tambahan</strong>
                        </label>
                        <textarea class="form-control" name="catatan" 
                                  rows="3" 
                                  placeholder="Catatan tambahan (opsional)"
                                  oninput="updateCounter(this, 'counter-catatan')">{{ old('catatan') }}</textarea>
                        <div class="textarea-counter">
                            <span id="counter-catatan">0</span> karakter
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <a href="{{ route('spmi.pengendalian.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                        <div class="btn-group">
                            <button type="reset" class="btn btn-outline-danger">
                                <i class="fas fa-redo me-2"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Tindakan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize character counters
        const textareas = ['deskripsi_masalah', 'tindakan_perbaikan', 'catatan'];
        textareas.forEach(field => {
            const element = document.querySelector(`[name="${field}"]`);
            if (element && element.value) {
                const counterId = field === 'deskripsi_masalah' ? 'counter-masalah' : 
                                field === 'tindakan_perbaikan' ? 'counter-perbaikan' : 'counter-catatan';
                document.getElementById(counterId).textContent = element.value.length;
            }
        });

        // Status selection
        const statusOptions = document.querySelectorAll('.status-option');
        const statusInput = document.getElementById('statusInput');
        const initialStatus = statusInput.value;

        statusOptions.forEach(option => {
            if (option.dataset.value === initialStatus) {
                option.classList.add('active');
            }
            
            option.addEventListener('click', function() {
                statusOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                statusInput.value = this.dataset.value;
            });
        });

        // Progress slider
        const progressSlider = document.getElementById('progressSlider');
        const progressValue = document.getElementById('progressValue');
        const progressInput = document.getElementById('progressInput');

        progressSlider.addEventListener('input', function() {
            const value = this.value;
            progressValue.textContent = value + '%';
            progressInput.value = value;
        });

        // Auto-select status based on progress
        progressSlider.addEventListener('change', function() {
            const value = parseInt(this.value);
            if (value === 0 && statusInput.value !== 'tertunda') {
                statusInput.value = 'rencana';
                updateStatusSelection('rencana');
            } else if (value > 0 && value < 100) {
                statusInput.value = 'berjalan';
                updateStatusSelection('berjalan');
            } else if (value === 100) {
                statusInput.value = 'selesai';
                updateStatusSelection('selesai');
            }
        });

        function updateStatusSelection(status) {
            statusOptions.forEach(option => {
                option.classList.remove('active');
                if (option.dataset.value === status) {
                    option.classList.add('active');
                }
            });
        }
    });

    function updateCounter(textarea, counterId) {
        const counter = document.getElementById(counterId);
        counter.textContent = textarea.value.length;
    }
</script>
@endpush