@extends('layout.master')

@section('content')
<main class="page-content">
<div class="container-fluid px-3 px-md-4">

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <span>Please correct the following errors:</span>
            </div>
            <ul class="mb-0 ps-3 font-13">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ========== TOP HEADER & FINANCIAL CONTROLS ========== -->
    <div class="card radius-10 border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 24px;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">Hospital Payments &amp; Financial Overview</h4>
                        <small class="text-muted">Multi-category revenue tracking, pharmacy, consultations, procedures, lab billing, and custom payments</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="bi bi-plus-circle-fill"></i> Record Payment
                    </button>
                    <a href="{{ route('reports.payments.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Financial Statement (PDF)
                    </a>
                    <a href="{{ route('hospital-payments') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-3 p-md-4">
            <!-- Filter Toolbar -->
            <form method="GET" action="{{ route('hospital-payments') }}" class="row g-2 align-items-end mb-4 bg-light p-3 rounded-3 border">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label font-12 fw-semibold text-secondary mb-1">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label font-12 fw-semibold text-secondary mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label font-12 fw-semibold text-secondary mb-1">Category</label>
                    <select name="category" class="form-select form-select-sm">
                        <option value="all" {{ $selectedCategory === 'all' ? 'selected' : '' }}>All Categories</option>
                        @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}" {{ $selectedCategory === $catKey ? 'selected' : '' }}>{{ $catLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <label class="form-label font-12 fw-semibold text-secondary mb-1">Search Keyword</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Invoice / Patient / Phone" value="{{ $search }}">
                </div>
                <div class="col-12 col-md-2 d-flex gap-1 flex-wrap">
                    <button type="submit" class="btn btn-primary btn-sm px-3 flex-fill">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-12 mt-2 d-flex gap-1 flex-wrap align-items-center">
                    <span class="font-12 text-secondary me-2">Quick Presets:</span>
                    <a href="{{ route('hospital-payments', ['filter' => 'today', 'category' => $selectedCategory]) }}" class="btn btn-xs {{ $filterType === 'today' ? 'btn-dark' : 'btn-outline-secondary' }} py-0 px-2 font-12">Today</a>
                    <a href="{{ route('hospital-payments', ['filter' => 'week', 'category' => $selectedCategory]) }}" class="btn btn-xs {{ $filterType === 'week' ? 'btn-dark' : 'btn-outline-secondary' }} py-0 px-2 font-12">This Week</a>
                    <a href="{{ route('hospital-payments', ['filter' => 'month', 'category' => $selectedCategory]) }}" class="btn btn-xs {{ $filterType === 'month' ? 'btn-dark' : 'btn-outline-secondary' }} py-0 px-2 font-12">This Month</a>
                    <a href="{{ route('hospital-payments', ['filter' => 'all']) }}" class="btn btn-xs {{ $filterType === 'all' && empty($startDate) ? 'btn-dark' : 'btn-outline-secondary' }} py-0 px-2 font-12">All Time</a>

                    <div class="ms-auto">
                        <span class="badge bg-white text-dark border px-2 py-1 font-12 d-inline-flex align-items-center gap-1 shadow-sm">
                            <i class="bi bi-calendar3 text-primary"></i> 
                            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'All Time' }}
                            @if($endDate && $endDate !== $startDate) - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} @endif
                        </span>
                    </div>
                </div>
            </form>

            <!-- 3 Main Financial Summary Blocks -->
            <div class="row g-3 mb-3">
                <!-- Block 1: Total Sales & Revenue Streams -->
                <div class="col-12 col-lg-4">
                    <div class="h-100 p-3 rounded-3 border" style="background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase font-12 fw-bold text-success">Total Sales &amp; Billing</span>
                            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-cash-stack"></i> Gross Collections</span>
                        </div>
                        <h3 class="fw-bold text-dark mb-3">Rs. {{ number_format($totalSales, 0) }}</h3>
                        <ul class="list-unstyled mb-0 font-13">
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary d-flex align-items-center gap-1">
                                    <i class="bi bi-person-badge text-primary font-12"></i> Consultation Billing
                                </span>
                                <span class="fw-bold text-dark">Rs. {{ number_format($consultationSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary d-flex align-items-center gap-1">
                                    <i class="bi bi-capsule text-info font-12"></i> Pharmacy / Medicines
                                </span>
                                <span class="{{ $medicineSales > 0 ? 'fw-bold text-info' : 'text-muted' }}">Rs. {{ number_format($medicineSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary d-flex align-items-center gap-1">
                                    <i class="bi bi-bandaid text-purple font-12"></i> General Procedures
                                </span>
                                <span class="{{ $procedureSales > 0 ? 'fw-bold text-dark' : 'text-muted' }}">Rs. {{ number_format($procedureSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary d-flex align-items-center gap-1">
                                    <i class="bi bi-virus text-warning font-12"></i> Diagnostics / Lab
                                </span>
                                <span class="{{ $labSales > 0 ? 'fw-bold text-warning-emphasis' : 'text-muted' }}">Rs. {{ number_format($labSales, 0) }}</span>
                            </li>
                            @if($additionalSales > 0)
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary d-flex align-items-center gap-1">
                                    <i class="bi bi-hospital text-danger font-12"></i> Emergency &amp; Other
                                </span>
                                <span class="fw-bold text-danger">Rs. {{ number_format($additionalSales, 0) }}</span>
                            </li>
                            @endif
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary d-flex align-items-center gap-1">
                                    <i class="bi bi-tag-fill text-danger font-12"></i> Total Discounts Given
                                </span>
                                <span class="text-danger fw-semibold">- Rs. {{ number_format($totalDiscount, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2 fw-bold text-dark">
                                <span>Hospital Gross Share</span>
                                <span class="text-success fs-6">Rs. {{ number_format($totalSales, 0) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Block 2: Hospital Revenue & Net Profit -->
                <div class="col-12 col-lg-4">
                    <div class="h-100 p-3 rounded-3 border" style="background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase font-12 fw-bold text-primary">Hospital Net Revenue</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-graph-up-arrow"></i> Net Profit</span>
                        </div>
                        <h3 class="fw-bold text-primary mb-3">Rs. {{ number_format($netRevenue, 0) }}</h3>
                        <ul class="list-unstyled mb-0 font-13">
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Gross Service Collections</span>
                                <span class="fw-bold text-dark">Rs. {{ number_format($totalSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Daily Operational Expenses</span>
                                <span class="text-danger fw-semibold">- Rs. {{ number_format($dailyExpenses, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Monthly Staff Payroll Liability</span>
                                <span class="text-muted">Rs. {{ number_format($staffPayroll, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2 fw-bold">
                                <span class="text-dark">Operating Balance</span>
                                <span class="{{ $netRevenue > 0 ? 'text-primary fs-6' : 'text-danger fs-6' }}">Rs. {{ number_format($netRevenue, 0) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Block 3: Patient Accounts & Dues -->
                <div class="col-12 col-lg-4">
                    <div class="h-100 p-3 rounded-3 border" style="background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase font-12 fw-bold text-warning-emphasis">Patient Accounts &amp; Dues</span>
                            <span class="badge bg-warning bg-opacity-25 text-dark"><i class="bi bi-person-lines-fill"></i> Balances</span>
                        </div>
                        <h3 class="fw-bold text-danger mb-3">Rs. {{ number_format($totalDueAmount, 0) }}</h3>
                        <ul class="list-unstyled mb-0 font-13">
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Total Outstanding Patient Dues</span>
                                <span class="text-danger fw-bold">Rs. {{ number_format($totalDueAmount, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Patient Wallet / Advances</span>
                                <span class="text-success fw-bold">Rs. {{ number_format($totalWalletAmount, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Avg Amount per Transaction</span>
                                <span class="fw-semibold text-dark">Rs. {{ number_format($avgFeePerVisit, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2 fw-bold text-dark">
                                <span>Collection Efficiency</span>
                                <span class="badge bg-success bg-opacity-10 text-success fs-6">{{ $collectionRate }}%</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bottom 2 Metrics Side-by-Side -->
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase font-12 fw-semibold text-secondary">Total Registered Patients</span>
                            <h4 class="mb-0 fw-bold text-dark mt-1">{{ number_format($totalPatients) }}</h4>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-3 border bg-light-subtle d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-uppercase font-12 fw-semibold text-secondary">Recorded Hospital Transactions</span>
                            <h4 class="mb-0 fw-bold text-dark mt-1">{{ number_format($payments->total() + $appointments->total()) }}</h4>
                            <small class="text-muted font-11">{{ $payments->total() }} Invoices / Custom Payments · {{ $appointments->total() }} Consultations</small>
                        </div>
                        <div class="rounded-circle bg-purple bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== INTERACTIVE LEDGER TABS: PAYMENTS vs APPOINTMENTS ========== -->
    <div class="card radius-10 border-0 shadow-sm">
        <div class="card-header bg-transparent pt-3 pb-0 border-bottom">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <ul class="nav nav-tabs card-header-tabs" id="paymentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold d-flex align-items-center gap-2" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices-pane" type="button" role="tab" aria-controls="invoices-pane" aria-selected="true">
                            <i class="bi bi-journal-text text-primary"></i> Hospital Invoices &amp; Category Payments
                            <span class="badge bg-primary rounded-pill">{{ $payments->total() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold d-flex align-items-center gap-2" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments-pane" type="button" role="tab" aria-controls="appointments-pane" aria-selected="false">
                            <i class="bi bi-calendar-check text-success"></i> Appointments Consultation Stream
                            <span class="badge bg-secondary rounded-pill">{{ $appointments->total() }}</span>
                        </button>
                    </li>
                </ul>
                <div class="d-flex gap-2 mb-2">
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="bi bi-plus-lg"></i> Add Payment
                    </button>
                </div>
            </div>
        </div>

        <div class="tab-content" id="paymentTabsContent">
            <!-- TAB 1: HOSPITAL INVOICES & CATEGORY PAYMENTS -->
            <div class="tab-pane fade show active" id="invoices-pane" role="tabpanel" aria-labelledby="invoices-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light font-13 text-secondary">
                            <tr>
                                <th class="ps-3">Invoice #</th>
                                <th>Date &amp; Method</th>
                                <th>Category</th>
                                <th>Patient Name</th>
                                <th>Attending Doctor</th>
                                <th class="text-end">Gross Amount</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">Net Paid</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $pay)
                                @php
                                    $catColor = match($pay->category) {
                                        'Consultation' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                        'Pharmacy / Medicine' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
                                        'General Procedures' => 'bg-purple bg-opacity-10 text-primary border border-primary border-opacity-25',
                                        'Diagnostics / Lab' => 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-50',
                                        'Emergency' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
                                        default => 'bg-secondary bg-opacity-10 text-secondary border',
                                    };
                                    $catIcon = match($pay->category) {
                                        'Consultation' => 'bi-person-badge',
                                        'Pharmacy / Medicine' => 'bi-capsule',
                                        'General Procedures' => 'bi-bandaid',
                                        'Diagnostics / Lab' => 'bi-virus',
                                        'Emergency' => 'bi-hospital',
                                        default => 'bi-tag',
                                    };
                                    $statusColor = match($pay->status) {
                                        'Paid' => 'bg-success',
                                        'Partial' => 'bg-info text-dark',
                                        'Pending' => 'bg-warning text-dark',
                                        'Refunded' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3">
                                        <span class="badge bg-light text-dark border px-2 py-1 font-12 fw-bold font-monospace">
                                            {{ $pay->invoice_no }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-13 fw-semibold text-dark">
                                            {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') : '-' }}
                                        </div>
                                        <span class="badge bg-light text-secondary border font-11 px-2 py-0">
                                            <i class="bi bi-credit-card me-1"></i>{{ $pay->payment_method }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $catColor }} px-2 py-1 font-12 d-inline-flex align-items-center gap-1">
                                            <i class="bi {{ $catIcon }}"></i> {{ $pay->category }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $pay->patient_name }}</div>
                                        @if($pay->patient_phone)
                                            <small class="text-muted font-11 font-monospace">{{ $pay->patient_phone }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pay->doctor)
                                            <div class="fw-semibold text-dark">{{ $pay->doctor->name }}</div>
                                            <small class="text-muted font-11">{{ $pay->doctor->speciality }}</small>
                                        @else
                                            <span class="text-muted font-12">Hospital Staff / OPD</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold text-dark">
                                        Rs. {{ number_format((float) $pay->amount, 0) }}
                                    </td>
                                    <td class="text-end text-danger fw-semibold">
                                        @if((float)$pay->discount > 0)
                                            - Rs. {{ number_format((float) $pay->discount, 0) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success fs-6">
                                        Rs. {{ number_format((float) $pay->net_amount, 0) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusColor }} font-11 px-2 py-1 rounded-pill">
                                            {{ $pay->status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1 font-11 btn-view-receipt" 
                                                data-invoice="{{ $pay->invoice_no }}"
                                                data-date="{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') : '' }}"
                                                data-patient="{{ $pay->patient_name }}"
                                                data-phone="{{ $pay->patient_phone }}"
                                                data-category="{{ $pay->category }}"
                                                data-doctor="{{ $pay->doctor ? $pay->doctor->name : 'Clinical Staff' }}"
                                                data-amount="Rs. {{ number_format((float)$pay->amount, 2) }}"
                                                data-discount="Rs. {{ number_format((float)$pay->discount, 2) }}"
                                                data-net="Rs. {{ number_format((float)$pay->net_amount, 2) }}"
                                                data-method="{{ $pay->payment_method }}"
                                                data-notes="{{ $pay->notes }}"
                                                title="View Receipt">
                                                <i class="bi bi-receipt"></i> Receipt
                                            </button>
                                            <form action="{{ route('hospital-payments.destroy', $pay->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete payment #{{ $pay->invoice_no }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1 font-11" title="Delete Payment">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"></i>
                                        No custom payments or invoices found matching the selected filters.
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                                                <i class="bi bi-plus-circle me-1"></i> Record First Payment
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())
                    <div class="card-footer bg-transparent py-3 border-top">
                        <div class="d-flex justify-content-center">
                            {{ $payments->links() }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- TAB 2: APPOINTMENTS CONSULTATION STREAM -->
            <div class="tab-pane fade" id="appointments-pane" role="tabpanel" aria-labelledby="appointments-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light font-13 text-secondary">
                            <tr>
                                <th class="ps-3">Appointment ID</th>
                                <th>Date &amp; Time</th>
                                <th>Patient Name</th>
                                <th>Patient Phone</th>
                                <th>Doctor &amp; Specialty</th>
                                <th class="text-end">Consultation Fee</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $apt)
                                <tr>
                                    <td class="ps-3">
                                        <span class="badge bg-light text-dark border px-2 py-1 font-12 fw-bold">
                                            #APT-{{ str_pad($apt->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-13 fw-semibold text-dark">
                                            {{ $apt->created_at ? $apt->created_at->format('d/m/Y') : '-' }}
                                        </div>
                                        <small class="text-muted font-11">
                                            {{ $apt->time ?: ($apt->created_at ? $apt->created_at->format('h:i A') : '-') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $apt->name }}</span>
                                        @if($apt->age)
                                            <small class="text-muted font-11">({{ $apt->age }} yrs)</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 font-12 font-monospace">
                                            {{ $apt->phone ?: 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">
                                            {{ $apt->doctor ? $apt->doctor->name : 'General Medical Officer' }}
                                        </div>
                                        <small class="text-muted font-11">
                                            {{ $apt->doctor ? $apt->doctor->speciality : 'General OPD' }}
                                        </small>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        Rs. {{ number_format($apt->doctor ? (float)$apt->doctor->fee : 0, 0) }}
                                    </td>
                                    <td>
                                        @php
                                            $st = strtolower($apt->status ?? 'pending');
                                            $badgeClass = match($st) {
                                                'completed' => 'bg-success',
                                                'confirmed' => 'bg-primary',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-warning text-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} font-11 px-2 py-1 rounded-pill">
                                            {{ ucfirst($apt->status ?: 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('appointment.show', $apt->id) }}" class="btn btn-outline-secondary btn-sm px-2 py-1 font-11" title="View Details">
                                                <i class="bi bi-eye"></i> Details
                                            </a>
                                            <a href="{{ route('pdf.appointment', $apt->id) }}" target="_blank" class="btn btn-outline-danger btn-sm px-2 py-1 font-11" title="Print Receipt">
                                                <i class="bi bi-receipt"></i> Slip
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt-cutoff fs-2 d-block mb-2 text-secondary"></i>
                                        No appointment consultations recorded for the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($appointments->hasPages())
                    <div class="card-footer bg-transparent py-3 border-top">
                        <div class="d-flex justify-content-center">
                            {{ $appointments->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
</main>

<!-- ========== MODAL: RECORD CUSTOM HOSPITAL PAYMENT ========== -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="addPaymentModalLabel">
                    <i class="bi bi-cash-coin fs-5"></i> Record Hospital Payment / Invoice
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hospital-payments.store') }}" method="POST" id="hospitalPaymentForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Category Selection -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Payment Category <span class="text-danger">*</span></label>
                            <select name="category" id="modal_category" class="form-select" required>
                                <option value="" disabled selected>-- Select Clinical Category --</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Link with Registered Patient (optional) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Link Registered Patient <small class="text-muted fw-normal">(Optional)</small></label>
                            <select name="patient_id" id="modal_patient_select" class="form-select">
                                <option value="">-- Direct / Walk-in Patient --</option>
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}" data-name="{{ $p->name }}" data-phone="{{ $p->phone }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->phone ?: 'No phone' }}) - Due: Rs. {{ number_format($p->due_amount, 0) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Patient Name -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" id="modal_patient_name" class="form-control" placeholder="e.g. Tariq Mehmood" value="{{ old('patient_name') }}" required>
                        </div>

                        <!-- Patient Phone -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Patient Phone</label>
                            <input type="text" name="patient_phone" id="modal_patient_phone" class="form-control" placeholder="e.g. 0300 1234567" value="{{ old('patient_phone') }}">
                        </div>

                        <!-- Attending Doctor (Optional) -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Attending Doctor <small class="text-muted fw-normal">(Optional)</small></label>
                            <select name="doctor_id" id="modal_doctor_id" class="form-select">
                                <option value="">-- None / General Hospital OPD --</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" data-fee="{{ $doc->fee }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->name }} ({{ $doc->speciality }}) - Fee: Rs. {{ number_format($doc->fee, 0) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                @foreach($paymentMethods as $mKey => $mLabel)
                                    <option value="{{ $mKey }}" {{ old('payment_method', 'Cash') === $mKey ? 'selected' : '' }}>{{ $mLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Financials: Gross Amount, Discount, Net Payable -->
                        <div class="col-12 col-md-4">
                            <label class="form-label font-13 fw-semibold">Gross Amount (Rs.) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text font-12 fw-bold">Rs.</span>
                                <input type="number" step="0.01" min="0.01" name="amount" id="modal_amount" class="form-control fw-bold" placeholder="0.00" value="{{ old('amount') }}" required>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label font-13 fw-semibold">Discount (Rs.) <small class="text-muted fw-normal">(Optional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text font-12 fw-bold">Rs.</span>
                                <input type="number" step="0.01" min="0" name="discount" id="modal_discount" class="form-control" placeholder="0.00" value="{{ old('discount', 0) }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label font-13 fw-semibold text-success">Net Payable Amount</label>
                            <div class="input-group">
                                <span class="input-group-text font-12 fw-bold bg-success text-white">Rs.</span>
                                <input type="text" id="modal_net_preview" class="form-control fw-bold bg-light text-success fs-6" readonly value="0.00">
                            </div>
                        </div>

                        <!-- Date & Status -->
                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label font-13 fw-semibold">Payment Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Paid" {{ old('status', 'Paid') === 'Paid' ? 'selected' : '' }}>Paid in Full</option>
                                <option value="Partial" {{ old('status') === 'Partial' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending / Unpaid</option>
                            </select>
                        </div>

                        <!-- Clinical Notes & Descriptions -->
                        <div class="col-12">
                            <label class="form-label font-13 fw-semibold">Treatment, Medicines or Service Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Amoxicillin 500mg, CBC Blood Test, Dental Scaling &amp; Polishing">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Record &amp; Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== MODAL: VIEW & PRINT INVOICE RECEIPT ========== -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title font-14 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-printer-fill text-warning"></i> Hospital Payment Slip
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="printableReceiptArea">
                <div class="text-center pb-3 border-bottom mb-3">
                    <h5 class="fw-bold mb-0 text-dark">HOSPITAL MANAGEMENT CLINIC</h5>
                    <p class="text-muted font-11 mb-1">Official Medical Bill &amp; Payment Slip</p>
                    <span class="badge bg-light text-dark border font-12 fw-bold font-monospace" id="receipt_invoice">INV-0000</span>
                </div>
                <div class="row g-2 font-13 mb-3">
                    <div class="col-6"><span class="text-muted">Date:</span> <strong id="receipt_date">-</strong></div>
                    <div class="col-6 text-end"><span class="text-muted">Method:</span> <strong id="receipt_method">-</strong></div>
                    <div class="col-12"><span class="text-muted">Patient:</span> <strong id="receipt_patient">-</strong></div>
                    <div class="col-12"><span class="text-muted">Phone:</span> <span id="receipt_phone" class="font-monospace">-</span></div>
                    <div class="col-12"><span class="text-muted">Doctor:</span> <span id="receipt_doctor">-</span></div>
                    <div class="col-12"><span class="text-muted">Service Category:</span> <span id="receipt_category" class="badge bg-primary bg-opacity-10 text-primary">-</span></div>
                </div>
                <div class="bg-light p-3 rounded-3 border mb-3">
                    <div class="d-flex justify-content-between font-13 py-1">
                        <span class="text-muted">Gross Total:</span>
                        <strong id="receipt_gross">Rs. 0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between font-13 py-1 text-danger">
                        <span>Discount:</span>
                        <span id="receipt_discount">Rs. 0.00</span>
                    </div>
                    <hr class="my-1">
                    <div class="d-flex justify-content-between font-14 fw-bold text-success pt-1">
                        <span>Net Paid:</span>
                        <span id="receipt_net" class="fs-6">Rs. 0.00</span>
                    </div>
                </div>
                <div class="font-12 text-muted" id="receipt_notes_wrapper">
                    <strong>Notes:</strong> <span id="receipt_notes">None</span>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm px-3" onclick="window.printReceiptModal()">
                    <i class="bi bi-printer me-1"></i> Print Slip
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Real-time calculation of Net Amount in Add Payment Modal
    const amountInput = document.getElementById('modal_amount');
    const discountInput = document.getElementById('modal_discount');
    const netPreview = document.getElementById('modal_net_preview');

    function calculateNet() {
        const amt = parseFloat(amountInput.value) || 0;
        const disc = parseFloat(discountInput.value) || 0;
        const net = Math.max(0, amt - disc);
        netPreview.value = net.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    if (amountInput && discountInput) {
        amountInput.addEventListener('input', calculateNet);
        discountInput.addEventListener('input', calculateNet);
    }

    // Auto-fill patient name and phone on select
    const patientSelect = document.getElementById('modal_patient_select');
    const patientName = document.getElementById('modal_patient_name');
    const patientPhone = document.getElementById('modal_patient_phone');

    if (patientSelect) {
        patientSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                patientName.value = selected.getAttribute('data-name') || '';
                patientPhone.value = selected.getAttribute('data-phone') || '';
            }
        });
    }

    // Auto-fill doctor fee if Consultation is selected and Doctor chosen
    const doctorSelect = document.getElementById('modal_doctor_id');
    const categorySelect = document.getElementById('modal_category');

    if (doctorSelect && amountInput) {
        doctorSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const fee = parseFloat(selected.getAttribute('data-fee')) || 0;
            if (fee > 0 && (!amountInput.value || categorySelect.value === 'Consultation')) {
                amountInput.value = fee;
                calculateNet();
            }
        });
    }

    // View Receipt Modal Population
    const receiptModalEl = document.getElementById('receiptModal');
    let receiptModal = null;
    if (receiptModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        receiptModal = bootstrap.Modal.getOrCreateInstance(receiptModalEl);
    }

    document.querySelectorAll('.btn-view-receipt').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('receipt_invoice').innerText = this.getAttribute('data-invoice');
            document.getElementById('receipt_date').innerText = this.getAttribute('data-date');
            document.getElementById('receipt_method').innerText = this.getAttribute('data-method');
            document.getElementById('receipt_patient').innerText = this.getAttribute('data-patient');
            document.getElementById('receipt_phone').innerText = this.getAttribute('data-phone') || 'N/A';
            document.getElementById('receipt_doctor').innerText = this.getAttribute('data-doctor');
            document.getElementById('receipt_category').innerText = this.getAttribute('data-category');
            document.getElementById('receipt_gross').innerText = this.getAttribute('data-amount');
            document.getElementById('receipt_discount').innerText = this.getAttribute('data-discount');
            document.getElementById('receipt_net').innerText = this.getAttribute('data-net');
            document.getElementById('receipt_notes').innerText = this.getAttribute('data-notes') || 'N/A';

            if (receiptModal) {
                receiptModal.show();
            } else if (window.$) {
                $(receiptModalEl).modal('show');
            }
        });
    });

    // Print Receipt Helper
    window.printReceiptModal = function() {
        const printContent = document.getElementById('printableReceiptArea').innerHTML;
        const printWindow = window.open('', '_blank', 'width=600,height=700');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Hospital Receipt</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 20px; }
                        @media print { .btn { display: none; } }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${printContent}
                </body>
            </html>
        `);
        printWindow.document.close();
    };
});
</script>
@endpush

@endsection