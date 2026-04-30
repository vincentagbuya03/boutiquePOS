<!-- Archive Confirmation Modal -->
<div id="archiveConfirmModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 20px; padding: 3rem; text-align: center; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease-out;">
        <!-- Warning Icon -->
        <div style="width: 100%; height: 120px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: center;">
            <div style="font-size: 4rem; color: #802030;">
                <i class="fas fa-archive"></i>
            </div>
        </div>
        
        <h2 id="archiveConfirmTitle" style="font-size: 1.75rem; font-weight: 800; color: #1a1a1a; margin-bottom: 1rem;">Archive Record?</h2>
        <p id="archiveConfirmMessage" style="font-size: 1rem; color: #666; margin-bottom: 2rem; line-height: 1.6;">
            Are you sure you want to archive this product? It will be hidden from active selling and inventory lists.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="closeArchiveModal()" class="btn" style="background: #999; color: white; padding: 0.75rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; cursor: pointer;">
                Cancel
            </button>
            <button onclick="confirmArchive()" class="btn" style="background: #802030; color: white; padding: 0.75rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; cursor: pointer;">
                Archive
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
    let currentArchiveForm = null;

    function showArchiveModal(form, title = 'Archive Record?', message = 'Are you sure you want to archive this record? It will be hidden from active lists.') {
        currentArchiveForm = form;
        document.getElementById('archiveConfirmTitle').textContent = title;
        document.getElementById('archiveConfirmMessage').textContent = message;
        document.getElementById('archiveConfirmModal').style.display = 'flex';
        document.getElementById('archiveConfirmModal').classList.add('active');
    }
    
    function closeArchiveModal() {
        const modal = document.getElementById('archiveConfirmModal');
        modal.style.display = 'none';
        modal.classList.remove('active');
        currentArchiveForm = null;
    }
    
    function confirmArchive() {
        if (currentArchiveForm) {
            currentArchiveForm.submit();
        }
    }

    function showDeleteModal(form) {
        showArchiveModal(
            form,
            'Archive Product?',
            'Are you sure you want to archive this product? It will be hidden from active selling and inventory lists.'
        );
    }
    
    // Close on background click
    document.getElementById('archiveConfirmModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeArchiveModal();
        }
    });
</script>
