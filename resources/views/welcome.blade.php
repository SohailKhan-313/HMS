@extends('layout.master')

@section('content')
<main class="page-content">

    <!-- Hospital Dashboard Welcome & Quick Actions Banner -->
    <div class="card bg-white radius-10 border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill fw-semibold">
                            <i class="bi bi-hospital me-1"></i> Hospital Operations
                        </span>
                        <span class="text-secondary font-13"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
                    </div>
                    <h4 class="mb-0 fw-bold text-dark">Hospital Management Dashboard</h4>
                    <p class="mb-0 text-muted font-13">Real-time overview of patients, daily appointments, medical roster, and clinical finances.</p>
                </div>
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <button type="button" class="btn btn-dark btn-sm d-flex align-items-center gap-1 shadow-sm" onclick="if(typeof window.toggleHMSChatbot === 'function'){ window.toggleHMSChatbot(true); } else { document.getElementById('hms-chatbot-launcher')?.click(); }">
                        <i class="bi bi-robot text-info"></i> AI Assistant
                    </button>
                    @if(!auth()->check() || auth()->user()->canAccessAppointments())
                    <a href="{{ route('appointment.index') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-calendar-plus"></i> Appointments
                    </a>
                    @endif
                    @if(!auth()->check() || auth()->user()->canAccessPatients())
                    <a href="{{ route('patients.index') }}" class="btn btn-success btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-person-plus"></i> Patients Directory
                    </a>
                    @endif
                    @if(!auth()->check() || auth()->user()->canAccessPayments())
                    <a href="{{ route('hospital-payments') }}" class="btn btn-info btn-sm d-flex align-items-center gap-1 shadow-sm text-white">
                        <i class="bi bi-cash-stack"></i> Payments
                    </a>
                    @endif
                    @if(auth()->check() && auth()->user()->canManageUsers())
                    <a href="{{ route('users.index') }}" class="btn btn-warning btn-sm d-flex align-items-center gap-1 shadow-sm text-dark">
                        <i class="bi bi-shield-lock"></i> Users & Roles
                    </a>
                    @endif
                    @if(!auth()->check() || auth()->user()->canAccessAppointments())
                    <a href="{{ route('reports.appointments.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-file-earmark-pdf"></i> Print Registry (PDF)
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Main KPI Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4 g-3 mb-4">
        <!-- Total Patients -->
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-1 text-secondary font-13 text-uppercase fw-semibold">Total Patients</p>
                            <h3 class="mb-1 fw-bold text-dark">{{ number_format($totalPatients) }}</h3>
                            <p class="mb-0 font-12 text-muted">
                                <span class="text-danger fw-semibold">Due: Rs. {{ number_format($totalDueAmount, 0) }}</span> · 
                                <span class="text-success fw-semibold">Credit: Rs. {{ number_format($totalWalletAmount, 0) }}</span>
                            </p>
                        </div>
                        <div class="widget-icon-large bg-gradient-purple text-white ms-auto shadow-sm">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    @if(!auth()->check() || auth()->user()->canAccessPatients())
                    <a href="{{ route('patients.index') }}" class="font-12 text-primary fw-semibold text-decoration-none">
                        View Patients Directory <i class="bi bi-arrow-right"></i>
                    </a>
                    @else
                    <span class="font-12 text-muted fw-semibold">Patient Records</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-1 text-secondary font-13 text-uppercase fw-semibold">Today's Appointments</p>
                            <h3 class="mb-1 fw-bold text-dark">{{ number_format($todayAppointments) }}</h3>
                            <p class="mb-0 font-12 text-muted">
                                <span class="badge bg-warning text-dark">{{ $pendingAppointments }} Pending</span>
                                <span class="badge bg-success ms-1">{{ $completedAppointments }} Done</span>
                            </p>
                        </div>
                        <div class="widget-icon-large bg-gradient-info text-white ms-auto shadow-sm">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    @if(!auth()->check() || auth()->user()->canAccessAppointments())
                    <a href="{{ route('appointment.index') }}" class="font-12 text-info fw-semibold text-decoration-none">
                        Manage Appointments <i class="bi bi-arrow-right"></i>
                    </a>
                    @else
                    <span class="font-12 text-muted fw-semibold">Appointment Schedule</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Doctors Roster -->
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-1 text-secondary font-13 text-uppercase fw-semibold">Medical Specialists</p>
                            <h3 class="mb-1 fw-bold text-dark">{{ number_format($totalDoctors) }}</h3>
                            <p class="mb-0 font-12 text-success fw-semibold">
                                <i class="bi bi-cash-coin me-1"></i> Consultations: Rs. {{ number_format($totalRevenue, 0) }}
                            </p>
                        </div>
                        <div class="widget-icon-large bg-gradient-danger text-white ms-auto shadow-sm">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    <a href="{{ route('doctors.index') }}" class="font-12 text-danger fw-semibold text-decoration-none">
                        View Doctors Roster <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Staff & Operations -->
        <div class="col">
            <div class="card radius-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-1 text-secondary font-13 text-uppercase fw-semibold">Hospital Staff</p>
                            <h3 class="mb-1 fw-bold text-dark">{{ number_format($totalStaff) }}</h3>
                            <p class="mb-0 font-12 text-muted">
                                <span class="text-danger fw-semibold">Total Expenses: Rs. {{ number_format($totalExpenses, 0) }}</span>
                            </p>
                        </div>
                        <div class="widget-icon-large bg-gradient-success text-white ms-auto shadow-sm">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                    @if(!auth()->check() || auth()->user()->canManageStaff())
                    <a href="{{ route('staff.index') }}" class="font-12 text-success fw-semibold text-decoration-none">
                        View Staff Directory <i class="bi bi-arrow-right"></i>
                    </a>
                    @elseif(auth()->check() && auth()->user()->canAccessExpenses())
                    <a href="{{ route('expenses.index') }}" class="font-12 text-success fw-semibold text-decoration-none">
                        View Daily Expenses <i class="bi bi-arrow-right"></i>
                    </a>
                    @else
                    <span class="font-12 text-muted fw-semibold">Hospital Operations</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- AI Medical Assistant Quick Console on Dashboard -->
    <div class="card border-0 shadow-sm radius-10 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="card-body p-3 p-md-4 text-white">
            <div class="row align-items-center g-3">
                <div class="col-12 col-lg-7">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 px-2 py-1 rounded-pill font-11">
                            <i class="bi bi-cpu me-1"></i> HMS Intelligence
                        </span>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill font-11">
                            <i class="bi bi-check-circle me-1"></i> Live Records &amp; Medical Triage
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1 text-white">AI Hospital Assistant &amp; Medical Suggestions</h5>
                    <p class="text-white-50 font-13 mb-0">Ask about available doctors, consultation fees, patient dues, or describe symptoms for preliminary triage and doctor recommendations.</p>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <button type="button" class="btn btn-outline-light btn-sm rounded-pill font-12" onclick="openChatWithPrompt('Show available doctors and consultation fees')">
                            👨‍⚕️ Available Doctors
                        </button>
                        <button type="button" class="btn btn-outline-light btn-sm rounded-pill font-12" onclick="openChatWithPrompt('What are today appointments?')">
                            📅 Today's Schedule
                        </button>
                        <button type="button" class="btn btn-info btn-sm rounded-pill font-12 fw-semibold shadow-sm" onclick="if(typeof window.toggleHMSChatbot === 'function'){ window.toggleHMSChatbot(true); } else { document.getElementById('hms-chatbot-launcher')?.click(); }">
                            <i class="bi bi-chat-dots-fill me-1"></i> Open AI Assistant
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Row -->
    <div class="row g-3 mb-4">
        <!-- 7-Day Trend Chart -->
        <div class="col-12 col-lg-8 d-flex">
            <div class="card radius-10 border-0 shadow-sm w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 border-bottom mb-3">
                        <div>
                            <h5 class="mb-0 fw-bold">Appointment Trends (Past 7 Days)</h5>
                            <small class="text-muted">Daily registered visits vs. completed consultations</small>
                        </div>
                        <div class="d-flex align-items-center gap-3 font-13">
                            <div><i class="bi bi-circle-fill text-primary me-1"></i> Total Bookings</div>
                            <div><i class="bi bi-circle-fill text-success me-1"></i> Completed</div>
                        </div>
                    </div>
                    <div id="chart1" style="min-height: 330px;"></div>
                </div>
            </div>
        </div>

        <!-- Appointment Status Breakdown -->
        <div class="col-12 col-lg-4 d-flex">
            <div class="card radius-10 border-0 shadow-sm w-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="mb-0 fw-bold">Appointments Breakdown</h5>
                    <small class="text-muted">Total recorded status distribution</small>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div id="chart2" style="min-height: 230px;"></div>
                    <ul class="list-group list-group-flush mt-3 font-13">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span><i class="bi bi-check-circle-fill text-success me-2"></i> Completed Consultations</span>
                            <span class="badge bg-success rounded-pill">{{ $completedAppointments }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span><i class="bi bi-hourglass-split text-warning me-2"></i> Pending Patient Visits</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $pendingAppointments }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span><i class="bi bi-hospital text-info me-2"></i> Inpatient / Admitted</span>
                            <span class="badge bg-info rounded-pill">{{ $admittedAppointments }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span><i class="bi bi-x-circle-fill text-danger me-2"></i> Cancelled Appointments</span>
                            <span class="badge bg-danger rounded-pill">{{ $cancelledAppointments }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables & Doctor Roster Row -->
    <div class="row g-3 mb-4">
        <!-- Recent Appointments Table -->
        <div class="col-12 col-xl-8">
            <div class="card radius-10 border-0 shadow-sm h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5 class="mb-0 fw-bold">Recent Appointments</h5>
                        <small class="text-muted">Latest patient bookings and clinical schedules</small>
                    </div>
                    <a href="{{ route('appointment.index') }}" class="btn btn-outline-primary btn-sm">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover">
                            <thead class="table-light font-13">
                                <tr>
                                    <th>#APT ID</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th class="text-end pe-3">Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAppointments as $apt)
                                    <tr>
                                        <td class="fw-semibold text-secondary">#APT-{{ str_pad($apt->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 13px;">
                                                    {{ strtoupper(substr($apt->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark lh-sm">{{ $apt->name }}</div>
                                                    <small class="text-muted font-12">{{ $apt->phone ?? 'No phone' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($apt->doctor)
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-person-fill text-primary me-1"></i>{{ $apt->doctor->name }}
                                                </span>
                                            @else
                                                <span class="text-muted font-12">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-13 text-secondary">
                                                <i class="bi bi-clock me-1"></i>{{ $apt->time ?: 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($apt->status == 'Completed')
                                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Completed</span>
                                            @elseif($apt->status == 'Cancelled')
                                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Cancelled</span>
                                            @elseif($apt->status == 'Admitted')
                                                <span class="badge bg-info-subtle text-info border border-info border-opacity-25 px-2 py-1 rounded-pill">Admitted</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning border-opacity-25 px-2 py-1 rounded-pill">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('pdf.appointment', $apt->id) }}" target="_blank" class="btn btn-outline-danger btn-sm p-1 px-2" title="Print Appointment PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                            No appointments scheduled yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctors Roster List -->
        <div class="col-12 col-xl-4">
            <div class="card radius-10 border-0 shadow-sm h-100">
                <div class="card-header bg-transparent d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5 class="mb-0 fw-bold">Active Doctors</h5>
                        <small class="text-muted">Specialists roster & fees</small>
                    </div>
                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-danger btn-sm">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse($doctorsList as $doc)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded border bg-light-subtle">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; font-size: 15px;">
                                        <i class="bi bi-person-heart"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $doc->name }}</h6>
                                        <small class="text-muted font-12">{{ $doc->speciality ?: 'General Physician' }}</small>
                                        @if($doc->pmdc)
                                            <span class="d-block text-secondary font-11">PMDC: {{ $doc->pmdc }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        Rs. {{ number_format($doc->fee, 0) }}
                                    </span>
                                    <div class="font-11 text-muted mt-1">{{ $doc->appointments_count }} visits</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-person-x fs-3 d-block mb-1"></i>
                                No doctors registered yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Expenses Audit Strip -->
    <div class="card radius-10 border-0 shadow-sm">
        <div class="card-header bg-transparent d-flex align-items-center justify-content-between py-3">
            <div>
                <h5 class="mb-0 fw-bold">Recent Daily Expenses</h5>
                <small class="text-muted">Latest operational expenditures and hospital maintenance</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.expenses.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Expenses Audit (PDF)
                </a>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm">
                    Manage Expenses <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light font-13">
                        <tr>
                            <th>#EXP ID</th>
                            <th>Description / Expense Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th class="text-end pe-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentExpenses as $exp)
                            <tr>
                                <td class="fw-semibold text-secondary">#EXP-{{ str_pad($exp->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-bold text-dark">{{ $exp->name }}</td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary border-opacity-25">
                                        <i class="bi bi-tag-fill me-1"></i>{{ $exp->catagory ?: 'General' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-13 text-secondary">
                                        <i class="bi bi-calendar-event me-1"></i>{{ $exp->date }}
                                    </span>
                                </td>
                                <td class="text-end pe-3 fw-bold text-danger">
                                    Rs. {{ number_format($exp->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-receipt fs-3 d-block mb-1"></i>
                                    No hospital expenses recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<script>
    // Provide dynamic data for ApexCharts
    window.hmsAnalytics = {
        trendLabels: {!! json_encode($trendLabels ?? []) !!},
        trendTotal: {!! json_encode($trendTotal ?? []) !!},
        trendCompleted: {!! json_encode($trendCompleted ?? []) !!},
        statusSeries: {!! json_encode($statusSeries ?? [0, 0, 0, 0]) !!}
    };
</script>
@endsection