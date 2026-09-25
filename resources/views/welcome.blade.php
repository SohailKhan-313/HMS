<!--start content-->
@extends('layout.master')
@section('content')
<main class="page-content">
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4">
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Patients</p>
                        <h4 class="my-1">1,245</h4>
                        <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 12 new this week</p>
                    </div>
                    <div class="widget-icon-large bg-gradient-purple text-white ms-auto"><i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Available Beds</p>
                        <h4 class="my-1">45</h4>
                        <p class="mb-0 font-13 text-warning"><i class="bi bi-caret-down-fill"></i> 156 Total Capacity</p>
                    </div>
                    <div class="widget-icon-large bg-gradient-success text-white ms-auto"><i class="bi bi-hospital"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Doctors On Duty</p>
                        <h4 class="my-1">28</h4>
                        <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 5 departments</p>
                    </div>
                    <div class="widget-icon-large bg-gradient-danger text-white ms-auto"><i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Today's Appointments</p>
                        <h4 class="my-1">38</h4>
                        <p class="mb-0 font-13 text-info"><i class="bi bi-clock"></i> 12 pending</p>
                    </div>
                    <div class="widget-icon-large bg-gradient-info text-white ms-auto"><i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->

<div class="row">
    <div class="col-12 col-lg-8 col-xl-8 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="row row-cols-1 row-cols-lg-2 g-3 align-items-center pb-3">
                    <div class="col">
                        <h5 class="mb-0">Patient Admissions</h5>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-sm-end gap-3 cursor-pointer">
                            <div class="font-13"><i class="bi bi-circle-fill text-primary"></i><span class="ms-2">This Week</span></div>
                            <div class="font-13"><i class="bi bi-circle-fill text-success"></i><span class="ms-2">Last Week</span></div>
                        </div>
                    </div>
                </div>
                <div id="chart1"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 col-xl-4 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header bg-transparent">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Bed Occupancy</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart2"></div>
            </div>
            <ul class="list-group list-group-flush mb-0">
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-top">General Ward<span class="badge bg-primary badge-pill">45/60</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">ICU<span class="badge bg-danger badge-pill">12/15</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Emergency<span class="badge bg-warning badge-pill">8/10</span></li>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">Pediatric<span class="badge bg-success badge-pill">15/20</span></li>
            </ul>
        </div>
    </div>
</div><!--end row-->

<div class="row">
    <div class="col-12 col-lg-6 col-xl-6 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header bg-transparent">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Patient Categories</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-lg-flex align-items-center justify-content-center gap-4">
                    <div id="chart3"></div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-circle-fill text-purple me-1"></i> Inpatients: <span class="me-1">89</span></li>
                        <li class="list-group-item"><i class="bi bi-circle-fill text-info me-1"></i> Outpatients: <span class="me-1">145</span></li>
                        <li class="list-group-item"><i class="bi bi-circle-fill text-pink me-1"></i> Emergency: <span class="me-1">12</span></li>
                        <li class="list-group-item"><i class="bi bi-circle-fill text-success me-1"></i> ICU: <span class="me-1">8</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-6 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="row row-cols-1 row-cols-lg-2 g-3 align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Department Workload</h5>
                    </div>
                </div>
                <div id="chart4"></div>
            </div>
        </div>
    </div>
</div><!--end row-->

