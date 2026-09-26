@extends('layout.master')
@section('content')

<main class="page-content">
    <!-- Breadcrumb & Top Bar -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Patient Registry</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('welcome') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Patients & Medical History</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('reports.patients.pdf', ['search' => request('search')]) }}" target="_blank" class="btn btn-outline-danger btn-sm shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-file-earmark-pdf"></i> Print / Export Directory (PDF)
            </a>
            <button type="button" class="btn btn-primary btn-sm shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                <i class="bi bi-person-plus-fill"></i> Add New Patient
            </button>
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary font-13 fw-semibold text-uppercase">Total Registered</p>
                            <h4 class="my-1 text-primary">{{ number_format($totalPatients ?? 0) }}</h4>
                            <p class="mb-0 font-12 text-muted">Active patient records</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-purple text-white ms-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary font-13 fw-semibold text-uppercase">Outstanding Dues</p>
                            <h4 class="my-1 text-danger">Rs. {{ number_format($totalDue ?? 0, 2) }}</h4>
                            <p class="mb-0 font-12 text-danger">Uncollected hospital balances</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-danger text-white ms-auto">
                            <i class="bi bi-credit-card-2-front"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary font-13 fw-semibold text-uppercase">Patient Wallet Credit</p>
                            <h4 class="my-1 text-success">Rs. {{ number_format($totalWallet ?? 0, 2) }}</h4>
                            <p class="mb-0 font-12 text-success">Advance payments in wallet</p>
                        </div>
                        <div class="widget-icon-large bg-gradient-success text-white ms-auto">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert / Toast Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('update'))
        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('update') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('delete'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-trash-fill me-2"></i> {{ session('delete') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Patients Card & Table -->
    <div class="card radius-10 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <div class="row align-items-center g-2">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-journal-medical text-primary fs-4"></i> Patient Directory & Clinical History
                    </h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('patients.index') }}" class="d-flex gap-2 justify-content-md-end">
                        <div class="input-group input-group-sm" style="max-width: 320px;">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, phone or CNIC..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary" title="Clear Filter"><i class="bi bi-x"></i></a>
                            @endif
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary text-uppercase font-11">
                        <tr>
                            <th class="ps-3 py-3">M.R #</th>
                            <th>Patient Name</th>
                            <th>Age</th>
                            <th>Phone Number</th>
                            <th>CNIC / Identity</th>
                            <th>Due Balance</th>
                            <th>Wallet Credit</th>
                            <th>Registered</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td class="ps-3 fw-bold text-secondary">
                                    #{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-semibold text-dark">{{ $patient->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $patient->age ? $patient->age . ' yrs' : '—' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border font-12 fw-normal px-2 py-1">
                                        <i class="bi bi-telephone text-secondary me-1"></i>{{ $patient->phone }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-secondary font-12">{{ $patient->cnic ?: 'Not Provided' }}</span>
                                </td>
                                <td>
                                    @if($patient->due_amount > 0)
                                        <span class="badge bg-light-danger text-danger font-12 px-2 py-1">
                                            Rs. {{ number_format($patient->due_amount, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted font-12">Rs. 0.00</span>
                                    @endif
                                </td>
                                <td>
                                    @if($patient->wallet_amount > 0)
                                        <span class="badge bg-light-success text-success font-12 px-2 py-1">
                                            Rs. {{ number_format($patient->wallet_amount, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted font-12">Rs. 0.00</span>
                                    @endif
                                </td>
                                <td class="font-12 text-secondary">
                                    {{ $patient->created_at ? $patient->created_at->format('d M Y') : '—' }}
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <!-- View History Modal Trigger -->
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewHistoryModal{{ $patient->id }}" title="View Clinical History & Visits">
                                            <i class="bi bi-clock-history"></i>
                                        </button>
                                        <!-- Print Individual PDF Statement -->
                                        <a href="{{ route('reports.patient.profile.pdf', $patient->id) }}" target="_blank" class="btn btn-outline-danger" title="Download Medical Record PDF">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <!-- Edit Modal Trigger -->
                                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}" title="Edit Details">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <!-- Delete Modal Trigger -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePatientModal{{ $patient->id }}" title="Delete Record">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- View Medical History Modal -->
                            <div class="modal fade" id="viewHistoryModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title d-flex align-items-center gap-2">
                                                <i class="bi bi-file-earmark-medical"></i> Patient Clinical History: {{ $patient->name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <!-- Bio Snapshot -->
                                            <div class="p-3 bg-light rounded-3 mb-4">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <small class="text-secondary d-block font-11 text-uppercase">MRN Number</small>
                                                        <span class="fw-bold">#{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small class="text-secondary d-block font-11 text-uppercase">Age & Gender</small>
                                                        <span class="fw-semibold">{{ $patient->age ? $patient->age . ' Years' : 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small class="text-secondary d-block font-11 text-uppercase">Contact</small>
                                                        <span class="fw-semibold">{{ $patient->phone }}</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <small class="text-secondary d-block font-11 text-uppercase">CNIC</small>
                                                        <span class="fw-semibold">{{ $patient->cnic ?: 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="row g-3 mt-1 pt-2 border-top">
                                                    <div class="col-md-6">
                                                        <small class="text-secondary d-block font-11 text-uppercase">Outstanding Hospital Due</small>
                                                        <span class="fw-bold text-danger font-14">Rs. {{ number_format($patient->due_amount, 2) }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-secondary d-block font-11 text-uppercase">Wallet Credit Balance</small>
                                                        <span class="fw-bold text-success font-14">Rs. {{ number_format($patient->wallet_amount, 2) }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                                                <i class="bi bi-calendar2-check text-primary"></i> Hospital Consultations & Visits
                                            </h6>

                                            @php
                                                $patientVisits = \App\Models\Appointment::query()
                                                    ->where('phone', $patient->phone)
                                                    ->orWhere('name', 'like', "%{$patient->name}%")
                                                    ->with('doctor')
                                                    ->latest()
                                                    ->get();
                                            @endphp

                                            @if($patientVisits->isEmpty())
                                                <div class="text-center py-4 bg-light rounded-3">
                                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                                    <p class="mb-0 text-secondary mt-2">No appointment visit records linked to this phone number yet.</p>
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm align-middle font-13">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Doctor</th>
                                                                <th>Status</th>
                                                                <th>Diagnosis & Notes</th>
                                                                <th>Prescription</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($patientVisits as $visit)
                                                                <tr>
                                                                    <td>{{ $visit->created_at ? $visit->created_at->format('d M Y') : '—' }} ({{ $visit->time ?? '-' }})</td>
                                                                    <td class="fw-semibold">{{ $visit->doctor?->name ?? 'Dr. Unassigned' }}</td>
                                                                    <td>
                                                                        <span class="badge {{ $visit->status === 'Completed' ? 'bg-success' : ($visit->status === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                                                            {{ $visit->status }}
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ $visit->diagnosis ?: ($visit->notes ?: 'General Checkup') }}</td>
                                                                    <td>{{ $visit->medicine_suggestions ?: 'No notes recorded' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <a href="{{ route('reports.patient.profile.pdf', $patient->id) }}" target="_blank" class="btn btn-danger btn-sm">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Print Full Statement (PDF)
                                            </a>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Patient Modal -->
                            <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-pencil-square me-2"></i> Edit Patient Record - {{ $patient->name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label font-13 fw-semibold">Full Patient Name *</label>
                                                        <input type="text" name="name" value="{{ $patient->name }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label font-13 fw-semibold">Age (Years) *</label>
                                                        <input type="number" name="age" value="{{ $patient->age }}" min="0" max="150" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label font-13 fw-semibold">Phone Number *</label>
                                                        <input type="text" name="phone" value="{{ $patient->phone }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label font-13 fw-semibold">CNIC / ID Card</label>
                                                        <input type="text" name="cnic" value="{{ $patient->cnic }}" class="form-control" placeholder="35201-XXXXXXX-X">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label font-13 fw-semibold">Outstanding Due Balance (Rs.) *</label>
                                                        <input type="number" name="due_amount" value="{{ $patient->due_amount }}" step="0.01" min="0" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label font-13 fw-semibold">Wallet Credit Balance (Rs.) *</label>
                                                        <input type="number" name="wallet_amount" value="{{ $patient->wallet_amount }}" step="0.01" min="0" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm px-3">Update Record</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Patient Modal -->
                            <div class="modal fade" id="deletePatientModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body text-center p-4">
                                            <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                                            <h6 class="mt-3 fw-bold">Delete Patient Record?</h6>
                                            <p class="text-muted font-13">Are you sure you want to delete <strong>{{ $patient->name }}</strong>? This action cannot be undone.</p>
                                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="d-flex justify-content-center gap-2 mt-3">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                    No patient records found matching your query.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($patients->hasPages())
                <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="text-secondary font-12">
                        Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} entries
                    </span>
                    {{ $patients->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add New Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus-fill me-2"></i> Register New Patient
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('patients.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label font-13 fw-semibold">Full Patient Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Muhammad Usman" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-13 fw-semibold">Age (Years) *</label>
                                <input type="number" name="age" class="form-control" placeholder="e.g. 35" min="0" max="150" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-13 fw-semibold">Phone Number *</label>
                                <input type="text" name="phone" class="form-control" placeholder="e.g. 0300 1234567" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-13 fw-semibold">CNIC / ID Card</label>
                                <input type="text" name="cnic" class="form-control" placeholder="e.g. 35201-1234567-1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-13 fw-semibold">Initial Due Amount (Rs.)</label>
                                <input type="number" name="due_amount" class="form-control" value="0" step="0.01" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-13 fw-semibold">Initial Wallet Credit (Rs.)</label>
                                <input type="number" name="wallet_amount" class="form-control" value="0" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save Patient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection