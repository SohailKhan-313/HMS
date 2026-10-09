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

    .category-badge {
        background: #e1ecfe;
        color: #194a7a;
        font-weight: 500;
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .amount-phone {
        background: #e1ecfe;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-family: 'Inter', monospace;
        font-size: 0.9rem;
    }

    .action-icon {
        color: #577a9e;
        font-size: 1.3rem;
        padding: 0.3rem;
        border-radius: 8px;
        transition: 0.15s;
        text-decoration: none;
        margin: 0 2px;
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
        background: white;
    }

    .btn-outline-filter:hover {
        background: #e9f0f8;
        border-color: #a0b8d0;
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

    .btn-primary-custom:hover {
        background: #0e4a7c;
        color: white;
    }

    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        background: linear-gradient(135deg, #1a5f9c 0%, #0e4a7c 100%);
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

    .form-control, .form-select {
        border: 2px solid #e9eef2;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1a5f9c;
        box-shadow: 0 0 0 0.2rem rgba(26, 95, 156, 0.15);
    }

    .btn-save {
        background: linear-gradient(135deg, #1a5f9c 0%, #0e4a7c 100%);
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: all 0.2s;
        color: white;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(26, 95, 156, 0.4);
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

    .delete-modal .modal-header {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    }

    .delete-icon {
        font-size: 4rem;
        color: #ef4444;
        margin-bottom: 1rem;
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
        border: none;
        border-radius: 12px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
    }

    /* Print Styles: triggers cleanly on window.print() */
    @media print {
        .top-header,
        aside.sidebar-wrapper,
        .switcher-body,
        .hms-alerts-wrapper,
        .btn,
        .card-header .d-flex,
        .card-body.border-bottom.bg-light,
        .action-icon,
        th:last-child,
        td:last-child,
        .modal,
        .back-to-top {
            display: none !important;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .page-content,
        .container-fluid,
        .card,
        .appointment-card {
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
        }

        .card-header {
            border: none !important;
            padding: 0 0 1rem 0 !important;
        }

        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table th,
        .table td {
            border: 1px solid #cbd5e1 !important;
            padding: 7px 10px !important;
            color: #000 !important;
        }

        .table thead th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
        }

        .amount-phone {
            background: transparent !important;
            color: #000 !important;
            font-weight: 700 !important;
        }

        .category-badge {
            background: transparent !important;
            border: 1px solid #94a3b8 !important;
            color: #000 !important;
            padding: 2px 6px !important;
        }
    }
</style>

<main class="page-content">
    <div class="container-fluid px-0">
        <!-- Printable Header (Visible strictly when browser print is triggered) -->
        <div class="d-none d-print-block pb-3 mb-3 border-bottom text-center">
            <h2 class="fw-bold mb-1" style="color: #0b1b2f; letter-spacing: 0.5px;">CITY HOSPITAL &amp; MEDICAL CENTER</h2>
            <p class="text-secondary font-12 mb-2">123 Healthcare Avenue, Wellness City | Phone: +92 300 1234567 | care@cityhospital.com</p>
            <h4 class="fw-bold text-dark mt-2 mb-2 text-uppercase" style="letter-spacing: 1px;">Daily Expenses Ledger Report</h4>
            <div class="d-flex justify-content-between align-items-center font-12 text-muted border-top border-bottom py-2 mt-2">
                <span><strong>Period:</strong> {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : 'Earliest' }} to {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : 'Present' }}</span>
                <span><strong>Category:</strong> {{ ($selectedCategory && $selectedCategory !== 'all') ? $selectedCategory : 'All Categories' }}</span>
                <span><strong>Total:</strong> <strong class="text-danger font-monospace">PKR {{ number_format($totalFilteredAmount ?? $expenses->sum('amount'), 2) }}</strong></span>
                <span><strong>Generated:</strong> {{ now()->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="card appointment-card">
            <!-- Header: Daily Expense -->
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h2 class="h3 mb-0 fw-semibold d-flex align-items-center gap-3" style="color:#0b1b2f;">
                    <i class="bi bi-wallet2 fs-1 text-primary"
                        style="background:#eef4fe; padding:0.5rem; border-radius:14px;"></i>
                    Daily Expense Ledger
                </h2>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-filter btn-sm" onclick="location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Reload
                    </button>
                    <!-- Clean Print Expenses trigger invoking browser window.print() -->
                    <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm" onclick="window.print()" title="Print Expenses">
                        <i class="bi bi-printer me-1"></i> Print Expenses
                    </button>
                    <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#expenseModal">
                        <i class="bi bi-plus-circle me-2"></i> Add Expense
                    </button>
                </div>
            </div>

            <!-- Date Range & Search Filter Toolbar -->
            <div class="card-body p-3 p-md-4 border-bottom bg-light bg-opacity-50">
                <form method="GET" action="{{ route('expenses.index') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label font-12 fw-semibold text-secondary mb-1">
                            <i class="bi bi-calendar-event me-1"></i> From Date
                        </label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate ?? request('from_date') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label font-12 fw-semibold text-secondary mb-1">
                            <i class="bi bi-calendar-check me-1"></i> To Date
                        </label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate ?? request('to_date') }}">
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label font-12 fw-semibold text-secondary mb-1">
                            <i class="bi bi-tag me-1"></i> Category
                        </label>
                        <select name="category" class="form-select form-select-sm">
                            <option value="all" {{ ($selectedCategory ?? request('category', 'all')) === 'all' ? 'selected' : '' }}>All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ ($selectedCategory ?? request('category')) === $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label font-12 fw-semibold text-secondary mb-1">
                            <i class="bi bi-search me-1"></i> Search Title
                        </label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Title or keyword" value="{{ $search ?? request('search') }}">
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm px-3 flex-fill">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>

                    <!-- Summary Status Bar -->
                    <div class="col-12 mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1 font-12">
                                <i class="bi bi-list-check me-1"></i> {{ $totalCount ?? $expenses->count() }} Entries
                            </span>
                            @if(!empty($fromDate) || !empty($toDate))
                                <span class="badge bg-primary bg-opacity-10 text-primary border px-2 py-1 font-12">
                                    <i class="bi bi-calendar-range me-1"></i>
                                    {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : 'Start' }} to {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : 'Today' }}
                                </span>
                            @endif
                            @if(!empty($selectedCategory) && $selectedCategory !== 'all')
                                <span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 font-12">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $selectedCategory }}
                                </span>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="text-secondary font-12 me-1">Total Filtered Expenditure:</span>
                            <span class="fw-bold text-danger fs-6 font-monospace">PKR {{ number_format($totalFilteredAmount ?? $expenses->sum('amount'), 2) }}</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Expense Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Expense Name / Description</th>
                                <th>Category</th>
                                <th class="text-end pe-3">Amount (PKR)</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $e)
                            <tr>
                                <td>
                                    <span class="text-secondary font-monospace fw-medium">
                                        {{ \Carbon\Carbon::parse($e->date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td><span class="fw-semibold text-dark">{{ $e->name }}</span></td>
                                <td><span class="category-badge">{{ $e->catagory ?: 'General' }}</span></td>
                                <td class="text-end pe-3">
                                    <span class="amount-phone fw-bold text-danger">PKR {{ number_format($e->amount, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="action-icon" data-bs-toggle="modal" data-bs-target="#editExpense{{ $e->id }}" title="Edit Expense">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="#" class="action-icon text-danger" data-bs-toggle="modal" data-bs-target="#deleteExpense{{ $e->id }}" title="Delete Expense">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt-cutoff fs-2 d-block mb-2 text-secondary"></i>
                                    No expenses found for the selected criteria. Click "Add Expense" to record one.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer: count and total summary -->
            <div class="card-footer bg-white border-top py-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="color:#7589a2; font-size:0.85rem;">
                <span><i class="bi bi-info-circle me-1"></i> Showing {{ count($expenses) }} expense records</span>
                <span class="text-dark fw-semibold">
                    Total: <span class="text-danger font-monospace">PKR {{ number_format($totalFilteredAmount ?? $expenses->sum('amount'), 2) }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Modals moved outside table for strict HTML compliance and event reliability -->
    @foreach($expenses as $e)
        <!-- EDIT MODAL -->
        <div class="modal fade" id="editExpense{{ $e->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Expense – {{ $e->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('expenses.update', $e->id) }}" method="POST" id="editExpenseForm{{ $e->id }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-calendar me-2"></i>Date*</label>
                                    <input type="date" name="date" value="{{ \Carbon\Carbon::parse($e->date)->format('Y-m-d') }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-tag me-2"></i>Name*</label>
                                    <input type="text" name="name" value="{{ $e->name }}" class="form-control" placeholder="Expense description" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-grid me-2"></i>Category*</label>
                                    <input type="text" name="catagory" list="expenseCategoryListEdit{{ $e->id }}" value="{{ $e->catagory }}" class="form-control" placeholder="Select or type category" required>
                                    <datalist id="expenseCategoryListEdit{{ $e->id }}">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-wallet2 me-2"></i>Amount (PKR)*</label>
                                    <input type="number" step="0.01" min="0" name="amount" value="{{ $e->amount }}" class="form-control" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>Cancel
                        </button>
                        <button type="submit" form="editExpenseForm{{ $e->id }}" class="btn btn-save">
                            <i class="bi bi-check-lg me-2"></i>Update Expense
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div class="modal fade delete-modal" id="deleteExpense{{ $e->id }}" tabindex="-1" aria-hidden="true">
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
                        <i class="bi bi-wallet-fill delete-icon"></i>
                        <h4 class="mb-3">Are you sure?</h4>
                        <p class="text-muted mb-0">
                            You are about to delete <strong>{{ $e->name }}</strong> (PKR {{ number_format($e->amount, 2) }}).
                        </p>
                        <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>No, Keep Expense
                        </button>
                        <form action="{{ route('expenses.destroy', $e->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete">
                                <i class="bi bi-trash me-2"></i>Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- ADD EXPENSE MODAL -->
    <div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Add New Expense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('expenses.store') }}" method="POST" id="addExpenseForm">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-calendar me-2"></i>Date*</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-tag me-2"></i>Name / Description*</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g., Office Supplies, Generator Fuel" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-grid me-2"></i>Category*</label>
                                <input type="text" name="catagory" list="expenseCategoryListAdd" class="form-control" placeholder="Select or type category (e.g., Utility Bills)" required>
                                <datalist id="expenseCategoryListAdd">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-wallet2 me-2"></i>Amount (PKR)*</label>
                                <input type="number" step="0.01" min="0" name="amount" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </button>
                    <button type="submit" form="addExpenseForm" class="btn btn-save">
                        <i class="bi bi-check-lg me-2"></i>Save Expense
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