<div class="row">
    <div class="col-12 col-lg-6 col-xl-4 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header bg-transparent">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Department Statistics</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="categories">
                    <div class="progress-wrapper">
                        <p class="mb-2">Cardiology <span class="float-end">75%</span></p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-purple" role="progressbar" style="width: 75%;"></div>
                        </div>
                    </div>
                    <div class="my-3 border-top"></div>
                    <div class="progress-wrapper">
                        <p class="mb-2">Neurology <span class="float-end">60%</span></p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 60%;"></div>
                        </div>
                    </div>
                    <div class="my-3 border-top"></div>
                    <div class="progress-wrapper">
                        <p class="mb-2">Pediatrics <span class="float-end">45%</span></p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 45%;"></div>
                        </div>
                    </div>
                    <div class="my-3 border-top"></div>
                    <div class="progress-wrapper">
                        <p class="mb-2">Orthopedics <span class="float-end">80%</span></p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 80%;"></div>
                        </div>
                    </div>
                    <div class="my-3 border-top"></div>
                    <div class="progress-wrapper">
                        <p class="mb-2">Emergency <span class="float-end">90%</span></p>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 90%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6 col-xl-4 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header bg-transparent">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Today's Doctors</h5>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="best-product p-2 mb-3">
                    <div class="best-product-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-box border">
                                <img src="assets/images/avatars/avatar-1.png" alt="">
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">Dr. Sarah Johnson</h6>
                                <p class="mb-0 font-13">Cardiology</p>
                            </div>
                            <div class="sales-count ms-auto">
                                <p class="mb-0 badge bg-success">Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="best-product-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-box border">
                                <img src="assets/images/avatars/avatar-2.png" alt="">
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">Dr. Michael Chen</h6>
                                <p class="mb-0 font-13">Neurology</p>
                            </div>
                            <div class="sales-count ms-auto">
                                <p class="mb-0 badge bg-success">Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="best-product-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-box border">
                                <img src="assets/images/avatars/avatar-3.png" alt="">
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">Dr. Emily Williams</h6>
                                <p class="mb-0 font-13">Pediatrics</p>
                            </div>
                            <div class="sales-count ms-auto">
                                <p class="mb-0 badge bg-warning">In Surgery</p>
                            </div>
                        </div>
                    </div>
                    <div class="best-product-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-box border">
                                <img src="assets/images/avatars/avatar-4.png" alt="">
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">Dr. James Wilson</h6>
                                <p class="mb-0 font-13">Orthopedics</p>
                            </div>
                            <div class="sales-count ms-auto">
                                <p class="mb-0 badge bg-success">Available</p>
                            </div>
                        </div>
                    </div>
                    <div class="best-product-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-box border">
                                <img src="assets/images/avatars/avatar-5.png" alt="">
                            </div>
                            <div class="product-info">
                                <h6 class="product-name mb-1">Dr. Robert Brown</h6>
                                <p class="mb-0 font-13">Emergency</p>
                            </div>
                            <div class="sales-count ms-auto">
                                <p class="mb-0 badge bg-danger">Emergency</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-12 col-xl-4 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header bg-transparent">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Recent Patients</h5>
                    </div>
                </div>
            </div>
            <div class="top-sellers-list p-2 mb-3">
                <div class="d-flex align-items-center gap-3 sellers-list-item">
                    <img src="assets/images/avatars/avatar-6.png" class="rounded-circle" width="50" height="50" alt="">
                    <div>
                        <h6 class="mb-1">John Smith</h6>
                        <p class="mb-0 font-13">Patient #P00124</p>
                        <small class="text-success">Cardiology</small>
                    </div>
                    <div class="ms-auto">
                        <p class="mb-0 badge bg-primary">Admitted</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 sellers-list-item">
                    <img src="assets/images/avatars/avatar-7.png" class="rounded-circle" width="50" height="50" alt="">
                    <div>
                        <h6 class="mb-1">Maria Garcia</h6>
                        <p class="mb-0 font-13">Patient #P00125</p>
                        <small class="text-warning">Observation</small>
                    </div>
                    <div class="ms-auto">
                        <p class="mb-0 badge bg-warning">Pending</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 sellers-list-item">
                    <img src="assets/images/avatars/avatar-8.png" class="rounded-circle" width="50" height="50" alt="">
                    <div>
                        <h6 class="mb-1">David Lee</h6>
                        <p class="mb-0 font-13">Patient #P00126</p>
                        <small class="text-info">X-Ray</small>
                    </div>
                    <div class="ms-auto">
                        <p class="mb-0 badge bg-success">Discharged</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 sellers-list-item">
                    <img src="assets/images/avatars/avatar-9.png" class="rounded-circle" width="50" height="50" alt="">
                    <div>
                        <h6 class="mb-1">Lisa Anderson</h6>
                        <p class="mb-0 font-13">Patient #P00127</p>
                        <small class="text-danger">Emergency</small>
                    </div>
                    <div class="ms-auto">
                        <p class="mb-0 badge bg-danger">Critical</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 sellers-list-item">
                    <img src="assets/images/avatars/avatar-10.png" class="rounded-circle" width="50" height="50" alt="">
                    <div>
                        <h6 class="mb-1">James Wilson</h6>
                        <p class="mb-0 font-13">Patient #P00128</p>
                        <small class="text-success">Recovery</small>
                    </div>
                    <div class="ms-auto">
                        <p class="mb-0 badge bg-primary">Admitted</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->

<div class="card radius-10">
    <div class="card-header bg-transparent">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h5 class="mb-0">Today's Appointments</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#ID</th>
                        <th>Patient Name</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#APT001</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-info">
                                    <h6 class="product-name mb-1">Robert Johnson</h6>
                                </div>
                            </div>
                        </td>
                        <td>Dr. Sarah Johnson</td>
                        <td>Cardiology</td>
                        <td>09:00 AM</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye-fill"></i></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#APT002</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-info">
                                    <h6 class="product-name mb-1">Mary Williams</h6>
                                </div>
                            </div>
                        </td>
                        <td>Dr. Michael Chen</td>
                        <td>Neurology</td>
                        <td>10:30 AM</td>
                        <td><span class="badge bg-warning">Waiting</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye-fill"></i></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#APT003</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-info">
                                    <h6 class="product-name mb-1">Thomas Brown</h6>
                                </div>
                            </div>
                        </td>
                        <td>Dr. Emily Williams</td>
                        <td>Pediatrics</td>
                        <td>11:45 AM</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye-fill"></i></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#APT004</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-info">
                                    <h6 class="product-name mb-1">Patricia Davis</h6>
                                </div>
                            </div>
                        </td>
                        <td>Dr. James Wilson</td>
                        <td>Orthopedics</td>
                        <td>02:00 PM</td>
                        <td><span class="badge bg-danger">Cancelled</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye-fill"></i></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>#APT005</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-info">
                                    <h6 class="product-name mb-1">Joseph Martinez</h6>
                                </div>
                            </div>
                        </td>
                        <td>Dr. Robert Brown</td>
                        <td>Emergency</td>
                        <td>03:30 PM</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye-fill"></i></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>
@endsection