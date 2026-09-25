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
        /* Minimal custom overrides (same as staff blade) */
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

        .name {
            background: #e1ecfe;
            color: #194a7a;
            font-weight: 500;
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .phone-pill {
            background: #f4f7fb;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-family: 'Inter', monospace;
            font-size: 0.9rem;
            display: inline-block;
            white-space: nowrap;
        }

        .badge-cnic {
            background: #ecf1f6;
            color: #1a3c5e;
            padding: 0.2rem 0.8rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .amount {
            font-weight: 500;
            color: #0b3b4e;
        }

        .action-icon {
            color: #577a9e;
            font-size: 1.3rem;
            padding: 0.3rem;
            border-radius: 8px;
            transition: 0.15s;
            cursor: pointer;
            display: inline-block;
        }

        .action-icon:hover {
            background: #e3ecf5;
            color: #1b4c7c;
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

        /* Modal improvements */
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
    </style>

    <main class="page-content">
        <div class="container-fluid px-0">
            <div class="card appointment-card">
                <!-- Header: Create Appointment -->
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <h2 class="h3 mb-0 fw-semibold d-flex align-items-center gap-3" style="color:#0b1b2f;">
                        <i class="bi bi-calendar-plus-fill fs-1 text-primary"
                            style="background:#eef4fe; padding:0.5rem; border-radius:14px;"></i>
                        patients history
                    </h2>
                    <div class="d-flex gap-2">
                        <div class="d-flex gap-2">
                            <form id="searchForm" method="GET" action="{{ route('patients.index') }}">
                                <input class="form-control form-control-sm" type="text" name="search" id="patientSearch"
                                    placeholder="Search patient..." value="{{ request('search') }}"
                                    onkeyup="document.getElementById('searchForm').submit();">
                            </form>
                        </div>

                        <!-- New Patient Button -->
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#patientModal">
                            <i class="bi bi-plus-circle me-2"></i> New Patient
                        </button>
                    </div>
                </div>

                <!-- New Patient Modal -->
                <div class="modal fade" id="patientModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-person-plus-fill me-2"></i>
                                    Add New Patient
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('patients.store') }}" method="POST" id="addPatientForm">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-person me-2"></i>Full Name <span style="color: red">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Enter full name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-calendar me-2"></i>Age</label>
                                            <input type="number" name="age" class="form-control" placeholder="Age" >
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-telephone me-2"></i>Phone
                                                Number*</label>
                                            <input type="text" name="phone" class="form-control"
                                                placeholder="+92 34 567 890" >
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-credit-card me-2"></i>CNIC</label>
                                            <input type="text" name="cnic" class="form-control"
                                                placeholder="XXXXX-XXXXXXX-X">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-cash me-2"></i>Due Amount</label>
                                            <input type="number" name="due_amount" class="form-control" placeholder="0"
                                                value="0" step="0.01">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-wallet2 me-2"></i>Wallet
                                                Balance</label>
                                            <input type="number" name="wallet_amount" class="form-control" placeholder="0"
                                                value="0" step="0.01">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg me-2"></i>Cancel
                                </button>
                                <button type="submit" form="addPatientForm" class="btn btn-save text-white">
                                    <i class="bi bi-check-lg me-2"></i>Save Patient
                                </button>
                            </div>
                        </div>
                    </div>
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

                <!-- Patients Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>M.R#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Phone</th>
                                    <th>Patient CNIC</th>
                                    <th>Due Amount</th>
                                    <th>Wallet Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $patient->id }}</span></td>
                                        <td><span class="name">{{ $patient->name }}</span></td>
                                        <td>{{ $patient->age }}</td>
                                        <td><span class="phone-pill">{{ $patient->phone }}</span></td>
                                        <td><span class="badge-cnic">{{ $patient->cnic ?: '—' }}</span></td>
                                        <td class="amount">Rs. {{ number_format($patient->due_amount, 2) }}</td>
                                        <td class="amount">Rs. {{ number_format($patient->wallet_amount, 2) }}</td>
                                        <td>
                                            <a href="#" class="action-icon" data-bs-toggle="modal"
                                                data-bs-target="#editPatientModal{{ $patient->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#" class="action-icon" data-bs-toggle="modal"
                                                data-bs-target="#deletePatientModal{{ $patient->id }}">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Patient Modal -->
                                    <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-pencil-square me-2"></i>
                                                        Edit Patient - {{ $patient->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('patients.update', $patient->id) }}" method="POST"
                                                        id="editPatientForm{{ $patient->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row g-4">
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i class="bi bi-person me-2"></i>Full
                                                                    Name*</label>
                                                                <input type="text" name="name" value="{{ $patient->name }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i
                                                                        class="bi bi-calendar me-2"></i>Age*</label>
                                                                <input type="number" name="age" value="{{ $patient->age }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i
                                                                        class="bi bi-telephone me-2"></i>Phone Number*</label>
                                                                <input type="text" name="phone" value="{{ $patient->phone }}"
                                                                    class="form-control" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i
                                                                        class="bi bi-credit-card me-2"></i>CNIC</label>
                                                                <input type="text" name="cnic" value="{{ $patient->cnic }}"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i class="bi bi-cash me-2"></i>Due
                                                                    Amount</label>
                                                                <input type="number" name="due_amount"
                                                                    value="{{ $patient->due_amount }}" class="form-control"
                                                                    step="0.01">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i
                                                                        class="bi bi-wallet2 me-2"></i>Wallet Balance</label>
                                                                <input type="number" name="wallet_amount"
                                                                    value="{{ $patient->wallet_amount }}" class="form-control"
                                                                    step="0.01">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-lg me-2"></i>Cancel
                                                    </button>
                                                    <button type="submit" form="editPatientForm{{ $patient->id }}"
                                                        class="btn btn-save text-white">
                                                        <i class="bi bi-check-lg me-2"></i>Update Patient
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Patient Modal -->
                                    <div class="modal fade delete-modal" id="deletePatientModal{{ $patient->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                        Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <i class="bi bi-person-x-fill delete-icon"></i>
                                                    <h4 class="mb-3">Are you sure?</h4>
                                                    <p class="text-muted mb-0">
                                                        You are about to delete <strong>{{ $patient->name }}</strong> from the
                                                        patient list.
                                                    </p>
                                                    <p class="text-muted">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-lg me-2"></i>No, Keep Patient
                                                    </button>
                                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-delete text-white">
                                                            <i class="bi bi-trash me-2"></i>Yes, Delete Patient
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No patients found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
 
                        </table>
                    </div>

                </div>

                <!-- Footer with pagination -->
                <div class="card-footer bg-white border-0 gap-3 py-3" style="color:#7589a2; font-size:0.85rem;">
                    <i class="bi bi-info-circle"></i> {{ $patients->total() }} patient records
                    @if($patients->total() > 0)
                        · Last updated {{ now()->format('M d, Y') }}
                    @endif
                    <nav class="float-end" aria-label="Page navigation">
                        {{ $patients->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </main>

    <!-- Optional: Filter placeholder function (can be extended) -->
    <script>


@endsection