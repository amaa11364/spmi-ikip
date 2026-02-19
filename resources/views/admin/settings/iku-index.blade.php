@extends('layouts.main')

@section('title', 'Pengaturan IKU')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold"><i class="fas fa-chart-line me-2"></i>Pengaturan IKU</h4>
                <p class="text-muted mb-0">Kelola Indikator Kinerja Utama (IKU)</p>
            </div>
            <a href="{{ route('admin.settings.iku.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah IKU
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
                        <th>Nama IKU</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ikus as $iku)
                    <tr>
                        <td>
                            <span class="badge bg-primary">{{ $iku->kode }}</span>
                        </td>
                        <td>
                            <strong>{{ $iku->nama }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ Str::limit($iku->deskripsi, 50) }}</small>
                        </td>
                        <td>
                            @if($iku->status)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.settings.iku.edit', $iku->id) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="confirmDelete('{{ route('admin.settings.iku.destroy', $iku->id) }}', '{{ $iku->nama }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada IKU yang ditambahkan</p>
                            <a href="{{ route('admin.settings.iku.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah IKU Pertama
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
    if (confirm(`Apakah Anda yakin ingin menghapus IKU "${name}"?`)) {
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