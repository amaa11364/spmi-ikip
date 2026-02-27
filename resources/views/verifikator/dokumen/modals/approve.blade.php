<!-- Modal Approve -->
<div class="modal fade" id="approveModal{{ $dokumen->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $dokumen->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikator.dokumen.approve', $dokumen->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel{{ $dokumen->id }}">
                        <i class="fas fa-check-circle"></i> Setujui Dokumen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui dokumen <strong>{{ $dokumen->judul ?? $dokumen->nama_dokumen }}</strong>?</p>
                    
                    <div class="mb-3">
                        <label for="comment{{ $dokumen->id }}" class="form-label">Komentar (Opsional)</label>
                        <textarea name="comment" id="comment{{ $dokumen->id }}" class="form-control" rows="3" placeholder="Tambahkan komentar jika diperlukan..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Dokumen yang disetujui akan dipublikasikan dan dapat diakses oleh pengguna lain.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>