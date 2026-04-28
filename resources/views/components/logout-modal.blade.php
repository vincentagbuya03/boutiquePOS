<!-- Logout Confirmation Modal -->
<div id="logoutConfirmModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 20px; padding: 3rem; text-align: center; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease-out;">
        <!-- Warning Lottie Animation -->
        <div style="width: 100%; height: 150px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: center;">
            <dotlottie-player src="{{ asset('lottie/Warning animation.lottie') }}" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></dotlottie-player>
        </div>
        
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #1a1a1a; margin-bottom: 1rem;">Confirm Logout?</h2>
        <p style="font-size: 1rem; color: #666; margin-bottom: 2rem; line-height: 1.6;">
            Are you sure you want to logout? You'll need to sign in again to access your dashboard.
        </p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="closeLogoutConfirm()" class="btn" style="background: #999; color: white; padding: 0.75rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; cursor: pointer;">
                Cancel
            </button>
            <button onclick="confirmLogout()" class="btn" style="background: #802030; color: white; padding: 0.75rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; cursor: pointer;">
                Logout
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
    function showLogoutConfirm() {
        document.getElementById('logoutConfirmModal').style.display = 'flex';
        document.getElementById('logoutConfirmModal').classList.add('active');
    }
    
    function closeLogoutConfirm() {
        const modal = document.getElementById('logoutConfirmModal');
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
    
    function confirmLogout() {
        document.getElementById('logoutForm').submit();
    }
    
    // Close on background click
    document.getElementById('logoutConfirmModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLogoutConfirm();
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLogoutConfirm();
        }
    });
</script>
