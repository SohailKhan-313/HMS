<!doctype html>
<html lang="en" class="minimal-theme">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
  <!--plugins-->
  <link href="{{asset('/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
  <link href="{{asset('/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
  <link href="{{asset('/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
  <link href="{{asset('/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/bootstrap-extended.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/style.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/icons.css')}}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <!-- loader-->
  <link href="{{ asset('/css/pace.min.css') }}" rel="stylesheet" />

  <!--Theme Styles-->
  <link href="{{asset('/css/dark-theme.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/light-theme.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/semi-dark.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/header-colors.css')}}" rel="stylesheet" />

  <title>HMS</title>
</head>

<body>


  <!--start wrapper-->
  <div class="wrapper">
    <!--start top header-->
      <header class="top-header">        
        <nav class="navbar navbar-expand align-items-center px-3">
          <div class="mobile-toggle-icon d-xl-none me-3 cursor-pointer">
              <i class="bi bi-list fs-3"></i>
          </div>
          
          <div class="top-navbar d-none d-lg-block">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-light text-primary border rounded-pill px-3 py-2 fw-semibold fs-6">
                <i class="bi bi-hospital me-1"></i> Hospital Management System
              </span>
            </div>
          </div>

          <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center flex-row gap-2">
              <!-- WhatsApp Contact -->
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-center rounded-circle shadow-sm" 
                   href="https://wa.me/923470232059" 
                   target="_blank" 
                   title="Chat on WhatsApp (03470232059)" 
                   style="width: 38px; height: 38px; background-color: #e8f9ee; border: 1px solid #a3e9b7;">
                  <i class="bi bi-whatsapp fs-5 text-success"></i>
                </a>
              </li>

              <!-- Email Contact -->
              <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-center rounded-circle shadow-sm" 
                   href="mailto:skpattan850911@gmail.com" 
                   title="Send Email (skpattan850911@gmail.com)" 
                   style="width: 38px; height: 38px; background-color: #ebf3ff; border: 1px solid #bfdbfe;">
                  <i class="bi bi-envelope-fill fs-5 text-primary"></i>
                </a>
              </li>

              <!-- Admin Profile -->
              <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret d-flex align-items-center gap-2 p-1" href="#" data-bs-toggle="dropdown">
                  <div class="user-setting d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 15px;">
                      <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="user-name d-none d-sm-block text-start">
                      <div class="fw-bold text-dark lh-1" style="font-size: 13px;">Administrator</div>
                      <small class="text-secondary" style="font-size: 11px;">Hospital Admin Desk</small>
                    </div>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                  <li class="px-3 py-2 border-bottom">
                    <h6 class="mb-0 fw-bold">Hospital Desk</h6>
                    <small class="text-muted">HMS System Active</small>
                  </li>
                  <li><a class="dropdown-item py-2" href="{{ route('welcome') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</a></li>
                  <li><a class="dropdown-item py-2" href="{{ route('appointment.index') }}"><i class="bi bi-calendar-check me-2 text-success"></i>Appointments</a></li>
                  <li><a class="dropdown-item py-2" href="{{ route('patients.index') }}"><i class="bi bi-person-lines-fill me-2 text-info"></i>Patients Directory</a></li>
                  <li><a class="dropdown-item py-2" href="{{ route('doctors.index') }}"><i class="bi bi-heart-pulse me-2 text-danger"></i>Doctors Roster</a></li>
                  <li><a class="dropdown-item py-2" href="{{ route('expenses.index') }}"><i class="bi bi-wallet2 me-2 text-warning"></i>Daily Expenses</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
       <!--end top header-->

        <!--start sidebar -->
        <!--start sidebar -->
    <aside class="sidebar-wrapper" data-simplebar="true">
      <div class="sidebar-header">
        <div>
          <i class="logo-icon bi bi-bag-plus"></i>
        </div>
        <div>
          <h4 class="logo-text">H M S</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-chevron-double-left"></i>
        </div>
      </div>
      <!--navigation-->
      <ul class="metismenu" id="menu">

        <!-- Dashboard  -->
        <li class="{{ request()->routeIs('welcome') ? 'mm-active' : '' }}">
          <a href="{{ route('welcome') }}">
            <div class="parent-icon"><i class="bi bi-speedometer2"></i></div>
            <div class="menu-title">Dashboard</div>
          </a>
        </li>

        <!-- Appointments -->
        <li class="{{ request()->routeIs('appointment.*') ? 'mm-active' : '' }}">
          <a href="{{ route('appointment.index') }}">
            <div class="parent-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="menu-title">Appointments</div>
          </a>
        </li>

        <!-- Doctors -->
        <li class="{{ request()->routeIs('doctors.*') ? 'mm-active' : '' }}">
          <a href="{{ route('doctors.index') }}">
            <div class="parent-icon"><i class="bi bi-person-badge"></i></div>
            <div class="menu-title">Doctors</div>
          </a>
        </li>

        <!-- Staff -->
        <li class="{{ request()->routeIs('staff.*') ? 'mm-active' : '' }}">
          <a href="{{ route('staff.index') }}">
            <div class="parent-icon"><i class="bi bi-people"></i></div>
            <div class="menu-title">Staff Members</div>
          </a>
        </li>

        <!-- Patient History -->
        <li class="{{ request()->routeIs('patients.*') ? 'mm-active' : '' }}">
          <a href="{{ route('patients.index') }}">
            <div class="parent-icon"><i class="bi bi-person-lines-fill"></i></div>
            <div class="menu-title">Patients & History</div>
          </a>
        </li>

        <!-- Hospital Payments -->
        <li class="{{ request()->routeIs('hospital-payments') ? 'mm-active' : '' }}">
          <a href="{{ route('hospital-payments') }}">
            <div class="parent-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="menu-title">Hospital Payments</div>
          </a>
        </li>

        <!-- Daily Expense -->
        @php
          $isExpenseActive = request()->routeIs('expenses.*') || request()->routeIs('category.*');
        @endphp
        <li class="{{ $isExpenseActive ? 'mm-active' : '' }}">
          <a href="javascript:;" class="has-arrow" aria-expanded="{{ $isExpenseActive ? 'true' : 'false' }}">
            <div class="parent-icon"><i class="bi bi-receipt"></i></div>
            <div class="menu-title">Daily Expenses</div>
          </a>
          <ul class="{{ $isExpenseActive ? 'mm-collapse mm-show' : 'mm-collapse' }}">
            <li class="{{ request()->routeIs('expenses.*') ? 'mm-active' : '' }}">
              <a href="{{ route('expenses.index') }}"><i class="bi bi-arrow-right-short"></i>Expenses List</a>
            </li>
            <li class="{{ request()->routeIs('category.*') ? 'mm-active' : '' }}">
              <a href="{{ route('category.index') }}"><i class="bi bi-arrow-right-short"></i>Expense Categories</a>
            </li>
          </ul>
        </li>

        <li class="menu-label text-uppercase text-secondary mt-2 mb-1 px-3 font-11">Reports & Downloads</li>

        <!-- PDF Export Quick Center -->
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bi bi-file-earmark-pdf text-danger"></i></div>
            <div class="menu-title">PDF Reports</div>
          </a>
          <ul>
            <li>
              <a href="{{ route('reports.patients.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Patients Directory (PDF)</a>
            </li>
            <li>
              <a href="{{ route('reports.appointments.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Appointments List (PDF)</a>
            </li>
            <li>
              <a href="{{ route('reports.doctors.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Doctors Roster (PDF)</a>
            </li>
            <li>
              <a href="{{ route('reports.staff.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Staff Directory (PDF)</a>
            </li>
            <li>
              <a href="{{ route('reports.expenses.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Expenses Audit (PDF)</a>
            </li>
          </ul>
        </li>

      </ul>


      <!--end navigation-->
    </aside>
    <!--end sidebar -->
       <!--end sidebar -->

       <!--start content-->
         @yield('content')
       <!--end page main-->

       <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
       <!--end overlay-->

       <!--Start Back To Top Button-->
		     <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
       <!--End Back To Top Button-->

       <!--start switcher-->
       <div class="switcher-body">
        <button class="btn btn-primary btn-switcher shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><i class="bi bi-paint-bucket me-0"></i></button>
        <div class="offcanvas offcanvas-end shadow border-start-0 p-2" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling">
          <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Theme Customizer</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
          </div>
          <div class="offcanvas-body">
            <h6 class="mb-0">Theme Variation</h6>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="LightTheme" value="option1">
              <label class="form-check-label" for="LightTheme">Light</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="DarkTheme" value="option2">
              <label class="form-check-label" for="DarkTheme">Dark</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="SemiDarkTheme" value="option3">
              <label class="form-check-label" for="SemiDarkTheme">Semi Dark</label>
            </div>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="MinimalTheme" value="option3" checked>
              <label class="form-check-label" for="MinimalTheme">Minimal Theme</label>
            </div>
            <hr/>
            <h6 class="mb-0">Header Colors</h6>
            <hr/>
            <div class="header-colors-indigators">
              <div class="row row-cols-auto g-3">
                <div class="col">
                  <div class="indigator headercolor1" id="headercolor1"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor2" id="headercolor2"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor3" id="headercolor3"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor4" id="headercolor4"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor5" id="headercolor5"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor6" id="headercolor6"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor7" id="headercolor7"></div>
                </div>
                <div class="col">
                  <div class="indigator headercolor8" id="headercolor8"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
       </div>
       <!--end switcher-->

  </div>
  <!--end wrapper-->


  <!-- Bootstrap bundle JS -->
  <script src="{{asset('/js/bootstrap.bundle.min.js')}}"></script>
  <!--plugins-->
  <script src="{{asset('/js/jquery.min.js')}}"></script>
  <script src="{{asset('/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{asset('/plugins/metismenu/js/metisMenu.min.js')}}"></script>
  <script src="{{asset('/plugins/easyPieChart/jquery.easypiechart.js')}}"></script>
  <script src="{{asset('/plugins/peity/jquery.peity.min.js')}}"></script>
  <script src="{{asset('/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('/js/pace.min.js')}}"></script>
  <script src="{{asset('/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
  <script src="{{asset('/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{asset('/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
  <!--app-->
  <script src="{{ asset('/js/app.js') }}"></script>
  @if(request()->routeIs('welcome'))
  <script src="{{ asset('/js/index.js') }}"></script>
  @endif

  <script>
    if (document.querySelector('.best-product')) {
        new PerfectScrollbar('.best-product');
    }
    if (document.querySelector('.top-sellers-list')) {
        new PerfectScrollbar('.top-sellers-list');
    }
  </script>

  @stack('scripts')

</body>

</html>