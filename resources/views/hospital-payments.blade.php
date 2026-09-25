@extends('layout.master')
@section('content')

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Google Font: Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600&display=swap" rel="stylesheet">

<style>
    /* Base styles (same as provided) */
    body {
        background: #f1f5f9;
        font-family: 'helvetica', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }
    .history-card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 35px -8px rgba(173, 44, 44, 0.34);
        overflow: hidden;
        background: white;
        margin-bottom: 2rem;
    }
    .card-header {
        background-color: white;
        border-bottom: 1px solid #e9eef2;
        padding: 1.75rem 2rem 1rem 2rem;
    }
    .card-header h2 {
        font-weight: 600;
        font-size: 2rem;
        color: #0b1b2f;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .card-header h2 i {
        font-size: 2rem;
        color: #2a7de1;
        background: #eef4fe;
        padding: 0.5rem;
        border-radius: 14px;
    }
    .table thead th {
        color: #4a5f7a;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom-width: 1.5px;
        border-bottom-color: #dfe7ef;
        padding: 1.1rem 0.8rem 0.9rem 0.8rem;
        background-color: white;
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
    .action-stack {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        align-items: flex-start;
    }
    .action-btn-sm {
        background: white;
        border: 1px solid #d5e0ec;
        color: #2f5681;
        border-radius: 30px;
        padding: 0.2rem 1rem;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
        transition: 0.1s;
        text-decoration: none;
    }
    .action-btn-sm i {
        font-size: 0.8rem;
    }
    .action-btn-sm:hover {
        background: #e3ecf5;
        border-color: #b0c6dd;
    }
    .date-pill {
        background: #f4f7fb;
        padding: 0.4rem 1.2rem;
        border-radius: 40px;
        font-size: 0.95rem;
        font-weight: 500;
        color: #1f3a5e;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #d5e0ec;
    }
    .btn-outline-secondary-pill {
        background: white;
        border: 1px solid #d5e0ec;
        color: #2f5681;
        border-radius: 40px;
        padding: 0.4rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.15s;
    }
    .btn-outline-secondary-pill:hover {
        background: #e3ecf5;
        border-color: #b0c6dd;
    }
    .btn-primary-pill {
        background: #2a7de1;
        border: 1px solid #2a7de1;
        color: white;
        border-radius: 40px;
        padding: 0.4rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.15s;
    }
    .btn-primary-pill:hover {
        background: #1d5bbf;
        border-color: #1d5bbf;
    }
    .no-data-row td {
        text-align: center;
        padding: 3rem 1rem;
        color: #7589a2;
        font-style: italic;
        font-size: 1rem;
    }
    /* New styles for summary blocks */
    .stat-block {
        background: #64acbe;
        border-radius: 16px;
        padding: 1.2rem;
        height: 100%;
    }
    .stat-title {
        font-size: 1rem;
        font-weight: 600;
        color: #4a5f7a;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.75rem;
    }
    .stat-main {
        font-size: 2rem;
        font-weight: 600;
        color: #0b1b2f;
        margin-bottom: 0.5rem;
    }
    .stat-breakdown {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
        color: #1f2c41;
    }
    .stat-breakdown li {
        display: flex;
        justify-content: space-between;
        padding: 0.2rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .stat-breakdown li:last-child {
        border-bottom: none;
    }
</style>
<main class="page-content">
<div class="container-fluid px-4">
    <!-- ========== SUMMARY CARD (from first image) ========== -->
    <div class="history-card">
        <div class="card-header">
            <h2>
                <i class="bi bi-calendar-plus"></i>
                Create Appointment
            </h2>
        </div>
        <div class="card-body p-4">
            <!-- Date selection row -->
            <div class="d-flex flex-wrap gap-3 mb-4">
                <span class="date-pill"><i class="bi bi-calendar"></i> Start: 05/20/2025</span>
                <span class="date-pill"><i class="bi bi-calendar"></i> End: —</span>
                <span class="date-pill"><i class="bi bi-clock"></i> Time: 12:00 am</span>
                <span class="date-pill"><i class="bi bi-sliders2"></i> Range: —</span>
            </div>

            <!-- Stats grid -->
            <div class="row g-4">
                <!-- Total Sales -->
                <div class="col-md-4">
                    <div class="stat-block">
                        <div class="stat-title">Total Sales</div>
                        <div class="stat-main">Rs. 4,000</div>
                        <ul class="stat-breakdown">
                            <li><span>Consultation</span> <span>4,000 ₦</span></li>
                            <li><span>Medicines</span> <span>0 ₦</span></li>
                            <li><span>General</span> <span>0 ₦</span></li>
                            <li><span>Procedures</span> <span>0 ₦</span></li>
                            <li><span>Lab Tests</span> <span>0 ₦</span></li>
                            <li><span>Tot. Disc</span> <span>0 ₦</span></li>
                            <li><span>Hosp Disc</span> <span>0 ₦</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Hospital Revenue -->
                <div class="col-md-4">
                    <div class="stat-block">
                        <div class="stat-title">Hospital Revenue</div>
                        <div class="stat-main">Rs. 2,800</div>
                        <ul class="stat-breakdown">
                            <li><span>Daily Expense</span> <span>0 ₦</span></li>
                            <li><span>Net Revenue</span> <span>2,800 ₦</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="col-md-4">
                    <div class="stat-block">
                        <div class="stat-title">Payment Methods</div>
                        <div class="stat-main">Rs. 2,000</div>
                        <ul class="stat-breakdown">
                            <li><span>EasyPaisa! Sales</span> <span>0 ₦</span></li>
                            <li><span>JazCash! Sales</span> <span>0 ₦</span></li>
                            <li><span>Card</span> <span>110 ₦</span></li>
                            <li><span>Other Sales</span> <span>0 ₦</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Total Patients & Total Appointments (side by side) -->
                <div class="col-md-6">
                    <div class="stat-block d-flex flex-column h-100">
                        <div class="stat-title">Total Patients</div>
                        <div class="stat-main">2</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-block d-flex flex-column h-100">
                        <div class="stat-title">Total Appointments</div>
                        <div class="stat-main">2</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== APPOINTMENTS TABLE (from second image) ========== -->
    <div class="history-card">
        <div class="card-header">
            <h2>
                <i class="bi bi-calendar-check"></i>
                Today's Appointments
            </h2>
        </div>
        <div class="card-body p-4">
            <!-- Optional toolbar (like reload/print) could go here, but we keep it minimal to match the image -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Appointment Id</th>
                            <th>Appointment Info</th>
                            <th>Patient Name</th>
                            <th>Patient Phone No</th>
                            <th>Total Expense</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 from image -->
                        <tr>
                            <td><span class="badge-cnic">64409</span></td>
                            <td>20/05/2025 11:15 AM</td>
                            <td>haseeb</td>
                            <td><span class="phone-pill">925632478357</span></td>
                            <td class="amount">2000</td>
                            <td>
                                <a href="#" class="action-btn-sm">
                                    View Details <i class="bi bi-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- Row 2 from image -->
                        <tr>
                            <td><span class="badge-cnic">64433</span></td>
                            <td>20/05/2025 12:45 PM</td>
                            <td>Barrera</td>
                            <td><span class="phone-pill">925765346245</span></td>
                            <td class="amount">2000</td>
                            <td>
                                <a href="#" class="action-btn-sm">
                                    View Details <i class="bi bi-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Optional footer note -->
            <div class="d-flex gap-3 mt-3" style="color:#7589a2; font-size:0.85rem;">
                <i class="bi bi-info-circle"></i> Showing 2 appointments
            </div>
        </div>
    </div>

  
            <!-- Subtle footer -->
            <div class="d-flex gap-3 mt-3" style="color:#7589a2; font-size:0.85rem;">
                <i class="bi bi-info-circle"></i> 0 records · Use "Add Expense" to create an entry
            </div>
        </div>
    </div>
</div>
</main>
@endsection