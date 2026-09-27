<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>HMS - Clinical & Hospital Portal Sign In</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS & Icons -->
  <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    :root {
      --primary-600: #2563eb;
      --primary-700: #1d4ed8;
      --primary-800: #1e40af;
      --surface-card: rgba(255, 255, 255, 0.94);
      --text-main: #0f172a;
      --text-muted: #64748b;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.25rem;
      background-color: #f1f5f9;
      background-image: 
        radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(14, 165, 233, 0.15) 0px, transparent 50%),
        radial-gradient(at 50% 100%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
        radial-gradient(circle at 50% 50%, #ffffff 0%, #f8fafc 100%);
      position: relative;
      overflow-x: hidden;
    }

    /* Ambient glowing orbs in the background */
    .bg-orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      z-index: 0;
      pointer-events: none;
      opacity: 0.6;
      animation: floatOrb 12s ease-in-out infinite alternate;
    }

    .bg-orb-1 {
      width: 320px;
      height: 320px;
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.3), rgba(6, 182, 212, 0.3));
      top: -60px;
      left: 10%;
    }

    .bg-orb-2 {
      width: 380px;
      height: 380px;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.25), rgba(168, 85, 247, 0.2));
      bottom: -100px;
      right: 10%;
      animation-delay: -5s;
    }

    @keyframes floatOrb {
      0% { transform: translateY(0) scale(1); }
      100% { transform: translateY(30px) scale(1.08); }
    }

    .login-container {
      width: 100%;
      max-width: 450px;
      position: relative;
      z-index: 1;
    }

    .login-card {
      background: var(--surface-card);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.9);
      border-radius: 28px;
      box-shadow: 
        0 25px 60px -15px rgba(15, 23, 42, 0.12),
        0 0 0 1px rgba(226, 232, 240, 0.8),
        inset 0 1px 1px 0 rgba(255, 255, 255, 0.9);
      padding: 2.75rem 2.5rem 2.25rem;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Top decorative gradient border */
    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(90deg, #0284c7 0%, #2563eb 50%, #4f46e5 100%);
    }

    /* Brand Logo & Icon */
    .brand-icon-wrap {
      width: 64px;
      height: 64px;
      border-radius: 20px;
      background: linear-gradient(135deg, #1e40af 0%, #2563eb 60%, #38bdf8 100%);
      color: #ffffff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      box-shadow: 
        0 12px 24px -6px rgba(37, 99, 235, 0.4),
        inset 0 1px 1px 0 rgba(255, 255, 255, 0.4);
      margin-bottom: 1.25rem;
      position: relative;
    }

    .brand-icon-wrap::after {
      content: '';
      position: absolute;
      inset: -4px;
      border-radius: 24px;
      border: 2px dashed rgba(37, 99, 235, 0.25);
      animation: rotateBorder 24s linear infinite;
    }

    @keyframes rotateBorder {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .portal-title {
      font-weight: 800;
      color: var(--text-main);
      font-size: 1.55rem;
      letter-spacing: -0.025em;
      margin-bottom: 0.35rem;
    }

    .portal-subtitle {
      color: var(--text-muted);
      font-size: 0.88rem;
      font-weight: 500;
      margin-bottom: 0.75rem;
    }

    .live-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      background: #ecfdf5;
      color: #065f46;
      border: 1px solid #a7f3d0;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
      margin-bottom: 1.75rem;
    }

    .pulse-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #10b981;
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      animation: pulseGreen 2s infinite;
    }

    @keyframes pulseGreen {
      0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      }
      70% {
        transform: scale(1);
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
      }
      100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
      }
    }

    /* Form Fields */
    .form-label {
      font-size: 0.85rem;
      font-weight: 600;
      color: #334155;
      margin-bottom: 0.45rem;
      display: flex;
      align-items: center;
      gap: 0.35rem;
    }

    .input-group-custom {
      position: relative;
      display: flex;
      align-items: center;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      background: #f8fafc;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
    }

    .input-group-custom:focus-within {
      background: #ffffff;
      border-color: var(--primary-600);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .input-icon {
      padding: 0.75rem 0.25rem 0.75rem 1rem;
      color: #94a3b8;
      font-size: 1.15rem;
      display: flex;
      align-items: center;
      transition: color 0.2s ease;
    }

    .input-group-custom:focus-within .input-icon {
      color: var(--primary-600);
    }

    .form-control-custom {
      border: none;
      background: transparent;
      padding: 0.8rem 0.9rem;
      font-size: 0.92rem;
      font-weight: 500;
      color: #0f172a;
      width: 100%;
      outline: none;
    }

    .form-control-custom::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    .btn-toggle-eye {
      border: none;
      background: transparent;
      padding: 0 1rem;
      color: #94a3b8;
      cursor: pointer;
      display: flex;
      align-items: center;
      transition: color 0.2s ease, transform 0.1s ease;
    }

    .btn-toggle-eye:hover {
      color: var(--primary-600);
      transform: scale(1.05);
    }

    /* Checkbox & Options */
    .form-check-input {
      width: 1.15rem;
      height: 1.15rem;
      border-radius: 6px;
      border: 1.5px solid #cbd5e1;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .form-check-input:checked {
      background-color: var(--primary-600);
      border-color: var(--primary-600);
    }

    .form-check-input:focus {
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Submit Button */
    .btn-submit-login {
      background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #3b82f6 100%);
      color: #ffffff;
      border: none;
      border-radius: 14px;
      padding: 0.88rem 1.5rem;
      font-size: 0.98rem;
      font-weight: 700;
      letter-spacing: -0.01em;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      box-shadow: 
        0 10px 22px -5px rgba(37, 99, 235, 0.45),
        inset 0 1px 0 0 rgba(255, 255, 255, 0.25);
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .btn-submit-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: 0.5s;
    }

    .btn-submit-login:hover {
      background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 55%, #2563eb 100%);
      transform: translateY(-2px);
      box-shadow: 0 14px 28px -6px rgba(37, 99, 235, 0.55);
      color: #ffffff;
    }

    .btn-submit-login:hover::before {
      left: 100%;
    }

    .btn-submit-login:active {
      transform: translateY(0);
      box-shadow: 0 6px 14px -3px rgba(37, 99, 235, 0.4);
    }

    .btn-submit-login i {
      transition: transform 0.2s ease;
    }

    .btn-submit-login:hover i {
      transform: translateX(4px);
    }

    /* Registration Card Callout */
    .register-card-callout {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 1rem 1.15rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      margin-top: 1.5rem;
      transition: all 0.2s ease;
    }

    .register-card-callout:hover {
      border-color: #cbd5e1;
      background: #ffffff;
      box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.05);
    }

    .register-btn-pill {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      color: var(--primary-700);
      font-size: 0.84rem;
      font-weight: 700;
      padding: 0.45rem 0.95rem;
      border-radius: 10px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: all 0.2s ease;
      white-space: nowrap;
    }

    .register-btn-pill:hover {
      background: #eff6ff;
      border-color: #93c5fd;
      color: var(--primary-800);
      transform: translateY(-1px);
    }

    /* Security Footer */
    .footer-security-note {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 0.78rem;
      color: #64748b;
      margin-top: 1.5rem;
      text-align: center;
    }
  </style>
</head>
<body>

  <!-- Floating Ambient Glowing Orbs -->
  <div class="bg-orb bg-orb-1"></div>
  <div class="bg-orb bg-orb-2"></div>

  <div class="login-container">
    <div class="login-card">

      <!-- Header & Branding -->
      <div class="text-center">
        <div class="brand-icon-wrap">
          <i class="bi bi-hospital"></i>
        </div>
        <h1 class="portal-title">HMS Clinical Portal</h1>
        <p class="portal-subtitle">Hospital Information & Staff Management</p>
        <div>
          <span class="live-status-pill">
            <span class="pulse-dot"></span>
            System Online & Secure
          </span>
        </div>
      </div>

      <!-- Flash Notifications -->
      @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 rounded-12 p-3 font-13 mb-3 border-0 bg-success bg-opacity-10 text-success">
          <i class="bi bi-check-circle-fill fs-5"></i>
          <div>{{ session('success') }}</div>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 rounded-12 p-3 font-13 mb-3 border-0 bg-danger bg-opacity-10 text-danger">
          <i class="bi bi-exclamation-triangle-fill fs-5"></i>
          <div>{{ session('error') }}</div>
        </div>
      @endif

      @if (session('info'))
        <div class="alert alert-info d-flex align-items-center gap-2 rounded-12 p-3 font-13 mb-3 border-0 bg-info bg-opacity-10 text-info">
          <i class="bi bi-info-circle-fill fs-5"></i>
          <div>{{ session('info') }}</div>
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger rounded-12 p-3 font-13 mb-3 border-0 bg-danger bg-opacity-10 text-danger">
          <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
            <i class="bi bi-exclamation-octagon-fill"></i> Authentication Failed
          </div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Sign In Form -->
      <form action="{{ route('login.post') }}" method="POST" autocomplete="on">
        @csrf

        <!-- Email Field -->
        <div class="mb-3">
          <label class="form-label" for="emailInput">
            <i class="bi bi-envelope text-primary"></i>
            Staff Email Address
          </label>
          <div class="input-group-custom">
            <span class="input-icon">
              <i class="bi bi-person-badge"></i>
            </span>
            <input type="email" 
                   name="email" 
                   id="emailInput" 
                   class="form-control-custom" 
                   value="{{ old('email') }}" 
                   placeholder="doctor@hospital.com" 
                   required 
                   autofocus>
          </div>
        </div>

        <!-- Password Field -->
        <div class="mb-3">
          <label class="form-label" for="passwordInput">
            <i class="bi bi-key text-primary"></i>
            Staff Password
          </label>
          <div class="input-group-custom">
            <span class="input-icon">
              <i class="bi bi-shield-lock"></i>
            </span>
            <input type="password" 
                   name="password" 
                   id="passwordInput" 
                   class="form-control-custom" 
                   placeholder="Enter your password" 
                   required>
            <button type="button" class="btn-toggle-eye" id="togglePasswordBtn" title="Toggle password visibility">
              <i class="bi bi-eye" id="togglePasswordIcon"></i>
            </button>
          </div>
        </div>

        <!-- Remember Me & Encryption Pill -->
        <div class="d-flex align-items-center justify-content-between mb-4 pt-1">
          <div class="form-check d-flex align-items-center gap-2 mb-0">
            <input class="form-check-input" 
                   type="checkbox" 
                   name="remember" 
                   id="rememberCheck" 
                   value="1" 
                   {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label font-13 text-secondary" for="rememberCheck" style="cursor: pointer;">
              Remember my session
            </label>
          </div>
          <span class="font-12 fw-semibold text-muted d-flex align-items-center gap-1">
            <i class="bi bi-shield-check text-success fs-6"></i> 256-bit SSL
          </span>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-submit-login">
          <span>Sign In to Dashboard</span>
          <i class="bi bi-arrow-right"></i>
        </button>

        <!-- New Staff Registration Callout -->
        <div class="register-card-callout">
          <div>
            <div class="fw-bold text-dark font-13">New Staff Member?</div>
            <div class="text-muted font-12">Create an account to request access</div>
          </div>
          <a href="{{ route('register') }}" class="register-btn-pill">
            <i class="bi bi-person-plus-fill"></i>
            <span>Register</span>
          </a>
        </div>
      </form>
    </div>

    <!-- Security & Compliance Notice -->
    <div class="footer-security-note">
      <i class="bi bi-lock-fill text-muted"></i>
      <span>Authorized hospital personnel only. All access is logged & monitored.</span>
    </div>
  </div>

  <script>
    // Toggle Password Visibility
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && toggleIcon) {
      toggleBtn.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          toggleIcon.classList.remove('bi-eye');
          toggleIcon.classList.add('bi-eye-slash');
        } else {
          passwordInput.type = 'password';
          toggleIcon.classList.remove('bi-eye-slash');
          toggleIcon.classList.add('bi-eye');
        }
      });
    }
  </script>

</body>
</html>
