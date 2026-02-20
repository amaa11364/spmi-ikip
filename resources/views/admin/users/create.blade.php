{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.main')

@section('title', 'Tambah Pengguna Baru')

@section('content')

@push('styles')
<style>
    /* Perbaiki tampilan dropdown */
    .form-select {
        background-color: #fff;
        border: 1px solid #ced4da;
        color: #212529;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        border-radius: 0.375rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    .form-select:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }

    /* Style untuk option di dropdown */
    .form-select option {
        background-color: #fff;
        color: #212529;
        padding: 8px;
    }

    /* Style untuk dropdown saat hover */
    .form-select option:hover,
    .form-select option:focus,
    .form-select option:active,
    .form-select option:checked {
        background-color: #0d6efd;
        color: white;
    }

    /* Untuk browser Firefox */
    .form-select option:checked {
        background: #0d6efd linear-gradient(0deg, #0d6efd 0%, #0d6efd 100%);
        color: white;
    }

    /* Style untuk dropdown container di browser tertentu */
    select.form-select {
        background-color: white !important;
        color: #212529 !important;
    }

    /* Pastikan teks di option terlihat */
    select.form-select option {
        color: #212529;
        background-color: white;
    }

    /* Style untuk placeholder option */
    select.form-select option[value=""] {
        color: #6c757d;
        font-style: italic;
    }
</style>
@endpush
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-plus me-2"></i>Tambah Pengguna Baru
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" 
                                name="role" 
                                required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="verifikator" {{ old('role') == 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Pengguna Biasa</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unit_kerja_id" class="form-label">Unit Kerja</label>
                        <select class="form-select @error('unit_kerja_id') is-invalid @enderror" 
                                id="unit_kerja_id" 
                                name="unit_kerja_id">
                            <option value="">Pilih Unit Kerja</option>
                            @foreach($unitKerja as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_kerja_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="program_studi_id" class="form-label">Program Studi</label>
                        <select class="form-select @error('program_studi_id') is-invalid @enderror" 
                                id="program_studi_id" 
                                name="program_studi_id">
                            <option value="">Pilih Program Studi</option>
                            @foreach($programStudi as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="avatar" class="form-label">Foto Profil</label>
                        <input type="file" 
                               class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG, GIF (max: 2MB)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktifkan akun segera
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3">Permissions (Opsional)</h6>
                <div class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="manage_users" 
                                   id="perm_manage_users"
                                   {{ in_array('manage_users', old('permissions', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_manage_users">
                                <i class="fas fa-users-cog me-1"></i>Mengelola Pengguna
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="manage_documents" 
                                   id="perm_manage_documents"
                                   {{ in_array('manage_documents', old('permissions', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_manage_documents">
                                <i class="fas fa-file-alt me-1"></i>Mengelola Dokumen
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="verify_documents" 
                                   id="perm_verify_documents"
                                   {{ in_array('verify_documents', old('permissions', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_verify_documents">
                                <i class="fas fa-check-circle me-1"></i>Verifikasi Dokumen
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="manage_settings" 
                                   id="perm_manage_settings"
                                   {{ in_array('manage_settings', old('permissions', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_manage_settings">
                                <i class="fas fa-cog me-1"></i>Mengelola Pengaturan
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Pengguna
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview avatar sebelum upload
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Bisa ditambahkan preview jika diperlukan
                console.log('File siap diupload');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush