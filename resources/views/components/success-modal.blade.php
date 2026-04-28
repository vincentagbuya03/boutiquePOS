<!-- Success Modal -->
<div id="successModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="modal-content" style="background: white; border-radius: 20px; padding: 3rem; text-align: center; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: slideUp 0.3s ease-out;">
        <!-- Lottie Animation -->
        <div id="successAnimation" style="width: 100%; height: 200px; margin-bottom: 2rem;">
            <dotlottie-player src="{{ asset('lottie/success.lottie') }}" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></dotlottie-player>
        </div>
        
        <h2 id="successTitle" style="font-size: 1.75rem; font-weight: 800; color: #1a1a1a; margin-bottom: 1rem;">Success!</h2>
        <p id="successMessage" style="font-size: 1rem; color: #666; margin-bottom: 2rem; line-height: 1.6;"></p>
        
        <button onclick="closeSuccessModal()" class="btn" style="background: var(--color-editorial, #802030); color: white; padding: 1rem 2rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.9rem; text-transform: uppercase; cursor: pointer; transition: transform 0.2s;">
            Got it
        </button>
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

<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

<script>
    function showSuccessModal(title = 'Success!', message = 'Operation completed successfully.') {
        document.getElementById('successTitle').textContent = title;
        document.getElementById('successMessage').textContent = message;
        document.getElementById('successModal').style.display = 'flex';
        document.getElementById('successModal').classList.add('active');
    }
    
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
    
    // Close on background click
    document.getElementById('successModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeSuccessModal();
        }
    });
    
    // Check for flash messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.querySelector('[data-flash-message]');
        if (successMessage) {
            const title = successMessage.getAttribute('data-title') || 'Success!';
            const message = successMessage.getAttribute('data-flash-message');
            showSuccessModal(title, message);
        }
    });
</script>
