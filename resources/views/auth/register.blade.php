<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>HMS - Create Account & Staff Registration</title>

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
      padding: 2rem 1rem;
      margin: 0;
    }

    .register-container {
      width: 100%;
      max-width: 520px;
    }

    .register-card {
      background: #ffffff;
      border: 1px solid rgba(226, 232, 240, 0.8);
      border-radius: 24px;
      box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.03);
      padding: 2.5rem 2.25rem;
      position: relative;
      overflow: hidden;
    }

    .register-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #10b981, #06b6d4, #2563eb);
    }

    .brand-icon {
      width: 56px;
      height: 56px;
      border-radius: 16px;
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.35);
      margin: 0 auto 1.25rem;
    }

    .form-control, .form-select {
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      font-size: 0.92rem;
      padding: 0.65rem 0.85rem;
      transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: #10b981;
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
    }

    .btn-register {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      border: none;
      color: #ffffff;
      font-weight: 600;
      font-size: 0.95rem;
      padding: 0.85rem;
      border-radius: 12px;
      transition: all 0.2s ease;
      box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.35);
    }

    .btn-register:hover {
      background: linear-gradient(135deg, #047857 0%, #059669 100%);
      transform: translateY(-1px);
      box-shadow: 0 12px 22px -5px rgba(16, 185, 129, 0.45);
      color: #ffffff;
    }
  </style>
</head>
<body>

  <div class="register-container">
    <div class="register-card">
      <div class="text-center mb-4">
        <div class="brand-icon">
          <i class="bi bi-person-plus-fill"></i>
        </div>
        <h3 class="fw-bold text-dark mb-1">Create HMS Account</h3>
        <p class="text-secondary font-13 mb-0">Join the Hospital Clinical & Administrative Management Portal</p>
      </div>

      <!-- Errors -->
      @if ($errors->any())
        <div class="alert alert-danger rounded-12 p-3 font-13 mb-3 border-0 bg-danger bg-opacity-10 text-danger">
          <div class="d-flex align-items-center gap-2 mb-1 fw-semibold">
            <i class="bi bi-exclamation-triangle-fill"></i> Registration Errors
          </div>
          <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Registration Form -->
      <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <div class="row g-3">
          <!-- Full Name -->
          <div class="col-12">
            <label class="form-label font-13 fw-semibold text-secondary mb-1">Full Name *</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
                <i class="bi bi-person"></i>
              </span>
              <input type="text" name="name" class="form-control border-start-0 ps-1 @error('name') is-invalid @enderror" 
                     value="{{ old('name') }}" placeholder="Dr. Sarah Ahmed / John Doe" style="border-radius: 0 12px 12px 0;" required autofocus>
            </div>
          </div>

          <!-- Email -->
          <div class="col-12 col-md-6">
            <label class="form-label font-13 fw-semibold text-secondary mb-1">Email Address *</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
                <i class="bi bi-envelope"></i>
              </span>
              <input type="email" name="email" class="form-control border-start-0 ps-1 @error('email') is-invalid @enderror" 
                     value="{{ old('email') }}" placeholder="name@hospital.com" style="border-radius: 0 12px 12px 0;" required>
            </div>
          </div>

          <!-- Phone -->
          <div class="col-12 col-md-6">
            <label class="form-label font-13 fw-semibold text-secondary mb-1">Phone Number</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
                <i class="bi bi-telephone"></i>
              </span>
              <input type="text" name="phone" class="form-control border-start-0 ps-1 @error('phone') is-invalid @enderror" 
                     value="{{ old('phone') }}" placeholder="0300 1234567" style="border-radius: 0 12px 12px 0;">
            </div>
          </div>

          <!-- Assigned Role -->
          <div class="col-12">
            <label class="form-label font-13 fw-semibold text-secondary mb-1">Hospital Role & Access Level *</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
                <i class="bi bi-shield-lock"></i>
              </span>
              <select name="role" id="roleSelect" class="form-select border-start-0 ps-1 @error('role') is-invalid @enderror" style="border-radius: 0 12px 12px 0;" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your departmental role...</option>
                <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Doctor (Clinical Appointments &amp; Roster)</option>
                <option value="receptionist" {{ old('role') === 'receptionist' ? 'selected' : '' }}>Front Desk Receptionist (Appointments &amp; Patients)</option>
                <option value="hr" {{ old('role') === 'hr' ? 'selected' : '' }}>HR Manager (Staff &amp; Doctor Scheduling)</option>
                <option value="accountant" {{ old('role') === 'accountant' ? 'selected' : '' }}>Chief Accountant (Payments &amp; Expenses)</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Universal Full Access)</option>
              </select>
            </div>
            <small class="text-muted font-11 mt-1 d-block">Your dashboard features and blade permissions will match your selected department.</small>
          </div>

          <!-- Doctor Specific Fields (Auto-toggled via Javascript) -->
          <div class="col-12 d-none" id="doctorExtraFields">
            <div class="p-3 bg-light rounded-12 border">
              <h6 class="font-12 fw-bold text-primary mb-2 text-uppercase letter-spacing-1">
                <i class="bi bi-hospital me-1"></i> Doctor Medical Profile Details
              </h6>
              <div class="row g-2">
                <div class="col-12 col-md-6">
                  <label class="form-label font-11 text-secondary mb-1">Medical Speciality</label>
                  <input type="text" name="speciality" class="form-control form-control-sm" value="{{ old('speciality', 'General Physician') }}" placeholder="e.g. Cardiologist, Neurologist">
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label font-11 text-secondary mb-1">PMDC Reg No.</label>
                  <input type="text" name="pmdc" class="form-control form-control-sm" value="{{ old('pmdc') }}" placeholder="e.g. PMC-45892">
                </div>
                <div class="col-12">
                  <label class="form-label font-11 text-secondary mb-1">Consultation Fee (PKR)</label>
                  <input type="number" name="fee" class="form-control form-control-sm" value="{{ old('fee', 1500) }}" placeholder="1500" min="0">
                </div>
              </div>
            </div>
          </div>

          <!-- Password -->
          <div class="col-12 col-md-6">
            <label class="form-label font-13 fw-semibold text-secondary mb-1">Password *</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
                <i class="bi bi-lock"></i>
              </span>
              <input type="password" name="password" id="regPassword" class="form-control border-start-0 ps-1 @error('password') is-invalid @enderror" 
                     placeholder="Min. 6 chars" style="border-radius: 0 12px 12px 0;" required>
            </div>
          </div>

          <!-- Confirm Password -->
          <div class="col-12 col-md-6">
            <label class="form-label font-13 fw-semibold text-secondary mb-1">Confirm Password *</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border-color: #e2e8f0;">
                <i class="bi bi-lock-fill"></i>
              </span>
              <input type="password" name="password_confirmation" id="regConfirmPassword" class="form-control border-start-0 ps-1" 
                     placeholder="Re-type password" style="border-radius: 0 12px 12px 0;" required>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-register w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            <span>Create Account &amp; Access Dashboard</span>
          </button>
        </div>
      </form>

      <!-- Back to Login -->
      <div class="text-center mt-4 pt-3 border-top">
        <p class="font-13 text-secondary mb-0">
          Already have an account? 
          <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">
            Sign In Here <i class="bi bi-arrow-right-short"></i>
          </a>
        </p>
      </div>

    </div>
  </div>

  <script>
    const roleSelect = document.getElementById('roleSelect');
    const doctorExtraFields = document.getElementById('doctorExtraFields');

    function checkRole() {
      if (roleSelect.value === 'doctor') {
        doctorExtraFields.classList.remove('d-none');
      } else {
        doctorExtraFields.classList.add('d-none');
      }
    }

    roleSelect.addEventListener('change', checkRole);
    // Trigger on page load in case of validation back with old input
    checkRole();
  </script>

</body>
</html>
