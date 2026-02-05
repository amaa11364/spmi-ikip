@if($allDokumen->count() > 0)
    <div class="list-group">
        @foreach($allDokumen as $dokumen)
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="{{ $dokumen->file_icon }} me-2"></i>
                    <span class="small">{{ $dokumen->nama_dokumen }}</span>
                </div>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('dokumen-saya.download', $dokumen->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-4">
        <p class="text-muted">Belum ada dokumen</p>
    </div>
@endif