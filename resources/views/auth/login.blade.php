@extends('layouts.app')

@section('title', 'Login')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    :root {
        --color-editorial: #802030;
        --color-editorial-light: #f8f8f8;
        --color-text-muted: #888;
    }

    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: #fff;
        font-family: 'Inter', sans-serif;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .main-wrapper {
        display: flex;
        min-height: 100vh;
        width: 100%;
        background: url('https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&q=80&w=2000') no-repeat center center;
        background-size: cover;
        position: relative;
    }

    /* Gradient overlay to achieve the bright, high-key look */
    .main-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgba(230, 230, 230, 0.8), rgba(255, 255, 255, 0.5));
        backdrop-filter: blur(2px);
    }

    .content-grid {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 4rem;
        align-items: center;
    }

    .left-section {
        padding-right: 4rem;
    }

    .editorial-heading {
        font-family: 'Bodoni Moda', serif;
        font-size: 6rem;
        line-height: 0.9;
        font-weight: 800;
        color: var(--color-editorial);
        margin: 0;
        text-transform: capitalize;
    }

    .editorial-title {
        display: block;
        font-size: 6rem;
    }

    .editorial-subtitle {
        margin-top: 2rem;
        font-size: 1.15rem;
        color: #444;
        max-width: 400px;
        line-height: 1.6;
    }

    .est-badge {
        margin-top: 3rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #333;
        letter-spacing: 0.2em;
    }

    .est-badge::before {
        content: '';
        width: 40px;
        height: 1px;
        background: #ccc;
    }

    .right-section {
        display: flex;
        justify-content: flex-end;
    }

    .login-card {
        background: white;
        width: 100%;
        max-width: 480px;
        padding: 4rem;
        border-radius: 12px;
        box-shadow: 0 40px 100px rgba(0,0,0,0.08);
        animation: slideInRight 1s cubic-bezier(0.23, 1, 0.32, 1);
    }

    @keyframes slideInRight {
        from { transform: translateX(50px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    .card-header {
        margin-bottom: 3.5rem;
    }

    .card-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .card-subtitle {
        font-size: 0.95rem;
        color: var(--color-text-muted);
        margin-top: 0.5rem;
    }

    .form-group {
        margin-bottom: 2.5rem;
        position: relative;
    }

    .form-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #666;
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 0;
        color: #999;
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 0 0.75rem 2rem;
        border: none;
        border-bottom: 1px solid #e0e0e0;
        font-size: 1rem;
        background: transparent;
        transition: border-color 0.3s;
        color: #333;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-bottom-color: var(--color-editorial);
    }

    .forgot-link {
        color: var(--color-editorial);
        text-decoration: none;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
    }

    .login-button {
        width: 100%;
        padding: 1.25rem;
        background: var(--color-editorial);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1rem;
        box-shadow: 0 10px 20px rgba(128, 32, 48, 0.2);
    }

    .login-button:hover {
        background: #601824;
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(128, 32, 48, 0.3);
    }

    .card-footer {
        margin-top: 3rem;
        text-align: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .create-account-link {
        display: block;
        margin-top: 1rem;
        color: #333;
        text-decoration: none;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    .powered-by {
        position: absolute;
        bottom: 4rem;
        left: 4rem;
        font-size: 0.7rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        z-index: 2;
    }

    .powered-by b {
        color: #666;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 3rem 2rem;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 40px 100px rgba(0,0,0,0.15);
        animation: modalSlideIn 0.3s cubic-bezier(0.23, 1, 0.32, 1);
    }

    @keyframes modalSlideIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-lottie {
        width: 150px;
        height: 150px;
        margin: 0 auto 2rem;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1rem;
    }

    .modal-message {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .modal-close {
        background: var(--color-editorial);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: all 0.3s;
    }

    .modal-close:hover {
        background: #601824;
        transform: translateY(-2px);
    }

    .forgot-password-link {
        color: var(--color-editorial);
        text-decoration: none;
        cursor: pointer;
        font-weight: 600;
        transition: color 0.3s;
    }

    .forgot-password-link:hover {
        color: #601824;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
            padding: 2rem;
        }
        .left-section {
            padding: 0;
            text-align: center;
            margin-bottom: 4rem;
        }
        .editorial-heading {
            font-size: 4rem;
        }
        .editorial-subtitle {
            margin: 2rem auto;
        }
        .est-badge {
            justify-content: center;
        }
        .right-section {
            justify-content: center;
        }
        .powered-by {
            bottom: 2rem;
            left: 0;
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 640px) {
        body {
            min-height: 100dvh;
        }

        .main-wrapper {
            min-height: 100dvh;
            align-items: flex-start;
        }

        .content-grid {
            padding: 1rem;
            gap: 1.5rem;
            align-items: start;
        }

        .left-section {
            margin-bottom: 1rem;
        }

        .editorial-heading,
        .editorial-title {
            font-size: 2.8rem;
        }

        .editorial-subtitle {
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .est-badge {
            margin-top: 1.5rem;
        }

        .login-card {
            max-width: none;
            padding: 1.5rem 1.25rem;
            border-radius: 16px;
        }

        .card-header {
            margin-bottom: 2rem;
        }

        .card-title {
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .login-button {
            padding: 1rem;
        }

        .powered-by {
            position: static;
            padding: 0 1rem 1.5rem;
        }

        .modal-content {
            width: calc(100% - 2rem);
            padding: 2rem 1.25rem;
        }

        .modal-lottie {
            width: 120px;
            height: 120px;
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="main-wrapper">
    <div class="content-grid">
        <div class="left-section">
            <h1 class="editorial-heading">
                V’S<br>
                Fashion<br>
                Boutique
            </h1>
            <p class="editorial-subtitle">
                A curated interface for the modern curator. Manage your boutique with the precision of a lookbook.
            </p>
            <div class="est-badge">
                EST. 2024
            </div>
        </div>

        <div class="right-section">
            <div class="login-card">
                <div class="card-header">
                    <h2 class="card-title">V’S Fashion</h2>
                    <p class="card-subtitle">Access your store dashboard</p>
                </div>

                @if($errors->any())
                    <div style="background: #fff5f5; color: #c53030; padding: 1rem; border-radius: 6px; margin-bottom: 2rem; font-size: 0.85rem;">
                        @foreach($errors->all() as $error)
                            <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">
                            EMAIL ADDRESS
                        </label>
                        <div class="input-wrapper">
                            <i class="far fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="form-input" 
                                   placeholder="manager@vsfashion.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">PASSWORD</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="login-button">
                        LOGIN
                    </button>
                </form>

                <div class="card-footer">
                    <a href="#" class="forgot-password-link" id="forgotPasswordBtn">Forgot Password?</a><br>

            </div>
        </div>
    </div>

    <div class="powered-by">
        POWERED BY<br>
        <b>V’S Fashion Boutique</b>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal-overlay" id="forgotPasswordModal">
        <div class="modal-content">
            <div class="modal-lottie" id="lottieContainer"></div>
            <h3 class="modal-title">Password Reset</h3>
            <p class="modal-message">
                Please contact your administrator to reset your password or recover your account access.
            </p>
            <button class="modal-close" id="closeModalBtn">CLOSE</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
<script>
    const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const lottieContainer = document.getElementById('lottieContainer');

    let lottieAnimation;

    // Lottie animation data (lock animation)
    const lottieData = {
        "v": "5.5.2",
        "fr": 29.9700012207031,
        "ip": 0,
        "op": 60.0000024438531,
        "w": 200,
        "h": 200,
        "nm": "Lock",
        "ddd": 0,
        "assets": [],
        "layers": [
            {
                "ddd": 0,
                "ind": 1,
                "ty": 4,
                "nm": "Shape Layer 1",
                "sr": 1,
                "ks": {
                    "o": { "a": 0, "k": 100 },
                    "r": { "a": 0, "k": 0 },
                    "p": { "a": 0, "k": [100, 100, 0] },
                    "a": { "a": 0, "k": [0, 0, 0] },
                    "s": { "a": 0, "k": [100, 100, 100] }
                },
                "ao": 0,
                "shapes": [
                    {
                        "ty": "gr",
                        "it": [
                            {
                                "d": 1,
                                "ty": "el",
                                "s": { "a": 0, "k": [60, 60] },
                                "p": { "a": 0, "k": [0, -30] },
                                "nm": "Ellipse Path 1",
                                "mn": "ADBE Vector Shape - Ellipse"
                            },
                            {
                                "ty": "st",
                                "c": { "a": 0, "k": [0.8, 0.13, 0.2, 1] },
                                "o": { "a": 0, "k": 100 },
                                "w": { "a": 0, "k": 4 },
                                "lc": 2,
                                "lj": 2,
                                "ml": 10,
                                "nm": "Stroke 1",
                                "mn": "ADBE Vector Graphic - Stroke"
                            },
                            {
                                "ty": "fl",
                                "c": { "a": 0, "k": [1, 1, 1, 1] },
                                "o": { "a": 0, "k": 100 },
                                "nm": "Fill 1",
                                "mn": "ADBE Vector Graphic - Fill"
                            }
                        ],
                        "nm": "Ellipse Group 1",
                        "np": 3,
                        "cix": 2,
                        "bm": 0,
                        "ix": 1,
                        "mn": "ADBE Vector Group"
                    },
                    {
                        "ty": "gr",
                        "it": [
                            {
                                "ty": "rc",
                                "d": 1,
                                "s": { "a": 0, "k": [80, 60] },
                                "p": { "a": 0, "k": [0, 15] },
                                "r": { "a": 0, "k": 4 },
                                "nm": "Rectangle Path 1",
                                "mn": "ADBE Vector Shape - Rect"
                            },
                            {
                                "ty": "st",
                                "c": { "a": 0, "k": [0.8, 0.13, 0.2, 1] },
                                "o": { "a": 0, "k": 100 },
                                "w": { "a": 0, "k": 4 },
                                "lc": 2,
                                "lj": 2,
                                "ml": 10,
                                "nm": "Stroke 1",
                                "mn": "ADBE Vector Graphic - Stroke"
                            },
                            {
                                "ty": "fl",
                                "c": { "a": 0, "k": [1, 1, 1, 1] },
                                "o": { "a": 0, "k": 100 },
                                "nm": "Fill 1",
                                "mn": "ADBE Vector Graphic - Fill"
                            }
                        ],
                        "nm": "Rectangle Group 1",
                        "np": 3,
                        "cix": 2,
                        "bm": 0,
                        "ix": 2,
                        "mn": "ADBE Vector Group"
                    }
                ],
                "ip": 0,
                "op": 60.0000024438531,
                "st": 0,
                "bm": 0
            }
        ],
        "markers": []
    };

    forgotPasswordBtn.addEventListener('click', function(e) {
        e.preventDefault();
        forgotPasswordModal.classList.add('active');
        
        // Initialize Lottie animation
        if (!lottieAnimation) {
            lottieAnimation = lottie.loadAnimation({
                container: lottieContainer,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                animationData: lottieData
            });
        }
    });

    closeModalBtn.addEventListener('click', function() {
        forgotPasswordModal.classList.remove('active');
        if (lottieAnimation) {
            lottieAnimation.stop();
        }
    });

    // Close modal when clicking outside
    forgotPasswordModal.addEventListener('click', function(e) {
        if (e.target === forgotPasswordModal) {
            forgotPasswordModal.classList.remove('active');
            if (lottieAnimation) {
                lottieAnimation.stop();
            }
        }
    });
</script>
@endsection
