document.addEventListener('DOMContentLoaded', () => {
    // --- MODAL AND UPLOAD LOGIC ---

    // Toggles the visibility of the inline upload form for a given ID
    window.toggleUploadModal = (id) => {
        const modal = document.getElementById(`uploadModalEvaluasi${id}`);
        if (!modal) return;

        // Hide all other open inline modals
        document.querySelectorAll('.upload-inline-modal.show').forEach(openModal => {
            if (openModal.id !== `uploadModalEvaluasi${id}`) {
                openModal.classList.remove('show');
            }
        });

        // Toggle the current modal
        modal.classList.toggle('show');

        // Add a listener to close the modal if clicking outside
        if (modal.classList.contains('show')) {
            setTimeout(() => {
                document.addEventListener('click', function hideOnOutsideClick(event) {
                    const uploadButton = document.querySelector(`button[onclick*="toggleUploadModal(${id})"]`);
                    if (!modal.contains(event.target) && !uploadButton.contains(event.target)) {
                        modal.classList.remove('show');
                        document.removeEventListener('click', hideOnOutsideClick);
                    }
                }, { once: true });
            }, 100);
        }
    };

    // Handles the inline file upload via Fetch API
    window.uploadInlineFile = (id, button) => {
        const form = document.getElementById(`uploadFormEvaluasi${id}`);
        if (!form) return;

        const submitBtn = button || form.querySelector('button[type="button"][onclick*="uploadInlineFile"]');
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
        submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Dokumen berhasil diupload!');
                window.location.reload();
            } else {
                alert(`Upload Gagal: ${data.message || 'Error tidak diketahui.'}`);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Terjadi kesalahan saat mengupload file. Silakan coba lagi.');
        })
        .finally(() => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    };

    // --- AJAX MODALS FOR VIEW AND EDIT ---

    // Fetches and displays the detail view in a modal
    window.viewEvaluasi = (id) => {
        const url = window.routes.evaluasiDetail.replace(':id', id);
        const viewModalEl = document.getElementById('viewModal');
        const viewModalBody = document.getElementById('viewModalBody');
        if (!viewModalEl || !viewModalBody) return;

        const modal = new bootstrap.Modal(viewModalEl);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    viewModalBody.innerHTML = data.html;
                    modal.show();
                } else {
                    alert(data.message || 'Gagal memuat data.');
                }
            })
            .catch(error => {
                console.error('View error:', error);
                alert('Gagal memuat detail. Silakan coba lagi.');
            });
    };

    // Fetches and displays the edit form in a modal
    window.editEvaluasi = (id) => {
        const url = window.routes.evaluasiEdit.replace(':id', id);
        const editModalEl = document.getElementById('editModal');
        const editModalBody = document.getElementById('editModalBody');
        const editForm = document.getElementById('editForm');
        if (!editModalEl || !editModalBody || !editForm) return;
        
        const modal = new bootstrap.Modal(editModalEl);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    editModalBody.innerHTML = data.html;
                    editForm.action = window.routes.evaluasiUpdate.replace(':id', id);
                    modal.show();
                } else {
                    alert(data.message || 'Gagal memuat form.');
                }
            })
            .catch(error => {
                console.error('Edit error:', error);
                alert('Gagal memuat form edit. Silakan coba lagi.');
            });
    };
    
    // --- FORM SUBMISSIONS ---

    // Handles the submission for the main edit form
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(editForm);
            
            fetch(editForm.action, {
                method: 'POST', // Using POST with _method field
                body: formData,
                 headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Data berhasil diperbarui!');
                    window.location.reload();
                } else {
                    alert(`Update Gagal: ${data.message || 'Error tidak diketahui.'}`);
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('Terjadi kesalahan saat memperbarui data.');
            });
        });
    }

    // --- GENERAL ---

    // Confirms deletion before submitting the form
    window.confirmDelete = (button) => {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            const form = button.closest('form');
            if(form) {
                form.submit();
            }
        }
    };

    // Initialize Bootstrap tooltips on the page
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
