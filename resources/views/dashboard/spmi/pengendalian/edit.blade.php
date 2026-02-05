@extends('layouts.main')

@section('title', 'Edit Pengendalian SPMI')

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
    
    .timeline-inputs {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .verification-section {
        background: #e8f5e9;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #28a745;
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
            <li class="breadcrumb-item">
                <a href="{{ route('spmi.pengendalian.show', $pengendalian->id) }}">
                    {{ Str::limit($pengendalian->nama_tindakan, 30) }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <i class="fas fa-edit me-1"></i> Edit
            </li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="form-container">
                <!-- Header -->
                <div class="form-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="mb-2">
                                <i class="fas fa-edit me-2 text-warning"></i> Edit Tindakan Pengendalian
                            </h4>
                            <p class="text-muted mb-0">
                                <strong>{{ $pengendalian->nama_tindakan }}</strong>
                            </p>
                        </div>
                        <div class="badge bg-light text-dark">
                            ID: {{ $pengendalian->id }}
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('spmi.pengendalian.update', $pengendalian->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nama Tindakan -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Nama Tindakan Perbaikan</strong> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg" 
                               name="nama_tindakan" required 
                               value="{{ old('nama_tindakan', $pengendalian->nama_tindakan) }}">
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
                                   value="{{ old('tahun', $pengendalian->tahun) }}"
                                   min="2000" max="{{ date('Y') + 5 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Sumber Evaluasi</strong>
                            </label>
                            <input type="text" class="form-control" 
                                   name="sumber_evaluasi" 
                                   value="{{ old('sumber_evaluasi', $pengendalian->sumber_evaluasi) }}">
                        </div>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Deskripsi Masalah</strong> <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="deskripsi_masalah" 
                                  rows="4" required>{{ old('deskripsi_masalah', $pengendalian->deskripsi_masalah) }}</textarea>
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
                                  rows="4" required>{{ old('tindakan_perbaikan', $pengendalian->tindakan_perbaikan) }}</textarea>
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
                                   value="{{ old('penanggung_jawab', $pengendalian->penanggung_jawab) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Target Waktu</strong> <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" 
                                   name="target_waktu" required 
                                   value="{{ old('target_waktu', $pengendalian->target_waktu ? \Carbon\Carbon::parse($pengendalian->target_waktu)->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <!-- Timeline Actual -->
                    <div class="timeline-inputs">
                        <h6 class="mb-3">
                            <i class="fas fa-calendar-alt me-2"></i> Timeline Aktual
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <strong>Tanggal Mulai Aktual</strong>
                                </label>
                                <input type="date" class="form-control" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $pengendalian->tanggal_mulai ? \Carbon\Carbon::parse($pengendalian->tanggal_mulai)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <strong>Tanggal Selesai Aktual</strong>
                                </label>
                                <input type="date" class="form-control" 
                                       name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $pengendalian->tanggal_selesai ? \Carbon\Carbon::parse($pengendalian->tanggal_selesai)->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Status Pelaksanaan -->
                    <div class="mb-4">
                        <label class="form-label">
                            <strong>Status Pelaksanaan</strong> <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap gap-2 mb-3" id="statusOptions">
                            @php
                                $statuses = [
                                    'rencana' => ['badge-rencana', 'fas fa-clock', 'Rencana'],
                                    'berjalan' => ['badge-berjalan', 'fas fa-play-circle', 'Berjalan'],
                                    'selesai' => ['badge-selesai', 'fas fa-check-circle', 'Selesai'],
                                    'terverifikasi' => ['badge-terverifikasi', 'fas fa-check-double', 'Terverifikasi'],
                                    'tertunda' => ['badge-tertunda', 'fas fa-exclamation-circle', 'Tertunda'],
                                ];
                            @endphp
                            
                            @foreach($statuses as $value => [$class, $icon, $label])
                                <div class="status-option {{ old('status_pelaksanaan', $pengendalian->status_pelaksanaan) == $value ? 'active' : '' }}" 
                                     data-value="{{ $value }}">
                                    <span class="status-badge {{ $class }}">
                                        <i class="{{ $icon }} me-1"></i> {{ $label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="status_pelaksanaan" id="statusInput" 
                               value="{{ old('status_pelaksanaan', $pengendalian->status_pelaksanaan) }}">
                        @error('status_pelaksanaan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Progress -->
                    <div class="mb-4">
                        <label class="form-label d-flex justify-content-between">
                            <strong>Progress Pelaksanaan</strong>
                            <span class="progress-value" id="progressValue">{{ old('progress', $pengendalian->progress) }}%</span>
                        </label>
                        <input type="range" class="progress-slider" 
                               id="progressSlider"
                               min="0" max="100" 
                               value="{{ old('progress', $pengendalian->progress) }}">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">0%</small>
                            <small class="text-muted">100%</small>
                        </div>
                        <input type="hidden" name="progress" id="progressInput" 
                               value="{{ old('progress', $pengendalian->progress) }}">
                        @error('progress')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hasil Verifikasi -->
                    @if(in_array($pengendalian->status_pelaksanaan, ['selesai', 'terverifikasi']))
                    <div class="verification-section mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-check-double me-2 text-success"></i> Hasil Verifikasi
                        </h6>
                        <textarea class="form-control" name="hasil_verifikasi" 
                                  rows="3" 
                                  placeholder="Hasil verifikasi dari tindakan perbaikan">{{ old('hasil_verifikasi', $pengendalian->hasil_verifikasi) }}</textarea>
                    </div>
                    @endif

                    <!-- Unit Kerja dan IKU -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <strong>Unit Kerja</strong>
                            </label>
                            <select class="form-select" name="unit_kerja_id">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($unitKerjas as $unit)
                                    <option value="{{ $unit->id }}" 
                                        {{ old('unit_kerja_id', $pengendalian->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
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
                                    <option value="{{ $iku->id }}" 
                                        {{ old('iku_id', $pengendalian->iku_id) == $iku->id ? 'selected' : '' }}>
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
                                  rows="3">{{ old('catatan', $pengendalian->catatan) }}</textarea>
                    </div>

                    <!-- Metadata -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-plus me-1"></i>
                                        <strong>Dibuat:</strong> {{ $pengendalian->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-check me-1"></i>
                                        <strong>Diperbarui:</strong> {{ $pengendalian->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <div>
                            <a href="{{ route('spmi.pengendalian.show', $pengendalian->id) }}" 
                               class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <a href="{{ route('spmi.pengendalian.index') }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i> Kembali ke Daftar
                            </a>
                        </div>
                        <div class="btn-group">
                            <button type="reset" class="btn btn-outline-danger">
                                <i class="fas fa-redo me-2"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
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
        // Status selection
        const statusOptions = document.querySelectorAll('.status-option');
        const statusInput = document.getElementById('statusInput');
        
        statusOptions.forEach(option => {
            option.addEventListener('click', function() {
                statusOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                statusInput.value = this.dataset.value;
                
                // Auto-adjust progress based on status
                const value = this.dataset.value;
                if (value === 'rencana') {
                    setProgress(0);
                } else if (value === 'berjalan') {
                    setProgress(50);
                } else if (value === 'selesai' || value === 'terverifikasi') {
                    setProgress(100);
                }
            });
        });

        // Progress slider
        const progressSlider = document.getElementById('progressSlider');
        const progressValue = document.getElementById('progressValue');
        const progressInput = document.getElementById('progressInput');
        
        function setProgress(value) {
            progressSlider.value = value;
            progressValue.textContent = value + '%';
            progressInput.value = value;
        }

        progressSlider.addEventListener('input', function() {
            const value = this.value;
            progressValue.textContent = value + '%';
            progressInput.value = value;
            
            // Auto-update status based on progress
            if (value == 0) {
                statusInput.value = 'rencana';
                updateStatusSelection('rencana');
            } else if (value > 0 && value < 100) {
                statusInput.value = 'berjalan';
                updateStatusSelection('berjalan');
            } else if (value == 100) {
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

        // Date validation
        const targetWaktu = document.querySelector('input[name="target_waktu"]');
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]');
        const tanggalSelesai = document.querySelector('input[name="tanggal_selesai"]');

        if (tanggalSelesai && tanggalMulai) {
            tanggalSelesai.addEventListener('change', function() {
                if (tanggalMulai.value && this.value < tanggalMulai.value) {
                    alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai');
                    this.value = '';
                }
            });
        }
    });
</script>
@endpush