@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('content')

<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i>Edit Pengguna: {{ $user->name }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
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
                               value="{{ old('email', $user->email) }}" 
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
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Kosongkan jika tidak diubah">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" 
                                name="role" 
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="verifikator" {{ old('role', $user->role) == 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Pengguna Biasa</option>
                        </select>
                        @if($user->id === auth()->id())
                            <small class="text-muted">Anda tidak dapat mengubah role Anda sendiri</small>
                        @endif
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
                                <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $user->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
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
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id', $user->program_studi_id) == $prodi->id ? 'selected' : '' }}>
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
                        
                        @if($user->avatar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 style="max-height: 100px; width: auto; object-fit: cover;"
                                 class="border rounded">
                            <p class="text-muted small mt-1">Foto saat ini</p>
                        </div>
                        @endif
                        
                        <input type="file" 
                               class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" 
                               name="avatar" 
                               accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG, GIF (max: 2MB). Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Akun Aktif
                            </label>
                            @if($user->id === auth()->id())
                                <br><small class="text-muted">Anda tidak dapat mengubah status akun Anda sendiri</small>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3">Permissions</h6>
                <div class="row mb-4">
                    @php $userPermissions = $user->permissions ?? []; @endphp
                    <div class="col-md-3 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="manage_users" 
                                   id="perm_manage_users"
                                   {{ in_array('manage_users', old('permissions', $userPermissions)) ? 'checked' : '' }}>
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
                                   {{ in_array('manage_documents', old('permissions', $userPermissions)) ? 'checked' : '' }}>
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
                                   {{ in_array('verify_documents', old('permissions', $userPermissions)) ? 'checked' : '' }}>
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
                                   {{ in_array('manage_settings', old('permissions', $userPermissions)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_manage_settings">
                                <i class="fas fa-cog me-1"></i>Mengelola Pengaturan
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Pengguna
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
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