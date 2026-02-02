<!-- Load jQuery sebelum script lainnya -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script>
    // Fungsi untuk upload file via AJAX
    function uploadInlineFileEvaluasi(event, id) {
        event.preventDefault();
        
        const form = document.getElementById('uploadForm' + id);
        const formData = new FormData(form);
        const url = form.action;
        
        // Tampilkan loading
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
        submitBtn.disabled = true;
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Dokumen berhasil diupload!');
                toggleUploadModal(id); // Tutup modal
                location.reload(); // Refresh halaman untuk update count
            } else {
                alert('Gagal: ' + data.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengupload dokumen. Silakan coba lagi.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Toggle upload modal
    function toggleUploadModalEvaluasi(id) {
        const modal = document.getElementById('uploadModal' + id);
        const allModals = document.querySelectorAll('.upload-inline-modal');
        
        // Hide all other modals
        allModals.forEach(m => {
            if (m.id !== 'uploadModal' + id) {
                m.classList.remove('show');
            }
        });
        
        // Toggle current modal
        if (modal.classList.contains('show')) {
            modal.classList.remove('show');
        } else {
            modal.classList.add('show');
        }
        
        // Close modal when clicking outside
        if (modal.classList.contains('show')) {
            setTimeout(() => {
                const handleClickOutside = (event) => {
                    if (!modal.contains(event.target) && !event.target.closest('.btn-upload')) {
                        modal.classList.remove('show');
                        document.removeEventListener('click', handleClickOutside);
                    }
                };
                document.addEventListener('click', handleClickOutside);
            }, 100);
        }
    }

    // View Penetapan Detail
    function viewPenetapan(id) {
        // Cek jika jQuery tersedia
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.penetapan.ajax.detail", ":id") }}'.replace(':id', id);
        
        jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    jQuery('#viewModalBody').html(response.html);
                    jQuery('#viewModal').modal('show');
                    
                    // Re-initialize tooltips di modal
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('#viewModal [title]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Gagal memuat data. Silakan coba lagi.');
            }
        });
    }
    
    // Edit Penetapan
    function editPenetapan(id) {
        // Cek jika jQuery tersedia
        if (typeof jQuery === 'undefined') {
            console.error('jQuery tidak tersedia untuk AJAX');
            alert('Fitur ini memerlukan jQuery. Silakan refresh halaman.');
            return;
        }
        
        const url = '{{ route("spmi.penetapan.ajax.edit-form", ":id") }}'.replace(':id', id);
        
        jQuery.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    jQuery('#editModalBody').html(response.html);
                    jQuery('#editForm').attr('action', '{{ route("spmi.penetapan.update", ":id") }}'.replace(':id', id));
                    jQuery('#editModal').modal('show');
                    
                    // Re-initialize tooltips di modal
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('#editModal [title]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Gagal memuat form edit. Silakan coba lagi.');
            }
        });
    }
    
    // Confirm Delete
    function confirmDelete(button) {
        if (confirm('Apakah Anda yakin ingin menghapus penetapan ini?')) {
            button.closest('.delete-form').submit();
        }
    }

    // Initialize page
    (function() {
        function initPage() {
            // Cek jika jQuery sudah dimuat
            if (typeof jQuery === 'undefined') {
                console.error('jQuery belum dimuat!');
                setTimeout(initPage, 100);
                return;
            }
            
            console.log('jQuery loaded, version:', jQuery.fn.jquery);
            
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Handle Edit Form Submission dengan jQuery
            jQuery('#editForm').submit(function(e) {
                e.preventDefault();
                
                const form = jQuery(this);
                const url = form.attr('action');
                const formData = form.serialize();
                
                jQuery.ajax({
                    url: url,
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            jQuery('#editModal').modal('hide');
                            alert('Data berhasil diperbarui!');
                            location.reload();
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Gagal memperbarui data. Silakan coba lagi.');
                    }
                });
            });
            
            // Handle Upload Inline Form Submission dengan jQuery
            jQuery('body').on('submit', '.upload-inline-form', function(e) {
                e.preventDefault();
                
                const form = jQuery(this);
                const url = form.attr('action');
                const formData = new FormData(this);
                
                jQuery.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert('Dokumen berhasil diupload!');
                            location.reload();
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Gagal mengupload dokumen. Silakan coba lagi.');
                    }
                });
            });
        }
        
        // Tunggu DOM siap
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPage);
        } else {
            initPage();
        }
    })();
</script>