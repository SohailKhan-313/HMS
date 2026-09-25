@extends('layout.master')
@section('content')

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Google Font: Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600&display=swap"
    rel="stylesheet">
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Minimal custom overrides – exactly from Staff list */
    /* Body & Font */
    body {
        background: #f1f5f9;
        font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }

    /* Card Styling */
    .appointment-card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid #e9eef2;
        padding: 1.75rem 2rem 1rem 2rem;
    }

    /* Table Styling */
    .table {
        border-collapse: collapse;
        /* Remove gaps between cells */
        width: 100%;
    }

    .table thead th {
        color: #4a5f7a;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1.5px solid #dfe7ef;
        padding: 0.8rem 0.4rem;
        /* smaller padding for tighter columns */
        text-align: center;
        /* optional, makes it look neat */
    }

    .table tbody td {
        padding: 0.6rem 0.4rem;
        /* smaller vertical & horizontal padding */
        vertical-align: middle;
        color: #1f2c41;
        border-bottom: 1px solid #edf2f7;
        text-align: center;
        /* optional */
    }

    /* Row Hover Effect */
    .table tbody tr:hover td {
        background-color: #f9fcff;
    }

    /* Optional: Make icons/buttons not stretch cells */
    .table tbody td .action-icon {
        margin: 0 2px;
        /* small spacing between icons */
        padding: 4px;
        /* smaller clickable area but readable */
    }

    /* Optional: Responsive text wrap */
    .table td,
    .table th {
        white-space: nowrap;
        /* prevents wide gaps for short content */
    }

    /* Name badge (soft blue) */
    .name {
        background: #e1ecfe;
        color: #194a7a;
        font-weight: 500;
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    /* Specialty badge (same style but different colour if desired) */
    .speciality-badge {
        background: #e1ecfe;
        color: #194a7a;
        font-weight: 500;
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    /* Phone number style */
    .phone-number {
        background: #f4f7fb;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-family: 'Inter', monospace;
        font-size: 0.9rem;
    }

    /* Email link */
    a.email-link {
        color: #1f5f9e;
        text-decoration: none;
        border-bottom: 1px dashed #b3c9e0;
    }

    a.email-link:hover {
        border-bottom: 1px solid #1f5f9e;
    }

    /* For PMDC, duty days, duty time – use a subtle background */
    .info-badge {
        background: #f0f4fa;
        color: #2d4059;
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 30px;
        display: inline-block;
    }

    /* Action icons */
    .action-icon {
        color: #577a9e;
        font-size: 1.3rem;
        padding: 0.3rem;
        border-radius: 8px;
        transition: 0.15s;
        display: inline-block;
        margin-right: 0.2rem;
    }

    .action-icon:hover {
        background: #e3ecf5;
        color: #1b4c7c;
    }

    /* Modal improvements (exactly from Staff list) */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid #e9eef2;
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #4a5f7a;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control {
        border: 2px solid #e9eef2;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: all 0.2s;
        color: white;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-cancel {
        background: #f1f5f9;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        color: #4a5f7a;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    /* Delete modal specific */
    .delete-modal .modal-header {
        background: linear-gradient(135deg, #f56565 0%, #c53030 100%);
    }

    .delete-icon {
        font-size: 4rem;
        color: #f56565;
        margin-bottom: 1rem;
    }

    .btn-delete {
        background: linear-gradient(135deg, #f56565 0%, #c53030 100%);
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(245, 101, 101, 0.4);
        color: white;
    }

    /* Pagination style */
    .pagination .page-link {
        border: none;
        color: #1f5f9e;
        background: transparent;
    }

    .pagination .active .page-link {
        background: #1f5f9e;
        color: white;
        border-radius: 8px;
    }
</style>

<main class="page-content">
    <div class="container-fluid px-0">
        <div class="card appointment-card">

            <!-- HEADER with title and New Doctor button -->
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h2 class="h3 mb-0 fw-semibold d-flex align-items-center gap-3" style="color:#0b1b2f;">
                    <i class="bi bi-person-badge fs-1 text-primary"
                        style="background:#eef4fe; padding:0.5rem; border-radius:14px;"></i>
                    Doctors
                </h2>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#doctorModal">
                        <i class="bi bi-plus-circle me-2"></i> New Doctor
                    </button>
                </div>
            </div>

            <!-- Toast Notifications (exactly from Staff) -->
            <div class="position-fixed top-0 end-0 p-3" style="z-index:1080">
                @php
                $toastMessage = '';
                $toastIcon = '';
                $textColor = '';
                @endphp

                @if(session('success') || session('update') || session('delete'))
                @php
                if(session('success')) {
                $toastMessage = session('success');
                $toastIcon = 'bi-check-circle-fill text-success';
                $textColor = 'text-success';
                } elseif(session('update')) {
                $toastMessage = session('update');
                $toastIcon = 'bi-pencil-square text-primary';
                $textColor = 'text-primary';
                } elseif(session('delete')) {
                $toastMessage = session('delete');
                $toastIcon = 'bi-trash-fill text-danger';
                $textColor = 'text-danger';
                }
                @endphp
                <div id="actionToast" class="toast border-0 shadow-lg bg-white" role="alert">
                    <div class="toast-body">
                        <div class="d-flex align-items-center">
                            <div class="fs-3">
                                <i class="bi {{ $toastIcon }}"></i>
                            </div>
                            <div class="ms-3">
                                <div class="{{ $textColor }}">
                                    {{ $toastMessage }}
                                </div>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @if(session('success') || session('update') || session('delete'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const toastEl = document.getElementById('actionToast');
                    if (toastEl) {
                        const toast = new bootstrap.Toast(toastEl, {
                            delay: 2000,
                            autohide: true
                        });
                        toast.show();
                    }
                });
            </script>
            @endif
            <!-- ADD DOCTOR MODAL -->
            <div class="modal fade" id="doctorModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Add New Doctor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('doctors.store') }}" method="POST" id="addDoctorForm">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label>Doctor Name*</label>
                                        <input type="text" name="name" class="form-control" placeholder="sohail" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Speciality*</label>
                                        <input type="text" name="speciality" class="form-control" placeholder="surgen" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Phone*</label>
                                        <input type="text" name="phone" class="form-control" placeholder="+92  3345467" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Email*</label>
                                        <input type="email" name="email" class="form-control" placeholder="sohail@gmail.com" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>PMDC*</label>
                                        <input type="text" name="pmdc" class="form-control" placeholder="dfg-43" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>FEE*</label>
                                        <input type="text" name="fee" class="form-control" placeholder="1500" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Duty Days*</label>

                                        @php
                                        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                        @endphp

                                        <div class="row">
                                            @foreach($days as $day)
                                            <div class="col-md-3 mb-3">
                                                <div class="d-flex flex-column gap-1 border p-2 rounded">

                                                    <div class="d-flex align-items-center">
                                                        <input type="checkbox" name="duty_days[]" value="{{ $day }}" id="{{ $day }}_checkbox">
                                                        <label class="ms-2 mb-0" for="{{ $day }}_checkbox">{{ $day }}</label>
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary w-100"
                                                        onclick="openTimeModal('{{ $day }}')">
                                                        Set Time
                                                    </button>

                                                    <span id="{{ $day }}_display" class="text-success small"></span>

                                                    <input type="hidden" name="duty_time[{{ $day }}][start]" id="{{ $day }}_start">
                                                    <input type="hidden" name="duty_time[{{ $day }}][end]" id="{{ $day }}_end">

                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" form="addDoctorForm" class="btn btn-primary">Save Doctor</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="timeModal" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Set Duty Time</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="current_day">

                            <div class="mb-3">
                                <label>Start Time</label>
                                <input type="time" id="modal_start" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>End Time</label>
                                <input type="time" id="modal_end" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="saveTimeBtn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
            <script>
                (function() {
                    // ============================================
                    // TIME MODAL (shared for Add & Edit)
                    // ============================================
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof bootstrap === 'undefined') {
                            console.error('Bootstrap JavaScript is not loaded!');
                            return;
                        }

                        // Current prefix – will be set by openTimeModal
                        let currentTimeModalPrefix = '';

                        // Expose openTimeModal globally
                        window.openTimeModal = function(day, prefix = '') {
                            currentTimeModalPrefix = prefix;
                            console.log('openTimeModal called', {
                                day,
                                prefix
                            }); // Debug

                            const currentDayInput = document.getElementById('current_day');
                            if (!currentDayInput) {
                                console.error('Element #current_day not found');
                                return;
                            }
                            currentDayInput.value = day;

                            // Pre-fill modal inputs with existing values (if any)
                            const startHidden = document.getElementById(prefix + day + '_start');
                            const endHidden = document.getElementById(prefix + day + '_end');
                            const modalStart = document.getElementById('modal_start');
                            const modalEnd = document.getElementById('modal_end');
                            if (modalStart) modalStart.value = startHidden ? startHidden.value : '';
                            if (modalEnd) modalEnd.value = endHidden ? endHidden.value : '';

                            // Show time modal
                            const timeModalEl = document.getElementById('timeModal');
                            if (!timeModalEl) {
                                console.error('Element #timeModal not found');
                                return;
                            }

                            // **FIX Z-INDEX**: ensure time modal appears above edit modal
                            timeModalEl.style.zIndex = '1060'; // higher than default modal (1055)
                            const modal = new bootstrap.Modal(timeModalEl);
                            modal.show();

                            // Restore z-index when hidden (optional)
                            timeModalEl.addEventListener('hidden.bs.modal', function() {
                                timeModalEl.style.zIndex = '';
                            }, {
                                once: true
                            });
                        };

                        // Save button inside time modal
                        const saveTimeBtn = document.getElementById('saveTimeBtn');
                        if (saveTimeBtn) {
                            saveTimeBtn.addEventListener('click', function() {
                                const day = document.getElementById('current_day')?.value;
                                if (!day) {
                                    alert('No day selected');
                                    return;
                                }

                                const start = document.getElementById('modal_start')?.value || '';
                                const end = document.getElementById('modal_end')?.value || '';
                                console.log('Saving time', {
                                    day,
                                    prefix: currentTimeModalPrefix,
                                    start,
                                    end
                                }); // Debug

                                // Auto-check the day checkbox
                                const checkbox = document.getElementById(currentTimeModalPrefix + day + '_checkbox');
                                if (checkbox) checkbox.checked = true;

                                // Save times to hidden inputs
                                const startHidden = document.getElementById(currentTimeModalPrefix + day + '_start');
                                const endHidden = document.getElementById(currentTimeModalPrefix + day + '_end');
                                if (startHidden) startHidden.value = start;
                                if (endHidden) endHidden.value = end;


                                function formatTo12Hour(time) {
                                    let [hours, minutes] = time.split(':');
                                    hours = parseInt(hours);

                                    let ampm = hours >= 12 ? 'PM' : 'AM';
                                    hours = hours % 12;
                                    hours = hours ? hours : 12;

                                    return hours + ':' + minutes + ' ' + ampm;
                                }

                                // Update display span
                                const displaySpan = document.getElementById(currentTimeModalPrefix + day + '_display');

                                if (displaySpan) {
                                    displaySpan.innerText = formatTo12Hour(start) + ' - ' + formatTo12Hour(end);
                                }

                                // Hide time modal
                                const timeModalEl = document.getElementById('timeModal');
                                if (timeModalEl) {
                                    const modalInstance = bootstrap.Modal.getInstance(timeModalEl);
                                    if (modalInstance) modalInstance.hide();
                                }
                            });
                        } else {
                            console.error('Element #saveTimeBtn not found');
                        }
                    });

                    // ============================================
                    // EDIT MODAL FUNCTIONS
                    // ============================================
                    window.openEditModalFromButton = function(button) {
                        try {
                            const doctor = JSON.parse(button.dataset.doctor);
                            console.log('Doctor data loaded:', doctor); // Debug
                            openEditModal(doctor);
                        } catch (e) {
                            console.error('Failed to parse doctor data:', e);
                            alert('Could not load doctor information. Please try again.');
                        }
                    };

                    window.openEditModal = function(doctor) {
                        if (typeof bootstrap === 'undefined') {
                            console.error('Bootstrap JavaScript is not loaded!');
                            return;
                        }

                        // Set form action
                        const form = document.getElementById('editDoctorForm');
                        if (form) form.action = `/doctors/update/${doctor.id}`;
                        else console.error('Form #editDoctorForm not found');

                        // Fill basic fields
                        setFieldValue('edit_name', doctor.name);
                        setFieldValue('edit_speciality', doctor.speciality);
                        setFieldValue('edit_phone', doctor.phone);
                        setFieldValue('edit_email', doctor.email);
                        setFieldValue('edit_pmdc', doctor.pmdc);
                        setFieldValue('edit_fee', doctor.pmdc);


                        // Reset all duty day fields
                        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        days.forEach(day => {
                            setFieldValue('edit_' + day + '_checkbox', false, 'checkbox');
                            setFieldValue('edit_' + day + '_start', '');
                            setFieldValue('edit_' + day + '_end', '');
                            setFieldValue('edit_' + day + '_display', '');
                        });

                        // Populate duty days and times if they exist
                        if (doctor.duty_days && typeof doctor.duty_days === 'object') {
                            Object.keys(doctor.duty_days).forEach(day => {
                                const duty = doctor.duty_days[day];
                                if (duty && duty.start && duty.end) {
                                    setFieldValue('edit_' + day + '_checkbox', true, 'checkbox');
                                    setFieldValue('edit_' + day + '_start', duty.start);
                                    setFieldValue('edit_' + day + '_end', duty.end);
                                    setFieldValue('edit_' + day + '_display', duty.start + ' - ' + duty.end);
                                }
                            });
                        }

                        // Show the edit modal
                        const modalEl = document.getElementById('editDoctorModal');
                        if (modalEl) {
                            // Reset z-index before showing (in case it was changed)
                            modalEl.style.zIndex = '';
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        } else {
                            console.error('Element #editDoctorModal not found');
                        }
                    };

                    // Helper function to safely set field values
                    function setFieldValue(id, value, type = 'text') {
                        const el = document.getElementById(id);
                        if (!el) return;
                        if (type === 'checkbox') {
                            el.checked = !!value;
                        } else {
                            el.value = value || '';
                        }
                    }
                })();
            </script>
            <!-- DOCTOR TABLE -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Speciality</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>PMDC</th>
                                <th>FEE</th>
                                <th>Duty Days</th>
                                <th>Duty Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $d)
                            <tr>
                                <td><span class="name">{{ $d->name }}</span></td>
                                <td><span class="speciality-badge">{{ $d->speciality }}</span></td>
                                <td><span class="speciality-badge">{{ $d->phone }}</span></td>
                                <td><a href="mailto:{{ $d->email }}" class="email-link">{{ $d->email }}</a></td>
                                <td><span class="info-badge">{{ $d->pmdc }}</span></td>
                                <td><span class="info-badge">{{ $d->fee }}</span></td>
                                <td style="vertical-align: top;">
                                    @foreach(explode(',', $d->duty_days) as $day)
                                    <span class="info-badge d-block w-100 text-center mb-1">
                                        {{ $day }}
                                    </span>
                                    @endforeach
                                </td>
                                @php
                                $schedule = json_decode($d->duty_schedule, true) ?? [];
                                @endphp

                                <td style="vertical-align: top;">
                                    @foreach($schedule as $day => $time)
                                    @if(!empty($time['start']) && !empty($time['end']))
                                    <span class="info-badge d-block h-100 w-100 text-center">
                                        {{ $day }}:
                                        {{ date('g:i A', strtotime($time['start'])) }} -
                                        {{ date('g:i A', strtotime($time['end'])) }}
                                    </span>
                                    @endif
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="action-icon me-2"
                                        data-doctor='@json($doctorsData[$d->id])'
                                        onclick="openEditModalFromButton(this)">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="#" class="action-icon" data-bs-toggle="modal" data-bs-target="#deleteDoctor{{ $d->id }}">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- EDIT DOCTOR MODAL (improved) -->
                            <!-- EDIT DOCTOR MODAL -->
                            <div class="modal fade" id="editDoctorModal" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Doctor</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="" method="POST" id="editDoctorForm">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <label>Doctor Name*</label>
                                                        <input type="text" name="name" id="edit_name" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Speciality*</label>
                                                        <input type="text" name="speciality" id="edit_speciality" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Phone*</label>
                                                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Email*</label>
                                                        <input type="email" name="email" id="edit_email" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>PMDC*</label>
                                                        <input type="text" name="pmdc" id="edit_pmdc" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>FEE*</label>
                                                        <input type="text" name="fee" id="edit_fee" class="form-control" required>
                                                    </div>

                                                    <div class="col-lg-12 col-sm-6">
                                                        <label>Duty Days*</label>

                                                        @php
                                                        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                                        @endphp

                                                        @foreach($days as $day)
                                                        <div class="d-flex align-items-center input-group-text mb-2">
                                                            <!-- Checkbox ID uses edit_ prefix -->
                                                            <input type="checkbox" class="form-check-input" name="duty_days[]" value="{{ $day }}" id="edit_{{ $day }}_checkbox">
                                                            <label class="me-2" for="edit_{{ $day }}_checkbox">{{ $day }}</label>

                                                            <!-- Pass the edit_ prefix to openTimeModal -->
                                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="openTimeModal('{{ $day }}', 'edit_')">
                                                                Set Time
                                                            </button>

                                                            <span id="edit_{{ $day }}_display" class="ms-2 text-success"></span>

                                                            <input type="hidden" name="duty_time[{{ $day }}][start]" id="edit_{{ $day }}_start">
                                                            <input type="hidden" name="duty_time[{{ $day }}][end]" id="edit_{{ $day }}_end">
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" form="editDoctorForm" class="btn btn-primary">Update Doctor</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <script>
                                // ============================================
                                // TIME MODAL (shared for Add & Edit)
                                // ============================================
                                let currentTimeModalPrefix = ''; // '' for add, 'edit_' for edit

                                function openTimeModal(day, prefix = '') {
                                    currentTimeModalPrefix = prefix;

                                    // Set the current day
                                    document.getElementById('current_day').value = day;

                                    // Pre-fill previous times if any (using prefixed IDs)
                                    const startInput = document.getElementById(prefix + day + '_start');
                                    const endInput = document.getElementById(prefix + day + '_end');
                                    document.getElementById('modal_start').value = startInput ? startInput.value : '';
                                    document.getElementById('modal_end').value = endInput ? endInput.value : '';

                                    // Show modal
                                    let modal = new bootstrap.Modal(document.getElementById('timeModal'));
                                    modal.show();
                                }

                                // Save button inside time modal
                                document.getElementById('saveTimeBtn').addEventListener('click', function() {
                                    let day = document.getElementById('current_day').value;
                                    let prefix = currentTimeModalPrefix;
                                    if (!day) {
                                        alert('No day selected');
                                        return;
                                    }

                                    let start = document.getElementById('modal_start').value;
                                    let end = document.getElementById('modal_end').value;

                                    // Auto-check the day checkbox (using prefixed ID)
                                    let checkbox = document.getElementById(prefix + day + '_checkbox');
                                    if (checkbox) checkbox.checked = true;

                                    // Save times to hidden inputs
                                    let startHidden = document.getElementById(prefix + day + '_start');
                                    let endHidden = document.getElementById(prefix + day + '_end');
                                    if (startHidden) startHidden.value = start;
                                    if (endHidden) endHidden.value = end;

                                    // Update display span
                                    let displaySpan = document.getElementById(prefix + day + '_display');
                                    if (displaySpan) displaySpan.innerText = start + ' - ' + end;

                                    // Hide modal
                                    let modalInstance = bootstrap.Modal.getInstance(document.getElementById('timeModal'));
                                    if (modalInstance) modalInstance.hide();
                                });

                                // ============================================
                                // EDIT MODAL FUNCTIONS
                                // ============================================
                                function openEditModalFromButton(button) {
                                    try {
                                        // Parse the JSON from the data-doctor attribute
                                        const doctor = JSON.parse(button.dataset.doctor);
                                        console.log('Doctor data loaded:', doctor); // Debug: remove in production
                                        openEditModal(doctor);
                                    } catch (e) {
                                        console.error('Failed to parse doctor data:', e);
                                        alert('Could not load doctor information. Please try again.');
                                    }
                                }

                                function openEditModal(doctor) {
                                    // Set form action
                                    document.getElementById('editDoctorForm').action = `/doctors/update/${doctor.id}`;

                                    // Fill basic fields
                                    document.getElementById('edit_name').value = doctor.name || '';
                                    document.getElementById('edit_speciality').value = doctor.speciality || '';
                                    document.getElementById('edit_phone').value = doctor.phone || '';
                                    document.getElementById('edit_email').value = doctor.email || '';
                                    document.getElementById('edit_pmdc').value = doctor.pmdc || '';

                                    // Reset all duty day checkboxes, displays, and hidden inputs
                                    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    days.forEach(day => {
                                        const checkbox = document.getElementById('edit_' + day + '_checkbox');
                                        const startHidden = document.getElementById('edit_' + day + '_start');
                                        const endHidden = document.getElementById('edit_' + day + '_end');
                                        const displaySpan = document.getElementById('edit_' + day + '_display');

                                        if (checkbox) checkbox.checked = false;
                                        if (startHidden) startHidden.value = '';
                                        if (endHidden) endHidden.value = '';
                                        if (displaySpan) displaySpan.innerText = '';
                                    });

                                    // Populate duty days and times if they exist
                                    if (doctor.duty_days && typeof doctor.duty_days === 'object') {
                                        Object.keys(doctor.duty_days).forEach(day => {
                                            const duty = doctor.duty_days[day];
                                            if (duty && duty.start && duty.end) {
                                                const checkbox = document.getElementById('edit_' + day + '_checkbox');
                                                const startHidden = document.getElementById('edit_' + day + '_start');
                                                const endHidden = document.getElementById('edit_' + day + '_end');
                                                const displaySpan = document.getElementById('edit_' + day + '_display');

                                                if (checkbox) checkbox.checked = true;
                                                if (startHidden) startHidden.value = duty.start;
                                                if (endHidden) endHidden.value = duty.end;
                                                if (displaySpan) displaySpan.innerText = duty.start + ' - ' + duty.end;
                                            }
                                        });
                                    }

                                    // Show the modal
                                    let modal = new bootstrap.Modal(document.getElementById('editDoctorModal'));
                                    modal.show();
                                }
                            </script> <!-- DELETE DOCTOR MODAL (improved, with red gradient and icon) -->
                            <div class="modal fade delete-modal" id="deleteDoctor{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                Confirm Delete
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <i class="bi bi-person-x-fill delete-icon"></i>
                                            <h4 class="mb-3">Are you sure?</h4>
                                            <p class="text-muted mb-0">
                                                You are about to delete doctor <strong>{{ $d->name }}</strong>.
                                            </p>
                                            <p class="text-muted">This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                                                <i class="bi bi-x-lg me-2"></i>No, Keep Doctor
                                            </button>
                                            <form action="{{ route('doctors.delete', $d->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="bi bi-trash me-2"></i>Yes, Delete Doctor
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- FOOTER with count and pagination -->
            <div class="card-footer bg-white border-0 gap-3 py-3" style="color:#7589a2; font-size:0.85rem;">
                <i class="bi bi-info-circle"></i> {{ $doctors->count() }} doctors available
                @if(method_exists($doctors, 'links'))
                <nav class="float-end" aria-label="Page navigation">
                    <ul class="pagination">
                        @if ($doctors->onFirstPage())
                        <li class="page-item disabled"><a class="page-link">Previous</a></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ $doctors->previousPageUrl() }}">Previous</a></li>
                        @endif

                        @foreach ($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $doctors->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        @if ($doctors->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $doctors->nextPageUrl() }}">Next</a></li>
                        @else
                        <li class="page-item disabled"><a class="page-link">Next</a></li>
                        @endif
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection