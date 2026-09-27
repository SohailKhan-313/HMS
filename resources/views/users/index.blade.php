@extends('layout.master')

@section('content')
<main class="page-content">
  <!-- Page Header & Metrics Cards -->
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-semibold">
          <i class="bi bi-shield-lock-fill me-1"></i> Hospital Security & Access Control
        </span>
      </div>
      <h4 class="mb-0 fw-bold text-dark">Staff Users & Role-Based Access Control</h4>
      <p class="mb-0 text-muted font-13">Create hospital user accounts, assign roles, manage module permissions, and control staff status.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-secondary btn-sm shadow-sm" data-bs-toggle="collapse" data-bs-target="#permissionsMatrixCard">
        <i class="bi bi-info-circle me-1"></i> Role Permissions Guide
      </button>
      <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add New User
      </button>
    </div>
  </div>

  <!-- Role Permissions Reference Card (Collapsible) -->
  <div class="collapse mb-4" id="permissionsMatrixCard">
    <div class="card border-0 shadow-sm radius-12 bg-white">
      <div class="card-header bg-light border-bottom-0 py-3">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-diagram-3 me-2 text-primary"></i>Departmental Access & Role Matrix</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0 font-13 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 15%;">Role</th>
                <th style="width: 45%;">Accessible Modules & Pages</th>
                <th style="width: 40%;">Special Permissions & Restrictions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span class="badge bg-purple text-white px-2 py-1 radius-6"><i class="bi bi-shield-lock me-1"></i> Admin</span></td>
                <td>Dashboard, Appointments, Doctors, Staff, Patients, Payments, Expenses, Reports, User Management</td>
                <td><span class="text-success fw-semibold">Full System Access:</span> Can add, edit, and delete records across all hospital modules.</td>
              </tr>
              <tr>
                <td><span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-heart-pulse me-1"></i> Doctor</span></td>
                <td>Dashboard, Appointments, Doctors Roster</td>
                <td><span class="text-secondary">View-only on Doctors roster.</span> <strong>Cannot edit or delete doctors</strong>. Can manage clinical appointment diagnosis and prescriptions.</td>
              </tr>
              <tr>
                <td><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-person-gear me-1"></i> HR</span></td>
                <td>Dashboard, Staff Directory, Doctors Management, Patients Directory</td>
                <td><span class="text-success fw-semibold">Full Doctor & Staff Control:</span> Can create, edit, update duty schedules, and delete doctors.</td>
              </tr>
              <tr>
                <td><span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-wallet2 me-1"></i> Accountant</span></td>
                <td>Dashboard, Hospital Payments Ledger, Daily Expenses, Expense Categories, Financial Reports</td>
                <td><span class="text-primary fw-semibold">Financial Control:</span> Manages patient invoices, discounts, daily operational expenses, and audit statements.</td>
              </tr>
              <tr>
                <td><span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-headset me-1"></i> Receptionist</span></td>
                <td>Appointments, Patients & History, Doctors Schedule Inquiry</td>
                <td><span class="text-secondary">Front Desk:</span> Books appointments, registers patients, and handles outpatient check-in.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Role Counts KPI Row -->
  <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-3 mb-4">
    <div class="col">
      <div class="card border-0 shadow-sm radius-12 p-3 text-center bg-white h-100">
        <span class="text-secondary font-12 text-uppercase fw-semibold">Total Users</span>
        <h4 class="mb-0 fw-bold text-dark mt-1">{{ $metrics['total'] }}</h4>
      </div>
    </div>
    <div class="col">
      <div class="card border-0 shadow-sm radius-12 p-3 text-center bg-white h-100 border-start border-4 border-primary">
        <span class="text-primary font-12 text-uppercase fw-semibold">Admins</span>
        <h4 class="mb-0 fw-bold text-primary mt-1">{{ $metrics['admins'] }}</h4>
      </div>
    </div>
    <div class="col">
      <div class="card border-0 shadow-sm radius-12 p-3 text-center bg-white h-100 border-start border-4 border-danger">
        <span class="text-danger font-12 text-uppercase fw-semibold">Doctors</span>
        <h4 class="mb-0 fw-bold text-danger mt-1">{{ $metrics['doctors'] }}</h4>
      </div>
    </div>
    <div class="col">
      <div class="card border-0 shadow-sm radius-12 p-3 text-center bg-white h-100 border-start border-4 border-success">
        <span class="text-success font-12 text-uppercase fw-semibold">HR Managers</span>
        <h4 class="mb-0 fw-bold text-success mt-1">{{ $metrics['hr'] }}</h4>
      </div>
    </div>
    <div class="col">
      <div class="card border-0 shadow-sm radius-12 p-3 text-center bg-white h-100 border-start border-4 border-warning">
        <span class="text-warning font-12 text-uppercase fw-semibold">Accountants</span>
        <h4 class="mb-0 fw-bold text-warning mt-1">{{ $metrics['accountants'] }}</h4>
      </div>
    </div>
    <div class="col">
      <div class="card border-0 shadow-sm radius-12 p-3 text-center bg-white h-100 border-start border-4 border-info">
        <span class="text-info font-12 text-uppercase fw-semibold">Receptionists</span>
        <h4 class="mb-0 fw-bold text-info mt-1">{{ $metrics['receptionists'] }}</h4>
      </div>
    </div>
  </div>

  <!-- Notification Alerts -->
  @if (session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 rounded-12 p-3 shadow-sm border-0 mb-4 bg-success bg-opacity-10 text-success">
      <i class="bi bi-check-circle-fill fs-5"></i>
      <div class="fw-semibold">{{ session('success') }}</div>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 rounded-12 p-3 shadow-sm border-0 mb-4 bg-danger bg-opacity-10 text-danger">
      <i class="bi bi-exclamation-octagon-fill fs-5"></i>
      <div class="fw-semibold">{{ session('error') }}</div>
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger rounded-12 p-3 shadow-sm border-0 mb-4 bg-danger bg-opacity-10 text-danger">
      <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Form Submission Errors:</div>
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Main Users Card & Filter Table -->
  <div class="card border-0 shadow-sm radius-12 bg-white">
    <div class="card-header bg-white border-bottom py-3">
      <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
        <!-- Search bar -->
        <div class="col-12 col-md-5">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
          </div>
        </div>

        <!-- Role Filter -->
        <div class="col-6 col-md-3">
          <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>Filter by Role: All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
            <option value="hr" {{ request('role') == 'hr' ? 'selected' : '' }}>HR</option>
            <option value="accountant" {{ request('role') == 'accountant' ? 'selected' : '' }}>Accountant</option>
            <option value="receptionist" {{ request('role') == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
          </select>
        </div>

        <!-- Status Filter -->
        <div class="col-6 col-md-2">
          <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Status: All</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <!-- Submit & Reset -->
        <div class="col-12 col-md-2 d-flex gap-1 justify-content-end">
          <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Filter</button>
          <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filters"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
      </form>
    </div>

    <!-- Table -->
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 font-13">
          <thead class="table-light text-secondary text-uppercase font-11">
            <tr>
              <th class="ps-3 py-3">Staff User</th>
              <th>Contact Info</th>
              <th>Assigned Role</th>
              <th>Status</th>
              <th>Linked Doctor</th>
              <th>Joined Date</th>
              <th class="text-end pe-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $u)
              <tr>
                <td class="ps-3 py-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold font-12" 
                         style="width: 38px; height: 38px; background: {{ $u->role === 'admin' ? '#7c3aed' : ($u->role === 'doctor' ? '#dc2626' : ($u->role === 'hr' ? '#16a34a' : ($u->role === 'accountant' ? '#d97706' : '#0284c7'))) }};">
                      {{ strtoupper(substr($u->name, 0, 2)) }}
                    </div>
                    <div>
                      <div class="fw-semibold text-dark">{{ $u->name }}</div>
                      <div class="font-12 text-muted">{{ $u->email }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  @if ($u->phone)
                    <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-telephone me-1"></i> {{ $u->phone }}</span>
                  @else
                    <span class="text-muted font-12">None</span>
                  @endif
                </td>
                <td>
                  @if ($u->role === 'admin')
                    <span class="badge bg-purple text-white px-2 py-1 radius-6"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                  @elseif ($u->role === 'doctor')
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-heart-pulse-fill me-1"></i> Doctor</span>
                  @elseif ($u->role === 'hr')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-person-gear me-1"></i> HR</span>
                  @elseif ($u->role === 'accountant')
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-wallet2 me-1"></i> Accountant</span>
                  @else
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 radius-6"><i class="bi bi-headset me-1"></i> Receptionist</span>
                  @endif
                </td>
                <td>
                  @if ($u->status === 'active')
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 radius-6">
                      <i class="bi bi-check-circle-fill me-1"></i> Active
                    </span>
                  @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 radius-6">
                      <i class="bi bi-dash-circle-fill me-1"></i> Inactive
                    </span>
                  @endif
                </td>
                <td>
                  @if ($u->doctor)
                    <span class="text-primary font-12 fw-semibold"><i class="bi bi-person-badge me-1"></i> {{ $u->doctor->name }} ({{ $u->doctor->speciality }})</span>
                  @else
                    <span class="text-muted font-12">—</span>
                  @endif
                </td>
                <td>
                  <span class="font-12 text-secondary">{{ $u->created_at ? $u->created_at->format('M d, Y') : '—' }}</span>
                </td>
                <td class="text-end pe-3">
                  <div class="btn-group btn-group-sm">
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-outline-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editUserModal{{ $u->id }}" 
                            title="Edit User & Roles">
                      <i class="bi bi-pencil-square"></i> Edit
                    </button>

                    <!-- Toggle Status Button -->
                    @if ($u->id !== auth()->id())
                      <form action="{{ route('users.toggle-status', $u->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-{{ $u->status === 'active' ? 'warning' : 'success' }}" 
                                title="{{ $u->status === 'active' ? 'Deactivate user' : 'Activate user' }}">
                          <i class="bi bi-power"></i>
                        </button>
                      </form>
                    @endif

                    <!-- Delete Button -->
                    @if ($u->id !== auth()->id())
                      <button type="button" class="btn btn-outline-danger" 
                              data-bs-toggle="modal" 
                              data-bs-target="#deleteUserModal{{ $u->id }}" 
                              title="Delete User">
                        <i class="bi bi-trash"></i>
                      </button>
                    @endif
                  </div>

                  <!-- Edit User Modal -->
                  <div class="modal fade text-start" id="editUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content radius-16 border-0 shadow-lg">
                        <div class="modal-header bg-light border-0 py-3">
                          <h6 class="modal-title fw-bold text-dark"><i class="bi bi-person-gear text-primary me-2"></i>Edit User & Assign Role</h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('users.update', $u->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="modal-body p-4">
                            <div class="mb-3">
                              <label class="form-label font-12 fw-semibold text-secondary">Full Name*</label>
                              <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $u->name) }}" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label font-12 fw-semibold text-secondary">Email Address*</label>
                              <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $u->email) }}" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label font-12 fw-semibold text-secondary">Phone Number</label>
                              <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $u->phone) }}">
                            </div>

                            <div class="row g-2 mb-3">
                              <div class="col-md-6">
                                <label class="form-label font-12 fw-semibold text-secondary">Hospital Role*</label>
                                <select name="role" class="form-select form-select-sm" required>
                                  <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                                  <option value="doctor" {{ $u->role === 'doctor' ? 'selected' : '' }}>Doctor (Appointments & Roster)</option>
                                  <option value="hr" {{ $u->role === 'hr' ? 'selected' : '' }}>HR (Staff & Doctors Management)</option>
                                  <option value="accountant" {{ $u->role === 'accountant' ? 'selected' : '' }}>Accountant (Payments & Expenses)</option>
                                  <option value="receptionist" {{ $u->role === 'receptionist' ? 'selected' : '' }}>Receptionist (Front Desk)</option>
                                </select>
                              </div>
                              <div class="col-md-6">
                                <label class="form-label font-12 fw-semibold text-secondary">Account Status*</label>
                                <select name="status" class="form-select form-select-sm" required>
                                  <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>Active</option>
                                  <option value="inactive" {{ $u->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                              </div>
                            </div>

                            <div class="mb-3">
                              <label class="form-label font-12 fw-semibold text-secondary">Link with Doctor Profile (Optional)</label>
                              <select name="doctor_id" class="form-select form-select-sm">
                                <option value="">-- None / Not a doctor --</option>
                                @foreach ($doctors as $doc)
                                  <option value="{{ $doc->id }}" {{ $u->doctor_id == $doc->id ? 'selected' : '' }}>{{ $doc->name }} ({{ $doc->speciality }})</option>
                                @endforeach
                              </select>
                            </div>

                            <div class="mb-0 pt-2 border-top">
                              <label class="form-label font-12 fw-semibold text-secondary">Update Password (Leave blank to keep unchanged)</label>
                              <input type="password" name="password" class="form-control form-control-sm" placeholder="••••••••">
                            </div>
                          </div>
                          <div class="modal-footer bg-light border-0 py-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- Delete Confirmation Modal -->
                  <div class="modal fade text-start" id="deleteUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                      <div class="modal-content radius-16 border-0 shadow-lg text-center p-3">
                        <div class="text-danger my-2 fs-1"><i class="bi bi-exclamation-circle"></i></div>
                        <h6 class="fw-bold mb-1">Delete User?</h6>
                        <p class="font-12 text-muted mb-3">Are you sure you want to remove <strong>{{ $u->name }}</strong> from HMS? This action cannot be undone.</p>
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-danger px-3">Yes, Delete</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="bi bi-people fs-1 text-secondary opacity-50 d-block mb-2"></i>
                  No users found matching the selected filter criteria.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if ($users->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>

  <!-- Create New User Modal -->
  <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content radius-16 border-0 shadow-lg">
        <div class="modal-header bg-light border-0 py-3">
          <h6 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus text-primary me-2"></i>Create New Staff User</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <div class="mb-3">
              <label class="form-label font-12 fw-semibold text-secondary">Full Name*</label>
              <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Dr. Ayesha Ahmed" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label font-12 fw-semibold text-secondary">Email Address*</label>
              <input type="email" name="email" class="form-control form-control-sm" placeholder="ayesha@hospital.com" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label font-12 fw-semibold text-secondary">Phone Number</label>
              <input type="text" name="phone" class="form-control form-control-sm" placeholder="03001234567" value="{{ old('phone') }}">
            </div>

            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <label class="form-label font-12 fw-semibold text-secondary">Assigned Role*</label>
                <select name="role" class="form-select form-select-sm" required>
                  <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor (Appointments & Roster)</option>
                  <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR (Staff & Doctors Management)</option>
                  <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant (Payments & Expenses)</option>
                  <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>Receptionist (Front Desk)</option>
                  <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Full System Access)</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label font-12 fw-semibold text-secondary">Initial Status*</label>
                <select name="status" class="form-select form-select-sm" required>
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label font-12 fw-semibold text-secondary">Link with Doctor Profile (If role is Doctor)</label>
              <select name="doctor_id" class="form-select form-select-sm">
                <option value="">-- None / Not a doctor --</option>
                @foreach ($doctors as $doc)
                  <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->name }} ({{ $doc->speciality }})</option>
                @endforeach
              </select>
            </div>

            <div class="mb-0 pt-2 border-top">
              <label class="form-label font-12 fw-semibold text-secondary">Initial Login Password*</label>
              <input type="password" name="password" class="form-control form-control-sm" placeholder="Minimum 6 characters" required>
            </div>
          </div>
          <div class="modal-footer bg-light border-0 py-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary">Create User Account</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection
