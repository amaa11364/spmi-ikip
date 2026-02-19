@extends('layouts.main')

@section('title', 'Pengaturan Unit Kerja') {{-- DIUBAH --}}

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold"><i class="fas fa-building me-2"></i>Pengaturan Unit Kerja</h4> {{-- DIUBAH --}}
                <p class="text-muted mb-0">Kelola data Unit Kerja</p> {{-- DIUBAH --}}
            </div>
            <a href="{{ route('admin.settings.unit-kerja.create') }}" class="btn btn-primary"> {{-- DIUBAH --}}
                <i class="fas fa-plus me-2"></i>Tambah Unit Kerja {{-- DIUBAH --}}
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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Unit Kerja</th> {{-- DIUBAH --}}
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unitKerjas as $unit) {{-- DIUBAH: $ikus menjadi $unitKerjas, $iku menjadi $unit --}}
                    <tr>
                        <td>
                            <span class="badge bg-primary">{{ $unit->kode }}</span> {{-- DIUBAH: $iku menjadi $unit --}}
                        </td>
                        <td>
                            <strong>{{ $unit->nama }}</strong> {{-- DIUBAH: $iku menjadi $unit --}}
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($unit->deskripsi, 50) }}</small> {{-- DIUBAH --}}
                        </td>
                        <td>
                            @if($unit->status) {{-- DIUBAH: $iku menjadi $unit --}}
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.settings.unit-kerja.edit', $unit->id) }}" class="btn btn-outline-primary"> {{-- DIUBAH --}}
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete('{{ route('admin.settings.unit-kerja.destroy', $unit->id) }}', '{{ $unit->nama }}')"> {{-- DIUBAH --}}
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i> {{-- DIUBAH --}}
                            <p class="text-muted">Belum ada Unit Kerja yang ditambahkan</p> {{-- DIUBAH --}}
                            <a href="{{ route('admin.settings.unit-kerja.create') }}" class="btn btn-primary"> {{-- DIUBAH --}}
                                <i class="fas fa-plus me-2"></i>Tambah Unit Kerja Pertama {{-- DIUBAH --}}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus Unit Kerja "${name}"?`)) { {{-- DIUBAH --}}
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush