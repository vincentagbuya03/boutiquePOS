<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 20px; padding: 3rem; text-align: center; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease-out;">
        <!-- Warning Icon -->
        <div style="width: 100%; height: 120px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: center;">
            <div style="font-size: 4rem; color: #f56565;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #1a1a1a; margin-bottom: 1rem;">Delete Product?</h2>
        <p style="font-size: 1rem; color: #666; margin-bottom: 2rem; line-height: 1.6;">
            Are you sure you want to delete this product? This action cannot be undone.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="closeDeleteModal()" class="btn" style="background: #999; color: white; padding: 0.75rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; cursor: pointer;">
                Cancel
            </button>
            <button onclick="confirmDelete()" class="btn" style="background: #f56565; color: white; padding: 0.75rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; cursor: pointer;">
                Delete
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal {
        display: none !important;
    }
    
    .modal.active {
        display: flex !important;
    }
</style>

<script>
    let currentDeleteForm = null;

    function showDeleteModal(form) {
        currentDeleteForm = form;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
        document.getElementById('deleteConfirmModal').classList.add('active');
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        modal.style.display = 'none';
        modal.classList.remove('active');
        currentDeleteForm = null;
    }
    
    function confirmDelete() {
        if (currentDeleteForm) {
            currentDeleteForm.submit();
        }
    }
    
    // Close on background click
    document.getElementById('deleteConfirmModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>
