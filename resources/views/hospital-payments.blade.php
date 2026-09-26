@extends('layout.master')

@section('content')
<main class="page-content">
<div class="container-fluid px-3 px-md-4">

    <!-- ========== SUMMARY & FINANCIAL KPI CARD ========== -->
    <div class="card radius-10 border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 24px;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">Hospital Payments &amp; Financial Overview</h4>
                        <small class="text-muted">Real-time revenue, consultation billing, operating expenses, and patient accounts</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('reports.payments.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Print Financial Statement (PDF)
                    </a>
                    <a href="{{ route('hospital-payments') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-3 p-md-4">
            <!-- Filter Toolbar -->
            <form method="GET" action="{{ route('hospital-payments') }}" class="row g-2 align-items-end mb-4 bg-light p-3 rounded-3 border">
                <div class="col-12 col-md-3">
                    <label class="form-label font-12 fw-semibold text-secondary mb-1">Start Date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label font-12 fw-semibold text-secondary mb-1">End Date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                </div>
                <div class="col-12 col-md-4 d-flex gap-1 flex-wrap">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-filter me-1"></i> Apply Filter
                    </button>
                    <a href="{{ route('hospital-payments', ['filter' => 'today']) }}" class="btn btn-sm {{ $filterType === 'today' ? 'btn-dark' : 'btn-outline-secondary' }}">Today</a>
                    <a href="{{ route('hospital-payments', ['filter' => 'week']) }}" class="btn btn-sm {{ $filterType === 'week' ? 'btn-dark' : 'btn-outline-secondary' }}">This Week</a>
                    <a href="{{ route('hospital-payments', ['filter' => 'month']) }}" class="btn btn-sm {{ $filterType === 'month' ? 'btn-dark' : 'btn-outline-secondary' }}">This Month</a>
                </div>
                <div class="col-12 col-md-2 text-md-end">
                    <span class="badge bg-white text-dark border px-2 py-2 font-12 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-calendar3 text-primary"></i> 
                        {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M') : 'All Time' }}
                        @if($endDate && $endDate !== $startDate) - {{ \Carbon\Carbon::parse($endDate)->format('d M') }} @endif
                    </span>
                </div>
            </form>

            <!-- 3 Main Summary Blocks (Matching Requested Structure) -->
            <div class="row g-3 mb-3">
                <!-- Block 1: Total Sales -->
                <div class="col-12 col-lg-4">
                    <div class="h-100 p-3 rounded-3 border" style="background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase font-12 fw-bold text-success">Total Sales &amp; Billing</span>
                            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-cash-stack"></i> Gross</span>
                        </div>
                        <h3 class="fw-bold text-dark mb-3">Rs. {{ number_format($totalSales, 0) }}</h3>
                        <ul class="list-unstyled mb-0 font-13">
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Consultation Billing</span>
                                <span class="fw-bold text-dark">Rs. {{ number_format($consultationSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Pharmacy / Medicines</span>
                                <span class="text-muted">Rs. {{ number_format($medicineSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">General Procedures</span>
                                <span class="text-muted">Rs. {{ number_format($procedureSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Diagnostics / Lab</span>
                                <span class="text-muted">Rs. {{ number_format($labSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Discounts Applied</span>
                                <span class="text-danger fw-semibold">Rs. {{ number_format($totalDiscount, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2 fw-bold text-dark">
                                <span>Hospital Gross Share</span>
                                <span class="text-success">Rs. {{ number_format($consultationSales, 0) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Block 2: Hospital Revenue -->
                <div class="col-12 col-lg-4">
                    <div class="h-100 p-3 rounded-3 border" style="background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-uppercase font-12 fw-bold text-primary">Hospital Net Revenue</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-graph-up-arrow"></i> Net Profit</span>
                        </div>
                        <h3 class="fw-bold text-primary mb-3">Rs. {{ number_format($netRevenue, 0) }}</h3>
                        <ul class="list-unstyled mb-0 font-13">
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Gross Consultations</span>
                                <span class="fw-bold text-dark">Rs. {{ number_format($consultationSales, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Daily Operational Expenses</span>
                                <span class="text-danger fw-semibold">- Rs. {{ number_format($dailyExpenses, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Staff Payroll Liability</span>
                                <span class="text-muted">Rs. {{ number_format($staffPayroll, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2 fw-bold">
                                <span class="text-dark">Operating Balance</span>
                                <span class="{{ $netRevenue > 0 ? 'text-primary' : 'text-danger' }}">Rs. {{ number_format($netRevenue, 0) }}</span>
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
                                <span class="text-secondary">Total Outstanding Dues</span>
                                <span class="text-danger fw-bold">Rs. {{ number_format($totalDueAmount, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Patient Wallet / Credit</span>
                                <span class="text-success fw-bold">Rs. {{ number_format($totalWalletAmount, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-1 border-bottom border-light-subtle">
                                <span class="text-secondary">Avg Consultation Charge</span>
                                <span class="fw-semibold text-dark">Rs. {{ number_format($avgFeePerVisit, 0) }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2 fw-bold text-dark">
                                <span>Collection Efficiency</span>
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $collectionRate }}%</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bottom 2 Counters Side-by-Side (from original design) -->
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
                            <span class="text-uppercase font-12 fw-semibold text-secondary">Total Appointments Filtered</span>
                            <h4 class="mb-0 fw-bold text-dark mt-1">{{ number_format($totalAppointments) }}</h4>
                        </div>
                        <div class="rounded-circle bg-purple bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 20px;">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== APPOINTMENTS & CONSULTATION BILLING TABLE ========== -->
    <div class="card radius-10 border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-primary"></i> Consultation Payments &amp; Appointments
                </h5>
                <small class="text-muted">Showing {{ $appointments->count() }} of {{ $appointments->total() }} recorded consultations</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('appointment.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-calendar-plus me-1"></i> Manage Appointments
                </a>
            </div>
        </div>
        <div class="card-body p-0">
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
                                    No appointment payments recorded for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
</main>
@endsection