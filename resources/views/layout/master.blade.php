<!doctype html>
<html lang="en" class="minimal-theme">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
  <!--plugins-->
  <link href="{{asset('/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
  <link href="{{asset('/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
  <link href="{{asset('/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
  <link href="{{asset('/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
  <link href="{{asset('/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/bootstrap-extended.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/style.css')}}" rel="stylesheet" />
  <link href="{{asset('/css/icons.css')}}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Core JS: jQuery & Bootstrap 5 Bundle (loaded in head so all child views have bootstrap and $ ready) -->
  <script src="{{ asset('/js/jquery.min.js') }}"></script>
  <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    if (typeof bootstrap === 'undefined') {
      document.write('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"><\/script>');
    }
  </script>

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
        <nav class="navbar navbar-expand align-items-center justify-content-between px-3">
          <!-- Mobile Sidebar Toggle -->
          <div class="mobile-toggle-icon d-xl-none me-3" style="cursor: pointer;">
            <i class="bi bi-list fs-4 text-dark"></i>
          </div>

          <!-- Quick Navigation Links (Desktop) -->
          <div class="top-navbar d-none d-xl-block">
            <ul class="navbar-nav align-items-center gap-1">
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('welcome') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('welcome') }}">
                  <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
              </li>
              @if(!auth()->check() || auth()->user()->canAccessAppointments())
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('appointment.*') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('appointment.index') }}">
                  <i class="bi bi-calendar-check me-1"></i> Appointments
                </a>
              </li>
              @endif
              @if(!auth()->check() || auth()->user()->isDoctor() || auth()->user()->canManageDoctors() || auth()->user()->isReceptionist())
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('doctors.*') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('doctors.index') }}">
                  <i class="bi bi-person-badge me-1"></i> Doctors
                </a>
              </li>
              @endif
              @if(!auth()->check() || auth()->user()->canAccessPatients())
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('patients.*') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('patients.index') }}">
                  <i class="bi bi-person-lines-fill me-1"></i> Patients
                </a>
              </li>
              @endif
              @if(!auth()->check() || auth()->user()->canAccessPayments())
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('hospital-payments') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('hospital-payments') }}">
                  <i class="bi bi-wallet2 me-1"></i> Payments
                </a>
              </li>
              @endif
              @if(!auth()->check() || auth()->user()->canAccessExpenses())
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('expenses.*') || request()->routeIs('category.*') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('expenses.index') }}">
                  <i class="bi bi-receipt me-1"></i> Expenses
                </a>
              </li>
              @endif
              @if(auth()->check() && auth()->user()->canManageUsers())
              <li class="nav-item">
                <a class="nav-link px-2 {{ request()->routeIs('users.*') ? 'active fw-bold text-primary' : 'text-dark' }}" href="{{ route('users.index') }}">
                  <i class="bi bi-shield-lock me-1"></i> Users & Roles
                </a>
              </li>
              @endif
            </ul>
          </div>

          <!-- Topbar Right Controls: WhatsApp, Email, AI Assistant & Profile -->
          <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center gap-2">
              <!-- WhatsApp Direct Contact Icon -->
              <li class="nav-item">
                <a href="https://wa.me/923470232059?text=Hello%20Hospital%20Management" target="_blank" 
                   class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center shadow-sm p-0" 
                   style="width: 36px; height: 36px;"
                   title="WhatsApp: 03470232059"
                   aria-label="WhatsApp: 03470232059">
                  <i class="bi bi-whatsapp fs-6"></i>
                </a>
              </li>

              <!-- Email Direct Contact Icon -->
              <li class="nav-item">
                <a href="mailto:skpattan850911@gmail.com" 
                   class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm p-0" 
                   style="width: 36px; height: 36px;"
                   title="Email: skpattan850911@gmail.com"
                   aria-label="Email: skpattan850911@gmail.com">
                  <i class="bi bi-envelope-at-fill fs-6"></i>
                </a>
              </li>

              <!-- AI Medical Assistant Launcher Button -->
              <li class="nav-item">
                <button type="button" 
                        class="btn btn-sm btn-dark d-flex align-items-center gap-1 rounded-pill px-2 px-md-3 shadow-sm" 
                        onclick="if(typeof window.toggleHMSChatbot === 'function'){ window.toggleHMSChatbot(true); } else { document.getElementById('hms-chatbot-launcher')?.click(); }" 
                        title="Open Hospital AI Assistant">
                  <i class="bi bi-robot text-info fs-6"></i>
                  <span class="font-12 fw-semibold d-none d-sm-inline">AI Assistant</span>
                </button>
              </li>

              <!-- Hospital User Profile Dropdown & Logout -->
              @auth
              <li class="nav-item dropdown dropdown-large ms-1">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret d-flex align-items-center gap-2 p-1 rounded-pill bg-light border px-2 shadow-sm" href="#" data-bs-toggle="dropdown">
                  <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold font-12" style="width: 32px; height: 32px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                  </div>
                  <div class="d-none d-sm-block text-start lh-1">
                    <span class="font-12 fw-bold text-dark d-block">{{ auth()->user()->name }}</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-10 text-uppercase py-0 px-1">{{ auth()->user()->role }}</span>
                  </div>
                  <i class="bi bi-chevron-down font-11 text-secondary"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 radius-10 p-2" style="min-width: 250px;">
                  <li class="p-2 border-bottom mb-2 bg-light radius-10">
                    <div class="d-flex align-items-center gap-2">
                      <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 16px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                      </div>
                      <div class="overflow-hidden">
                        <h6 class="mb-0 font-13 fw-bold text-dark text-truncate">{{ auth()->user()->name }}</h6>
                        <small class="text-muted font-11 text-truncate d-block">{{ auth()->user()->email }}</small>
                        <span class="badge bg-secondary-subtle text-secondary font-10 text-uppercase mt-1">{{ auth()->user()->role }}</span>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 font-13 radius-10" href="{{ route('welcome') }}">
                      <i class="bi bi-speedometer2 text-primary font-14"></i> Dashboard Overview
                    </a>
                  </li>
                  @if(auth()->user()->canManageUsers())
                  <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 font-13 radius-10" href="{{ route('users.index') }}">
                      <i class="bi bi-shield-lock text-primary font-14"></i> Users & Role Access
                    </a>
                  </li>
                  @endif
                  @if(auth()->user()->canAccessPayments())
                  <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 font-13 radius-10" href="{{ route('hospital-payments') }}">
                      <i class="bi bi-wallet2 text-success font-14"></i> Hospital Payments
                    </a>
                  </li>
                  @endif
                  @if(auth()->user()->canAccessExpenses())
                  <li>
                    <a class="dropdown-item py-2 d-flex align-items-center gap-2 font-13 radius-10" href="{{ route('expenses.index') }}">
                      <i class="bi bi-receipt text-danger font-14"></i> Daily Expenses
                    </a>
                  </li>
                  @endif
                  <li><hr class="dropdown-divider my-2"></li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                      @csrf
                      <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 font-13 radius-10 text-danger border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right font-14"></i> Sign Out / Logout
                      </button>
                    </form>
                  </li>
                </ul>
              </li>
              @else
              <li class="nav-item">
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </a>
              </li>
              @endauth
            </ul>
          </div>
        </nav>
      </header>
      <!--end top header-->
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

        @if(!auth()->check() || auth()->user()->canManageUsers())
        <!-- Users & Role Access (Admin Only) -->
        <li class="{{ request()->routeIs('users.*') ? 'mm-active' : '' }}">
          <a href="{{ route('users.index') }}">
            <div class="parent-icon"><i class="bi bi-shield-lock text-primary"></i></div>
            <div class="menu-title">Users & Roles</div>
          </a>
        </li>
        @endif

        @if(!auth()->check() || auth()->user()->canAccessAppointments())
        <!-- Appointments -->
        <li class="{{ request()->routeIs('appointment.*') ? 'mm-active' : '' }}">
          <a href="{{ route('appointment.index') }}">
            <div class="parent-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="menu-title">Appointments</div>
          </a>
        </li>
        @endif

        @if(!auth()->check() || auth()->user()->isDoctor() || auth()->user()->canManageDoctors() || auth()->user()->isReceptionist())
        <!-- Doctors -->
        <li class="{{ request()->routeIs('doctors.*') ? 'mm-active' : '' }}">
          <a href="{{ route('doctors.index') }}">
            <div class="parent-icon"><i class="bi bi-person-badge"></i></div>
            <div class="menu-title">Doctors</div>
          </a>
        </li>
        @endif

        @if(!auth()->check() || auth()->user()->canManageStaff())
        <!-- Staff -->
        <li class="{{ request()->routeIs('staff.*') ? 'mm-active' : '' }}">
          <a href="{{ route('staff.index') }}">
            <div class="parent-icon"><i class="bi bi-people"></i></div>
            <div class="menu-title">Staff Members</div>
          </a>
        </li>
        @endif

        @if(!auth()->check() || auth()->user()->canAccessPatients())
        <!-- Patient History -->
        <li class="{{ request()->routeIs('patients.*') ? 'mm-active' : '' }}">
          <a href="{{ route('patients.index') }}">
            <div class="parent-icon"><i class="bi bi-person-lines-fill"></i></div>
            <div class="menu-title">Patients & History</div>
          </a>
        </li>
        @endif

        @if(!auth()->check() || auth()->user()->canAccessPayments())
        <!-- Hospital Payments -->
        <li class="{{ request()->routeIs('hospital-payments') ? 'mm-active' : '' }}">
          <a href="{{ route('hospital-payments') }}">
            <div class="parent-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="menu-title">Hospital Payments</div>
          </a>
        </li>
        @endif

        @if(!auth()->check() || auth()->user()->canAccessExpenses())
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
        @endif

        <li class="menu-label text-uppercase text-secondary mt-2 mb-1 px-3 font-11">Reports & Downloads</li>

        <!-- PDF Export Quick Center -->
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="bi bi-file-earmark-pdf text-danger"></i></div>
            <div class="menu-title">PDF Reports</div>
          </a>
          <ul>
            @if(!auth()->check() || auth()->user()->canAccessPatients())
            <li>
              <a href="{{ route('reports.patients.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Patients Directory (PDF)</a>
            </li>
            @endif
            @if(!auth()->check() || auth()->user()->canAccessAppointments())
            <li>
              <a href="{{ route('reports.appointments.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Appointments List (PDF)</a>
            </li>
            @endif
            @if(!auth()->check() || auth()->user()->isDoctor() || auth()->user()->canManageDoctors())
            <li>
              <a href="{{ route('reports.doctors.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Doctors Roster (PDF)</a>
            </li>
            @endif
            @if(!auth()->check() || auth()->user()->canManageStaff())
            <li>
              <a href="{{ route('reports.staff.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Staff Directory (PDF)</a>
            </li>
            @endif
            @if(!auth()->check() || auth()->user()->canAccessExpenses())
            <li>
              <a href="{{ route('reports.expenses.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Expenses Audit (PDF)</a>
            </li>
            @endif
            @if(!auth()->check() || auth()->user()->canAccessPayments())
            <li>
              <a href="{{ route('reports.payments.pdf') }}" target="_blank"><i class="bi bi-arrow-right-short"></i>Payments Audit (PDF)</a>
            </li>
            @endif
          </ul>
        </li>

      </ul>

      <!--end navigation-->
    </aside>
    <!--end sidebar -->

    <!-- Global Flash Alerts -->
    @if(session('success'))
    <div class="container-fluid pt-3 px-4">
      <div class="alert alert-success border-0 bg-success alert-dismissible fade show text-white shadow-sm mb-0">
        <div class="d-flex align-items-center">
          <i class="bi bi-check-circle-fill fs-5 me-2"></i>
          <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    @endif
    @if(session('error'))
    <div class="container-fluid pt-3 px-4">
      <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show text-white shadow-sm mb-0">
        <div class="d-flex align-items-center">
          <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
          <div>{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
    @endif

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


  <!--plugins-->
  <script src="{{asset('/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{asset('/plugins/metismenu/js/metisMenu.min.js')}}"></script>
  <script src="{{asset('/plugins/easyPieChart/jquery.easypiechart.js')}}"></script>
  <script src="{{asset('/plugins/peity/jquery.peity.min.js')}}"></script>
  <script src="{{asset('/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('/js/pace.min.js')}}"></script>
  <script src="{{asset('/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
	<script src="{{asset('/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
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

  @include('partials.chatbot')

  @stack('scripts')

</body>

</html>