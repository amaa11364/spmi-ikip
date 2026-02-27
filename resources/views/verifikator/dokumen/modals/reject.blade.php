<!-- Modal Reject -->
<div class="modal fade" id="rejectModal{{ $dokumen->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $dokumen->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('verifikator.dokumen.reject', $dokumen->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel{{ $dokumen->id }}">
                        <i class="fas fa-times-circle"></i> Tolak Dokumen
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak dokumen <strong>{{ $dokumen->judul ?? $dokumen->nama_dokumen }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label for="reason{{ $dokumen->id }}" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason{{ $dokumen->id }}" class="form-control" rows="4" required 
                                  placeholder="Jelaskan alasan mengapa dokumen ini ditolak..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment{{ $dokumen->id }}" class="form-label">Komentar Tambahan (Opsional)</label>
                        <textarea name="comment" id="comment{{ $dokumen->id }}" class="form-control" rows="2" 
                                  placeholder="Tambahkan komentar..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Dokumen yang ditolak tidak akan dipublikasikan dan pengupload perlu mengunggah ulang.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban"></i> Tolak Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>