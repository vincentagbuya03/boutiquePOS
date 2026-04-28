<!-- Login Modal -->
<div id="loginModal" class="login-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="login-modal-content" style="background: white; border-radius: 20px; padding: 3rem; max-width: 500px; box-shadow: 0 25px 50px rgba(0,0,0,0.2); animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2rem; font-weight: 800; color: #802030; margin: 0 0 0.5rem 0;">V'S Fashion</h2>
            <p style="font-size: 0.85rem; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 0.2em;">Boutique Management</p>
        </div>
        
        <form id="loginForm" action="{{ route('login') }}" method="POST" style="display: flex; flex-direction: column; gap: 1.5rem;">
            @csrf
            
            <div>
                <label for="loginEmail" style="font-size: 0.75rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" id="loginEmail" name="email" placeholder="Enter your email..." required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #eee; border-radius: 10px; font-size: 0.95rem; transition: border-color 0.3s; outline: none;" onFocus="this.style.borderColor='#802030'" onBlur="this.style.borderColor='#eee'">
                @error('email')
                    <span style="color: #f56565; font-size: 0.75rem; font-weight: 700; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label for="loginPassword" style="font-size: 0.75rem; font-weight: 800; color: #adb5bd; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 0.5rem;">Password</label>
                <input type="password" id="loginPassword" name="password" placeholder="Enter your password..." required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #eee; border-radius: 10px; font-size: 0.95rem; transition: border-color 0.3s; outline: none;" onFocus="this.style.borderColor='#802030'" onBlur="this.style.borderColor='#eee'">
                @error('password')
                    <span style="color: #f56565; font-size: 0.75rem; font-weight: 700; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" style="background: #802030; color: white; padding: 1rem; border: none; border-radius: 100px; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.3s; margin-top: 1rem;">
                Sign In
            </button>
        </form>
        
        <button onclick="closeLoginModal()" style="position: absolute; top: 1.5rem; right: 1.5rem; background: #f1f1f1; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; color: #999; transition: all 0.3s;" onMouseOver="this.style.background='#802030'; this.style.color='white'" onMouseOut="this.style.background='#f1f1f1'; this.style.color='#999'">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<style>
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-30px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .login-modal {
        display: none !important;
    }
    
    .login-modal.active {
        display: flex !important;
    }
</style>

<script>
    function showLoginModal() {
        document.getElementById('loginModal').style.display = 'flex';
        document.getElementById('loginModal').classList.add('active');
    }
    
    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        modal.style.display = 'none';
        modal.classList.remove('active');
    }
    
    // Close on background click
    document.getElementById('loginModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeLoginModal();
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLoginModal();
        }
    });
</script>
