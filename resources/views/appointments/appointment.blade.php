<!-- Appointment list – Create Appointment / Today's overview -->
@extends('layout.master')
@section('content')

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600&display=swap"
        rel="stylesheet">

    <style>
        /* Base custom styles (reused from previous design) */
        body {
            background: #f1f5f9;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

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

        .table thead th {
            color: #4a5f7a;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom-width: 1.5px;
            border-bottom-color: #dfe7ef;
            padding: 1.1rem 0.8rem 0.9rem 0.8rem;
        }

        .table tbody td {
            padding: 1rem 0.8rem;
            vertical-align: middle;
            color: #1f2c41;
            border-bottom: 1px solid #edf2f7;
        }

        .table tbody tr:hover td {
            background-color: #f9fcff;
        }

        .phone-number {
            background: #f4f7fb;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-family: 'Inter', monospace;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        /* status badge */
        .status-badge {
            background: #fef6e0;
            color: #b7811e;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.25rem 0.9rem;
            border-radius: 30px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        /* filter chips (Pending, Complete, etc) */
        .filter-chip {
            background: white;
            border: 1px solid #cfddee;
            color: #234c7c;
            border-radius: 40px;
            padding: 0.4rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            cursor: default;
            transition: 0.1s;
        }

        .filter-chip.active {
            background: #1a5f9c;
            border-color: #1a5f9c;
            color: white;
        }

        .filter-chip i {
            margin-right: 0.3rem;
        }

        /* stats pill */
        .stat-item {
            background: #f4f7fd;
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #1f3a5e;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-number {
            background: white;
            border-radius: 40px;
            padding: 0.2rem 0.7rem;
            font-weight: 600;
            color: #0b2b4f;
        }

        /* small action buttons inside table */
        .action-btn-sm {
            background: transparent;
            border: 1px solid #d5e0ec;
            color: #2f5681;
            border-radius: 30px;
            padding: 0.2rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            white-space: nowrap;
            cursor: default;
        }

        .action-btn-sm i {
            font-size: 0.8rem;
        }

        .btn-outline-filter {
            border: 1px solid #cfddee;
            color: #234c7c;
            border-radius: 60px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
        }

        .btn-primary-custom {
            background: #1a5f9c;
            color: white;
            border: none;
            border-radius: 60px;
            padding: 0.6rem 1.4rem;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(21, 94, 158, 0.2);
        }

        .btn-danger-custom {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 60px;
            padding: 0.6rem 1.4rem;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.15);
        }

        a.email-link {
            color: #1f5f9e;
            text-decoration: none;
            border-bottom: 1px dashed #b3c9e0;
        }

        a.email-link:hover {
            border-bottom: 1px solid #1f5f9e;
        }

        .doctor-name {
            font-weight: 600;
            color: #0d2742;
            font-size: 1.1rem;
        }

        /* divider line */
        .section-divider {
            border-top: 2px dashed #dae2ec;
            margin: 1.5rem 0 1rem 0;
            opacity: 0.6;
        }

        /* Modal improvements */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            background: linear-gradient(135deg, #da0a0a 0%, #764ba2 100%);
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

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
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
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(245, 101, 101, 0.4);
        }

        @page {
            size: A4;
            margin: 0;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }

            body * {
                visibility: hidden;
            }

            #printReceiptContainer,
            #printReceiptContainer * {
                visibility: visible;
            }

            #printReceiptContainer {
                position: absolute;
                top: 0;
                left: 0;

                width: 230mm;
                height: 270mm;

                margin: 0 auto;
                padding: 0;

                background: #fff;
                overflow: hidden;

                page-break-inside: avoid;
            }

            .modal,
            .modal-backdrop,
            .modal-footer,
            .modal-header {
                display: none !important;
            }
        }
    </style>
    <main class="page-content">
        <div class="container-fluid px-0">
            <div class="card appointment-card">
                <!-- Header: Create Appointment (icon + title) -->
                <div class="card-header d-flex align-items-center flex-wrap gap-3">

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                            <i class="bi bi-calendar-plus-fill me-1"></i> Create Appointment
                        </button>
                        <a href="{{ route('reports.appointments.pdf', ['search' => request('search')]) }}" target="_blank" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                            <i class="bi bi-file-earmark-pdf"></i> Print Table (PDF)
                        </a>
                    </div>
                    <div class="d-flex gap-2 ms-auto">
                        <form id="searchForm" method="GET" action="{{ route('appointment.index') }}">
                            <div class="input-group input-group-sm">
                                <input class="form-control" type="text" name="search" id="patientSearch"
                                    placeholder="Search patient..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                                @if(request('search'))
                                    <a href="{{ route('appointment.index') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <!-- optional placeholder to keep header balanced, but we omit extra buttons -->
                </div>

                <!-- Main content area -->
                <div class="card-body p-4">
                    <!-- Subheading -->


                    <!-- Date row + Cancel button -->
                    <form action="{{ route('appointments.cancelToday', absolute: false) }}" method="POST" class="d-inline-flex align-items-center flex-wrap gap-2"
                        onsubmit="return confirm('Are you sure you want to cancel appointments for this date?');">
                        @csrf
                        <div class="input-group input-group-sm" style="width: auto;">
                            <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" class="form-control fw-semibold"
                                name="date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm shadow-sm d-flex align-items-center gap-1">
                            <i class="bi bi-x-circle"></i>
                            CANCEL APPOINTMENTS
                        </button>
                    </form>

                    <!-- Doctor & filter chips (Dr Sania Moiz + status filters) -->
                    <!-- <div class="d-flex flex-wrap align-items-center gap-4 mb-4"> -->

                    <!-- <div class="d-flex gap-2 flex-wrap">
                                                <span class="filter-chip active">Pending</span>
                                                <span class="filter-chip">Complete</span>
                                                <span class="filter-chip">Cancel</span>
                                                <span class="filter-chip">Admitted</span>
                                            </div>
                                        </div>  -->

                    <!-- Stats row: Today's Total, Pending, Completed, Admitted, Cancelled -->
                    <div class="d-flex flex-wrap gap-3 mb-4 mt-3">

                        <span class="stat-item bg-light border rounded-3 px-3 py-2 d-flex align-items-center gap-1">
                            <i class="bi bi-calendar-day text-primary"></i>
                            Today's Total
                            <span class="stat-number ms-2 badge bg-primary">{{ $totalToday }}</span>
                        </span>

                        <span class="stat-item bg-light border rounded-3 px-3 py-2 d-flex align-items-center gap-1">
                            <i class="bi bi-hourglass-split text-warning"></i>
                            Pending
                            <span class="stat-number ms-2 badge bg-warning text-dark">{{ $pending }}</span>
                        </span>

                        <span class="stat-item bg-light border rounded-3 px-3 py-2 d-flex align-items-center gap-1">
                            <i class="bi bi-check-circle text-success"></i>
                            Completed
                            <span class="stat-number ms-2 badge bg-success">{{ $completed }}</span>
                        </span>

                        <span class="stat-item bg-light border rounded-3 px-3 py-2 d-flex align-items-center gap-1">
                            <i class="bi bi-hospital text-info"></i>
                            Admitted
                            <span class="stat-number ms-2 badge bg-info text-dark">{{ $admitted }}</span>
                        </span>

                        <span class="stat-item bg-light border rounded-3 px-3 py-2 d-flex align-items-center gap-1">
                            <i class="bi bi-x-circle text-danger"></i>
                            Cancelled
                            <span class="stat-number ms-2 badge bg-danger">{{ $cancelled }}</span>
                        </span>

                    </div>
                    <!-- optional divider (as in image the line after stats) -->
                    <div class="section-divider"></div>

                    <!-- Appointments model -->
                    <!-- Create Appointment Modal -->
                    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <!-- Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-calendar-plus-fill me-2"></i>
                                        Add New Appointment
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Body -->
                                <div class="modal-body">
                                    <form action="{{ route('appointment.store') }}" method="POST" id="addAppointmentForm">
                                        @csrf

                                        <div class="row g-4">

                                            <div class="col-md-6">
                                                <label class="form-label"><i class="bi bi-person me-2"></i>Patient
                                                    Name</label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Enter patient name" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><i class="bi bi-telephone me-2"></i>Phone
                                                    Number</label>
                                                <input type="text" name="phone" class="form-control"
                                                    placeholder="+92 34 567 890" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                                <select name="doctor_id" id="doctor_id" class="form-select" required>
                                                    <option value="" disabled selected>Select a doctor</option>
                                                    @foreach($doctors as $doctor)
                                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- NEW ROW: Gender + Age -->
                                            <div class="row g-4 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label"><i
                                                            class="bi bi-gender-ambiguous me-2"></i>Gender</label>
                                                    <div class="d-flex gap-4 mt-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="gender"
                                                                id="genderMale" value="Male" required>
                                                            <label class="form-check-label" for="genderMale">
                                                                <i class="bi bi-gender-male"></i> Male
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="gender"
                                                                id="genderFemale" value="Female" required>
                                                            <label class="form-check-label" for="genderFemale">
                                                                <i class="bi bi-gender-female"></i> Female
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="gender"
                                                                id="genderOther" value="Other">
                                                            <label class="form-check-label" for="genderOther">
                                                                <i class="bi bi-gender-trans"></i> Other
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label"><i
                                                            class="bi bi-calendar-heart me-2"></i>Age</label>
                                                    <input type="number" name="age" class="form-control"
                                                        placeholder="e.g., 25" min="0" max="150" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><i class="bi bi-clock me-2"></i>Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Completed">Completed</option>
                                                    <option value="Cancelled">Cancelled</option>
                                                    <option value="Admitted">Admitted</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><i class="bi bi-telephone me-2"></i>appointment
                                                    time</label>
                                                <input type="time" name="time" class="form-control" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label"><i class="bi bi-calendar3 me-2"></i>Appointment
                                                    Date</label>
                                                <input type="date" name="appointment_date" class="form-control" required>
                                            </div>

                                        </div> <!-- end row g-4 -->

                                    </form>
                                </div>

                                <!-- Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg me-2"></i>Cancel
                                    </button>
                                    <button type="submit" form="addAppointmentForm" class="btn btn-primary text-white">
                                        <i class="bi bi-check-lg me-2"></i>Save Appointment
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Appointments table -->
                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle mb-0">

                            <thead>
                                <tr>
                                    <th>TK#</th>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>gender</th>
                                    <th>age</th>
                                    <th>Visit Time</th>
                                    <th>Phone</th>

                                    <th>Doctor</th>
                                    <th>Status</th>
                                    <th>appointment time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($appointments as $a)

                                    <tr>

                                        <td>
                                            <span class="phone-number">{{ $loop->iteration }}</span>
                                        </td>

                                        <td><span class="phone-number"> {{ $a->id }} </span></td>

                                        <td>
                                            <span class="  phone-number ">
                                                {{ $a->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="  phone-number ">
                                                {{ $a->gender }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="  phone-number ">
                                                {{ $a->age }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class=" phone-number"> {{ $a->created_at->format('h:i:s A') }}</span>
                                        </td>

                                        <td>
                                            <span class="phone-number">
                                                {{ $a->phone }}
                                            </span>
                                        </td>
                                        <td><span class="phone-number">{{ $a->doctor->name ?? '-' }}</span></td>
                                        <td>

                                            @if($a->status == 'Pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i> Pending
                                                </span>

                                            @elseif($a->status == 'Completed')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Completed
                                                </span>

                                            @elseif($a->status == 'Admitted')
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-x-circle"></i> Admitted
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> cancelled
                                                </span>
                                            @endif

                                        </td>
                                        <td><span class="phone-number">
                                                {{ $a->time ? \Carbon\Carbon::parse($a->time)->format('h:i:s A') : '-' }}
                                            </span></td>


                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <!-- Print Receipt button (outline, icon only) -->
                                                <a href="{{ route('pdf.appointment', $a->id) }}" id="printLink" target="_blank"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-receipt"></i>
                                                </a>

                                                <!-- Actions dropdown -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <!-- Edit -->
                                                        <li>
                                                            <button class="dropdown-item btn btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#editAppointmentModal{{ $a->id }}">
                                                                <i class="bi bi-pencil me-2"></i> Edit
                                                            </button>
                                                        </li>
                                                        <!-- Delete -->
                                                        <li>
                                                            <button class="dropdown-item btn btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#deleteAppointmentModal{{ $a->id }}">
                                                                <i class="bi bi-trash me-2"></i> Delete
                                                            </button>
                                                        </li>
                                                        <!-- View -->
                                                        <li>
                                                            <button type="button"
                                                                class="dropdown-item btn btn-sm view-appointment"
                                                                data-url="{{ route('appointment.show', $a->id) }}">
                                                                <i class="bi bi-eye me-2"></i> View
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>


                                    <!-- EDIT MODAL -->

                                    <div class="modal fade" id="editAppointmentModal{{ $a->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Appointment - {{ $a->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <form action="{{ route('appointment.update', $a->id) }}" method="POST"
                                                        id="editAppointmentForm{{ $a->id }}">

                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row g-3">

                                                            <div class="col-md-6">
                                                                <label class="form-label">Name</label>
                                                                <input type="text" name="name" value="{{ $a->name }}"
                                                                    class="form-control" required>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Phone</label>
                                                                <input type="text" name="phone" value="{{ $a->phone }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i
                                                                        class="bi bi-gender-ambiguous me-2"></i>Gender</label>
                                                                <div class="d-flex gap-4 mt-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="gender" id="genderMale{{ $a->id }}"
                                                                            value="Male" {{ $a->gender == 'Male' ? 'checked' : '' }} required>
                                                                        <label class="form-check-label"
                                                                            for="genderMale{{ $a->id }}">
                                                                            <i class="bi bi-gender-male"></i> Male
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="gender" id="genderFemale{{ $a->id }}"
                                                                            value="Female" {{ $a->gender == 'Female' ? 'checked' : '' }} required>
                                                                        <label class="form-check-label"
                                                                            for="genderFemale{{ $a->id }}">
                                                                            <i class="bi bi-gender-female"></i> Female
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="gender" id="genderOther{{ $a->id }}"
                                                                            value="Other" {{ $a->gender == 'Other' ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="genderOther{{ $a->id }}">
                                                                            <i class="bi bi-gender-trans"></i> Other
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">age</label>
                                                                <input type="text" name="age" value="{{ $a->age }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                                                <select name="doctor_id" id="doctor_id" class="form-select"
                                                                    required>
                                                                    <option value="" disabled selected>Select a doctor</option>
                                                                    @foreach($doctors as $doctor)
                                                                        <option value="{{ $doctor->id }}">{{ $doctor->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Status</label>

                                                                <select name="status" class="form-control">

                                                                    <option value="Pending" {{ $a->status == 'Pending' ? 'selected' : '' }}>
                                                                        Pending
                                                                    </option>

                                                                    <option value="Completed" {{ $a->status == 'Completed' ? 'selected' : '' }}>
                                                                        Completed
                                                                    </option>

                                                                    <option value="Cancelled" {{ $a->status == 'Cancelled' ? 'selected' : '' }}>
                                                                        Cancelled
                                                                    </option>
                                                                    <option value="Admitted" {{ $a->status == 'Admitted' ? 'selected' : '' }}>
                                                                        Admitted
                                                                    </option>

                                                                </select>


                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Phone</label>
                                                                <input type="text" name="time" value="{{ $a->time }}"
                                                                    class="form-control" required>
                                                            </div>


                                                        </div>

                                                    </form>

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <button type="submit" form="editAppointmentForm{{ $a->id }}"
                                                        class="btn btn-primary">
                                                        Update
                                                    </button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <!-- DELETE MODAL -->

                                    <div class="modal fade" id="deleteAppointmentModal{{ $a->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                        Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body text-center">

                                                    <i class="bi bi-calendar-x text-danger fs-1"></i>

                                                    <h5 class="mt-3">Are you sure?</h5>

                                                    <p class="text-muted">
                                                        Delete appointment for
                                                        <strong>{{ $a->name }}</strong>?
                                                    </p>

                                                </div>

                                                <div class="modal-footer justify-content-center">

                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>

                                                    <form action="{{ route('appointment.destroy', $a->id) }}" method="POST">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                            Delete
                                                        </button>

                                                    </form>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                @endforeach


                            </tbody>

                        </table>
                        <div class="card-footer mt-4">
                            <nav class="float-end" aria-label="Page navigation">
                                {{ $appointments->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                        <!-- View Appointment Details Modal -->
                        <div class="modal fade" id="viewAppointmentModal" tabindex="-1"
                            aria-labelledby="viewAppointmentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="viewAppointmentModalLabel">
                                            <i class="bi bi-calendar-check"></i> Appointment Details
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- <div id="printableArea">
                                            <!-- Your appointment details will be injected here via AJAX -->
                                        </div> --}}

                                        <div id="viewModalContent">
                                            <div class="text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="printAppointmentBtn">
                                            <i class="bi bi-printer"></i> Print token
                                        </button>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div id="printReceiptContainer"></div>
                    </div>
                    <script>
                        (function () {
                            setTimeout(function () {
                                try {
                                    if (document.readyState === 'loading') {
                                        document.addEventListener('DOMContentLoaded', init);
                                    } else {
                                        init();
                                    }
                                } catch (e) {
                                    console.error('Print script init error:', e);
                                }
                            }, 300);

                            function init() {
                                let currentAppointment = null;
                                let printContainer = document.getElementById('printReceiptContainer');

                                // Create container if missing
                                if (!printContainer) {
                                    printContainer = document.createElement('div');
                                    printContainer.id = 'printReceiptContainer';
                                    printContainer.style.display = 'none';
                                    document.body.appendChild(printContainer);
                                }

                                // Modal elements
                                const modalElement = document.getElementById('viewAppointmentModal');
                                if (!modalElement) {
                                    console.error('Modal #viewAppointmentModal not found');
                                    return;
                                }
                                if (typeof bootstrap === 'undefined') {
                                    console.error('Bootstrap JS not loaded');
                                    return;
                                }

                                let modal;
                                try {
                                    modal = new bootstrap.Modal(modalElement);
                                } catch (e) {
                                    console.error('Failed to init modal:', e);
                                    return;
                                }

                                const modalBodyContent = document.getElementById('viewModalContent');
                                if (!modalBodyContent) {
                                    console.error('#viewModalContent not found');
                                    return;
                                }

                                // View button event delegation (works for all .view-appointment)
                                document.body.addEventListener('click', function (e) {
                                    const button = e.target.closest('.view-appointment');
                                    if (!button) return;
                                    e.preventDefault();

                                    const url = button.getAttribute('data-url');
                                    if (!url) {
                                        console.error('No data-url on button');
                                        return;
                                    }

                                    modalBodyContent.innerHTML = `
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                `;
                                    modal.show();

                                    fetch(url, {
                                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                    })
                                        .then(response => {
                                            if (!response.ok) throw new Error('HTTP ' + response.status);
                                            return response.json();
                                        })
                                        .then(data => {
                                            currentAppointment = {
                                                id: data.id,
                                                patient_name: data.name || 'N/A',
                                                date: data.appointment_date || 'N/A',
                                                time: data.appointment_time || (data.created_at ? new Date(data.created_at).toLocaleTimeString() : 'N/A'),
                                                doctor: data.doctor ? data.doctor.name : 'N/A',
                                                notes: data.notes || ''
                                            };

                                            modalBodyContent.innerHTML = `
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>📋 Appointment ID:</strong> ${data.id}</p>
                                                <p><strong>👤 Patient Name:</strong> ${data.name || 'N/A'}</p>
                                                <p><strong>👤 Patient Gender:</strong> ${data.gender || 'N/A'}</p>
                                                <p><strong>👤 Patient Age:</strong> ${data.age || 'N/A'}</p>
                                                <p><strong>📅 Appointment Date:</strong> ${data.appointment_date || 'N/A'}</p>
                                                <p><strong>⏰ Created At:</strong> ${new Date(data.created_at).toLocaleString()}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>👨‍⚕️ Doctor:</strong> ${data.doctor ? data.doctor.name : 'N/A'}</p>
                                                <p><strong>📞 Phone:</strong> ${data.phone || 'N/A'}</p>
                                                <p><strong>📝 Status:</strong> 
                                                    <span class="badge ${data.status === 'Completed' ? 'bg-success' : (data.status === 'Cancelled' ? 'bg-danger' : 'bg-warning')}">
                                                        ${data.status || 'Pending'}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mt-3 ms-8">
                                            <h1>🎫 Token #: ${data.id}</h1>
                                        </div>
                                    `;
                                        })
                                        .catch(error => {
                                            modalBodyContent.innerHTML = `<div class="alert alert-danger">Failed to load details.<br><small>${error.message}</small></div>`;
                                            console.error('Fetch error:', error);
                                        });
                                });

                                // Print button listener
                                const printBtn = document.getElementById('printAppointmentBtn');
                                if (!printBtn) {
                                    console.error('Print button not found');
                                    return;
                                }

                                printBtn.addEventListener('click', function () {
                                    if (!currentAppointment) {
                                        alert('No appointment loaded. Please view an appointment first.');
                                        return;
                                    }

                                    // ----- YOUR RECEIPT HTML (paste your full design here) -----
                                    const receiptHTML = `<div style="210mm; min-height:290mm; background:#fff; position:absolute; font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; margin:0 auto; overflow:hidden;">
                      <!-- Top red banner -->
                      <div style="background:#c8102e; padding:8px 24px; text-align:center;">
                        <span style="color:#fff; font-size:22px; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; direction:rtl;">
                          ذلیخا ڈینٹل کلینک
                        </span>
                      </div>
                      <!-- Bilingual Header -->
                      <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:16px 28px 10px; border-bottom:2px solid #c8102e;">
                        <div style="flex:1;">
                          <div style="color:#555; font-size:11px; font-weight:600;">Professor AL - Haj</div>
                          <div style="color:#c8102e; font-size:20px; font-weight:800; line-height:1.1;">Dr. Shazad Ali</div>
                          <div style="color:#1a3a6b; font-size:12px; font-weight:700; letter-spacing:1.5px; margin-bottom:8px;">DENTAL SURGEON</div>
                          <div style="color:#444; font-size:9.5px; line-height:1.7;">
                            B.Sc. B.D.S (Pb), R.D.S (Pb), F.M.D.C (Islamabad)<br>

                          </div>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:center; padding:0 16px;">
                          <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="30" stroke="#c8102e" stroke-width="2.5" fill="none"/>
                            <path d="M40 12 C28 12,20 22,20 32 C20 40,22 46,24 52 C26 58,28 66,33 66 C36 66,38 62,40 56 C42 62,44 66,47 66 C52 66,54 58,56 52 C58 46,60 40,60 32 C60 22,52 12,40 12 Z" fill="#1a3a6b" opacity="0.85"/>
                            <path d="M34 22 C34 22,32 28,36 30 C40 32,44 30,42 22" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                            <text x="40" y="80" text-anchor="middle" font-size="6" fill="#1a3a6b" font-weight="600">DENTAL SURGEON</text>
                          </svg>
                        </div>
                        <div style="flex:1; text-align:left; direction:rtl; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif;">
                          <div style="color:#1a3a6b;  font-size:15px; font-weight:800; line-height:1.5;"> ڈاکٹرشہزادعلی</div>
                          <div style="color:#c8102e; font-size:13px; margin-bottom:6px;">ڈینٹل سرجن</div>
                          <div style="color:#444; font-size:8px; line-height:2.0;">
                            بی ایس سی، بی ڈی ایس (پنجاب)<br>

                          </div>
                        </div>
                      </div>
                      <div style="background:#1a3a6b; color:#fff; text-align:center; padding:8px; font-size:13px; letter-spacing:2px; font-weight:700;">
                        APPOINTMENT RECEIPT — اپوائنٹمنٹ رسید
                      </div>
                      <div style="display:flex; justify-content:center; padding:24px 28px 16px;">
                        <div style="background:#1a3a6b; color:#fff; border-radius:12px; padding:12px 36px; text-align:center; min-width:140px;">
                          <div style="font-size:11px; letter-spacing:1px; opacity:0.8; margin-bottom:2px;">TOKEN NUMBER</div>
                          <div style="font-size:52px; font-weight:900; line-height:1; color:#ffcc00;">${currentAppointment.id}</div>
                          <div style="font-size:12px; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; margin-top:4px; opacity:0.9;">ٹوکن نمبر</div>
                        </div>
                      </div>
                      <div style="padding:0 28px 20px;">
                        <table style="width:100%; border-collapse:collapse; font-size:12px;">
                          <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 10px; font-weight:700; color:#1a3a6b; font-size:11px; width:160px; white-space:nowrap;">
                              Patient Name<br>
                              <span style="font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; color:#c8102e;">مریض کا نام</span>
                            </td>
                            <td style="padding:8px 10px; font-size:13px; color:#333;">${currentAppointment.patient_name}</td>
                          </tr>
                          <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 10px; font-weight:700; color:#1a3a6b; font-size:11px; white-space:nowrap;">
                              Date<br>
                              <span style="font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; color:#c8102e;">تاریخ</span>
                            </td>
                            <td style="padding:8px 10px; font-size:13px; color:#333;">${currentAppointment.date}</td>
                          </tr>
                          <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 10px; font-weight:700; color:#1a3a6b; font-size:11px; white-space:nowrap;">
                              Time<br>
                              <span style="font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; color:#c8102e;">وقت</span>
                            </td>
                            <td style="padding:8px 10px; font-size:13px; color:#333;">${currentAppointment.time}</td>
                          </tr>
                          <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px 10px; font-weight:700; color:#1a3a6b; font-size:11px; white-space:nowrap;">
                              Doctor<br>
                              <span style="font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; color:#c8102e;">ڈاکٹر</span>
                            </td>
                            <td style="padding:8px 10px; font-size:13px; color:#333;">${currentAppointment.doctor}</td>
                          </tr>
                        </table>
                      </div>
                      <div style="border-top:2px dashed #ccc; margin:0 28px 20px;"></div>
                      <div style="text-align:center; padding:0px 48px 16px;">
                        <svg width="120" height="110" viewBox="0 0 140 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M70 22 C56 22,46 34,46 46 C46 56,48 64,51 72 C54 80,56 90,62 90 C66 90,68 86,70 80 C72 86,74 90,78 90 C84 90,86 80,89 72 C92 64,94 56,94 46 C94 34,84 22,70 22 Z" fill="#1a3a6b"/>
                          <path d="M62 34 C62 34,60 42,65 44 C70 46,74 44,72 34" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>
                          <path id="cp" d="M 70,12 A 58,58 0 1,1 69.999,12" fill="none"/>
                          <text font-size="7.5" fill="#1a3a6b" font-weight="600" letter-spacing="1.5">
                            <textPath href="#cp" startOffset="5%">Dental Surgery &amp; Orthodontic Center</textPath>
                          </text>
                        </svg>
                        <div style="color:#1a3a6b; font-size:10px; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; direction:rtl; margin-top:4px;">
                          ڈینٹل سرجری اینڈ آرتھوڈونٹک سینٹر
                        </div>
                      </div>
                      <div style="text-align:center; color:#555; font-size:11px; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; direction:rtl; padding:0 28px 16px;">
                        آپ کا شکریہ — Thank you for your visit
                      </div>
                      <div style="position:absolute; bottom:0; left:0; right:0; display:flex; justify-content:space-between; align-items:center; border-top:3px solid #c8102e; padding:10px 28px; background:#fff;">
                        <div>
                          <div style="font-size:9px; font-weight:700; color:#555; letter-spacing:1px;">TIMING</div>
                          <div style="font-size:15px; font-weight:900; color:#c8102e;">5.30 PM TO 11.00PM</div>
                        </div>
                        <div style="text-align:right; direction:rtl;">
                          <div style="font-size:13px; font-family:'Noto Nastaliq Urdu','Traditional Arabic',serif; color:#1a3a6b; font-weight:700;">ڈینٹل سرجری اینڈ آرتھوڈونٹک سینٹر</div>
                          <div style="font-size:11px; color:#444; margin-top:2px;">Mob: 0333-8147974</div>
                        </div>
                      </div>
                    </div>`;

                                    // Inject into the hidden container and print
                                    printContainer.innerHTML = receiptHTML;
                                    printContainer.style.display = 'block';
                                    window.print();

                                    // Clean up after printing
                                    setTimeout(() => {
                                        printContainer.style.display = 'none';
                                        printContainer.innerHTML = '';
                                    }, 500);
                                });
                            }
                        })();
                    </script>

                    <!-- subtle extra info (not in image but keeps card footer style) -->
                    <div class="d-flex gap-3 mt-4" style="color:#7589a2; font-size:0.85rem;">
                        <i class="bi bi-info-circle"></i> 1 appointment today · last updated 20/05/2015
                    </div>
                    <!-- Toast Container -->
                    <div class="position-fixed top-0 end-0 p-3" style="z-index:1080">
                        @php
                            $toastMessage = '';
                            $toastIcon = '';
                            $textColor = '';
                        @endphp

                        @if(session('success') || session('update') || session('delete'))
                            @php
                                if (session('success')) {
                                    $toastMessage = session('success');
                                    $toastIcon = 'bi-check-circle-fill text-success';
                                    $textColor = 'text-success';
                                } elseif (session('update')) {
                                    $toastMessage = session('update');
                                    $toastIcon = 'bi-pencil-square text-primary';
                                    $textColor = 'text-primary';
                                } elseif (session('delete')) {
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
                            document.addEventListener("DOMContentLoaded", function () {
                                const toastEl = document.getElementById('actionToast');
                                if (toastEl) {
                                    const toast = new bootstrap.Toast(toastEl, { delay: 2000, autohide: true });
                                    toast.show();
                                }
                            });
                        </script>

                    @endif
                </div> <!-- end card-body -->
            </div> <!-- end appointment-card -->
        </div> <!-- end container -->
    </main>
    <!-- (no extra JS needed) -->
@endsection