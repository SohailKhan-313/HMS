<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>HMS - Staff & Medical Portal Login</title>

  <!-- Google Fonts: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS & Icons -->
  <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      min-height: 100vh;
      background: radial-gradient(circle at top right, #e0f2fe, #f8fafc 40%, #e2e8f0 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      margin: 0;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
    }

    .login-card {
      background: #ffffff;
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 24px;
      box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.03);
      padding: 2.5rem 2.25rem;
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #2563eb, #06b6d4, #10b981);
    }

    .brand-icon {
      width: 56px;
      height: 56px;
      border-radius: 16px;
      background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.35);
      margin: 0 auto 1.25rem;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
      color: #2563eb;
    }

    .form-control {
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      font-size: 0.95rem;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    }

    .btn-login {
      background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
      border: none;
      color: #ffffff;
      font-weight: 600;
      font-size: 0.95rem;
      padding: 0.85rem;
      border-radius: 12px;
      transition: all 0.2s ease;
      box-shadow: 0 10px 20px -5px rgba(29, 78, 216, 0.35);
    }

    .btn-login:hover {
      background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
      transform: translateY(-1px);
      box-shadow: 0 12px 22px -5px rgba(29, 78, 216, 0.45);
      color: #ffffff;
    }

    .role-badge-btn {
      font-size: 0.75rem;
      padding: 0.35rem 0.65rem;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.15s ease;
      border: 1px solid #e2e8f0;
      background: #f8fafc;
      color: #334155;
      font-weight: 500;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }

    .role-badge-btn:hover {
      background: #eff6ff;
      border-color: #bfdbfe;
      color: #1d4ed8;
      transform: translateY(-1px);
    }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <div class="text-center mb-4">
        <div class="brand-icon">
          <i class="bi bi-hospital"></i>
        </div>
        <h3 class="fw-bold text-dark mb-1">HMS Staff Portal</h3>
        <p class="text-secondary font-13 mb-0">Hospital Management & Clinical System</p>
      </div>

      <!-- Alerts -->
      @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 rounded-12 p-3 font-13 mb-3 border-0 bg-success bg-opacity-10 text-success">
          <i class="bi bi-check-circle-fill fs-5"></i>
          <div>{{ session('success') }}</div>
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
          <div class="d-flex align-items-center gap-2 mb-1 fw-semibold">
            <i class="bi bi-exclamation-triangle-fill"></i> Authentication Failed
          </div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Login Form -->
      <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label font-13 fw-semibold text-secondary mb-1">Staff Email Address</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
              <i class="bi bi-envelope"></i>
            </span>
            <input type="email" name="email" id="emailInput" 
                   class="form-control border-start-0 ps-1 @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" 
                   placeholder="doctor@hospital.com" 
                   style="border-radius: 0 12px 12px 0;"
                   required autofocus>
          </div>
        </div>

        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-between mb-1">
            <label class="form-label font-13 fw-semibold text-secondary mb-0">Secure Password</label>
          </div>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
              <i class="bi bi-lock"></i>
            </span>
            <input type="password" name="password" id="passwordInput" 
                   class="form-control border-start-0 border-end-0 px-1 @error('password') is-invalid @enderror" 
                   placeholder="••••••••" 
                   required>
            <button class="btn btn-outline-secondary border-start-0 text-muted" type="button" id="togglePasswordBtn" style="border-radius: 0 12px 12px 0; border-color: #e2e8f0;">
              <i class="bi bi-eye" id="togglePasswordIcon"></i>
            </button>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rememberCheck" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label font-13 text-secondary" for="rememberCheck">
              Remember me
            </label>
          </div>
          <span class="font-12 text-muted"><i class="bi bi-shield-check text-success"></i> 256-bit Encrypted</span>
        </div>

        <button type="submit" class="btn btn-login w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
          <span>Sign In to Dashboard</span>
          <i class="bi bi-arrow-right"></i>
        </button>

        <!-- Create Account / Sign Up Link -->
        <div class="text-center mb-3 p-2 bg-light rounded-12 border">
          <span class="font-13 text-secondary">Don't have an account?</span>
          <a href="{{ route('register') }}" class="font-13 fw-bold text-primary text-decoration-none ms-1">
            <i class="bi bi-person-plus me-1"></i>Create Account / Sign Up
          </a>
        </div>

        <!-- Quick Demo Fill Section -->
        <div class="pt-3 border-top">
          <p class="font-11 text-uppercase text-secondary fw-semibold mb-2 text-center letter-spacing-1">
            Instant Demo Logins (Click to autofill)
          </p>
          <div class="d-flex flex-wrap gap-1 justify-content-center">
            <button type="button" class="role-badge-btn" onclick="fillDemo('admin@hospital.com', 'password123')">
              <i class="bi bi-shield-lock-fill text-primary"></i> Admin
            </button>
            <button type="button" class="role-badge-btn" onclick="fillDemo('doctor@hospital.com', 'password123')">
              <i class="bi bi-heart-pulse-fill text-danger"></i> Doctor
            </button>
            <button type="button" class="role-badge-btn" onclick="fillDemo('hr@hospital.com', 'password123')">
              <i class="bi bi-person-gear text-success"></i> HR
            </button>
            <button type="button" class="role-badge-btn" onclick="fillDemo('accountant@hospital.com', 'password123')">
              <i class="bi bi-wallet2 text-warning"></i> Accountant
            </button>
            <button type="button" class="role-badge-btn" onclick="fillDemo('receptionist@hospital.com', 'password123')">
              <i class="bi bi-headset text-info"></i> Receptionist
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- Security notice -->
    <div class="text-center mt-3 font-12 text-muted">
      Authorized hospital personnel only. Unauthorized access is monitored.
    </div>
  </div>

  <script>
    // Toggle password visibility
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    toggleBtn.addEventListener('click', function() {
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

    // 1-Click demo autofill
    function fillDemo(email, password) {
      document.getElementById('emailInput').value = email;
      document.getElementById('passwordInput').value = password;
      const btn = document.querySelector('.btn-login');
      btn.classList.add('animate__animated', 'animate__pulse');
    }
  </script>

</body>
</html>
