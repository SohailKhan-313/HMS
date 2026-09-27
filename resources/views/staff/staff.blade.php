<!-- Staff list -->
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
    /* Minimal custom overrides */
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

   .table tbody tr:hover td { background-color:#f9fcff; }
.name {
  background: #e1ecfe;
  color: #194a7a;
  font-weight: 500;
  font-size: 0.8rem;
  padding: 4px 12px;
  border-radius: 20px;
  display: inline-block;
}



    .designation-badge {
        background: #e1ecfe;
        color: #194a7a;
        font-weight: 500;
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .phone-number {
        background: #f4f7fb;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
        font-family: 'Inter', monospace;
        font-size: 0.9rem;
    }

    .btn-book {
        background: #e3f0fd;
        color: #1f5b99;
        border: none;
        border-radius: 40px;
        padding: 0.4rem 0.9rem;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-book i {
        font-size: 1.1rem;
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

    .action-icon {
        color: #577a9e;
        font-size: 1.3rem;
        padding: 0.3rem;
        border-radius: 8px;
        transition: 0.15s;
    }

    .action-icon:hover {
        background: #e3ecf5;
        color: #1b4c7c;
    }

    a.email-link {
        color: #1f5f9e;
        text-decoration: none;
        border-bottom: 1px dashed #b3c9e0;
    }

    a.email-link:hover {
        border-bottom: 1px solid #1f5f9e;
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

    /* Image preview */
    .image-preview {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #e9eef2;
        margin-top: 0.5rem;
    }
</style>

<main class="page-content">
    <div class="container-fluid px-0">
        <div class="card appointment-card">
            <!-- Header: Create Staff -->
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h2 class="h3 mb-0 fw-semibold d-flex align-items-center gap-3" style="color:#0b1b2f;">
                    <i class="bi bi-calendar-plus-fill fs-1 text-primary"
                        style="background:#eef4fe; padding:0.5rem; border-radius:14px;"></i>
                    Staff
                </h2>
                <div class="d-flex gap-2">
                    <div class="d-flex gap-2 ">
     <form id="searchForm" method="GET" action="{{ route('staff.index') }}">
    <input 
    class="form-control form-control-sm"
        type="text" 
        name="search" 
        id="staffSearch"
        placeholder="Search staff..." 
        value="{{ request('search') }}"
        onkeyup="document.getElementById('searchForm').submit();"
    >
</form>

                    </div>
                    <div class="d-flex gap-2">

                        <span class="btn-outline-filter btn-sm" onclick="filterStaff()">
                            <i class="bi bi-funnel "></i>Filter
                        </span>
                    </div>
                    <a href="{{ route('reports.staff.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm shadow-sm d-flex align-items-center gap-1">
                        <i class="bi bi-file-earmark-pdf"></i> Print / Export (PDF)
                    </a>
                    <!-- New Staff Button -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staffModal">
                        <i class="bi bi-plus-circle me-2"></i> New Staff
                    </button>
                </div>
            </div>

            <!-- New Staff Modal (Improved) -->
            <div class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Header -->
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-person-plus-fill me-2"></i>
                                Add New Staff Member
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data" id="addStaffForm">
                            @csrf

                            <!-- Body -->
                            <div class="modal-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person me-2"></i>Full Name*
                                        </label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-briefcase me-2"></i>Designation
                                        </label>
                                        <input type="text" name="designation" class="form-control" placeholder="e.g., Admin" >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-telephone me-2"></i>Phone Number*
                                        </label>
                                        <input type="text" name="phone" class="form-control" placeholder="+92 34 567 890" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-cash me-2"></i>Monthly Salary
                                        </label>
                                        <input type="number" name="salary" class="form-control" placeholder="Enter amount" >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-image me-2"></i>Profile Image
                                        </label>
                                        <input type="file" name="image" class="form-control" accept="image/*" id="addImageInput">
                                        <img id="addImagePreview" class="image-preview mt-2" style="display: none;">
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-save text-white">
                                    <i class="bi bi-check-lg me-2"></i>Save Staff
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
<!-- Reusable Centered Toast -->
<!-- Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index:1080">

    @php
        $toastMessage = '';
        $toastIcon = '';
        $textColor = '';
    @endphp

    @if(session('success') || session('update') || session('delete'))

        @php
            if(session('success')) {
                $toastMessage = session('success');
                $toastIcon = 'bi-check-circle-fill text-success';
                $textColor = 'text-success';
            } elseif(session('update')) {
                $toastMessage = session('update');
                $toastIcon = 'bi-pencil-square text-primary';
                $textColor = 'text-primary';
            } elseif(session('delete')) {
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

                    <button type="button"
                        class="btn-close ms-auto"
                        data-bs-dismiss="toast">
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>

@if(session('success') || session('update') || session('delete'))
<script>
document.addEventListener("DOMContentLoaded", function () {

    const toastEl = document.getElementById('actionToast');

    if(toastEl){
        const toast = new bootstrap.Toast(toastEl,{
            delay:2000,
            autohide:true
        });

        toast.show();
    }

});
</script>
@endif
  <!-- Staff table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="staffTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
    @forelse($staff as $s)
        <tr>
            <td><span class="name">{{ $s->name }}</span></td>
            <td>
                <span class="designation-badge">
                    {{ $s->designation }}
                </span>
            </td>
            <td>
                <a href="mailto:{{ $s->email }}" class="email-link">
                    {{ $s->email }}
                </a>
            </td>
            <td>
                <span class="phone-number">
                    {{ $s->phone }}
                </span>
            </td>
            <td>
                @if($s->image_url)
                    <div class="position-relative d-inline-block">
                        <img src="{{ $s->image_url }}" alt="{{ $s->name }}" width="42" height="42"
                             class="rounded-circle shadow-sm"
                             style="object-fit: cover; border: 2px solid #e9eef2;"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-none align-items-center justify-content-center fw-bold shadow-sm"
                             style="width: 42px; height: 42px; font-size: 14px; border: 2px solid #e9eef2;">
                            {{ $s->initials }}
                        </div>
                    </div>
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm"
                         style="width: 42px; height: 42px; font-size: 14px; border: 2px solid #e9eef2;"
                         title="No image uploaded">
                        {{ $s->initials }}
                    </div>
                @endif
            </td>
            <td>
                <a href="#" class="action-icon" data-bs-toggle="modal"
                   data-bs-target="#editStaffModal{{ $s->id }}">
                    <i class="bi bi-pencil"></i>
                </a>

                <a href="#" class="action-icon" data-bs-toggle="modal"
                   data-bs-target="#deleteStaffModal{{ $s->id }}">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>

    @empty
        {{-- This block runs ONLY if the $staff collection is empty --}}
        <tr>
            <td colspan="6" class="text-center">
                <div class="text-muted">
                    <i class="bi bi-search" style="font-size: 2rem;"></i>
                    <p>No staff records found matching your search.</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
                    </table>
                </div>

            </div>

            <!-- Simple footer with staff count -->
            <div class="card-footer bg-white border-0 gap-3 py-3" style="color:#7589a2; font-size:0.85rem;">
                <i class="bi bi-info-circle"></i> {{ $staff->count() }} staff members available
                <nav class="float-end" aria-label="Page navigation">
                    <ul class="pagination">

                        @if ($staff->onFirstPage())
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $staff->previousPageUrl() }}">Previous</a>
                        </li>
                        @endif

                        @foreach ($staff->getUrlRange(1, $staff->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $staff->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        @if ($staff->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $staff->nextPageUrl() }}">Next</a>
                        </li>
                        @else
                        <li class="page-item disabled">
                            <a class="page-link">Next</a>
                        </li>
                        @endif

                    </ul>
                </nav>
            </div>

        </div>

    </div>
</main>

@foreach($staff as $s)
    <!-- Edit Staff Modal -->
    <div class="modal fade" id="editStaffModal{{ $s->id }}" tabindex="-1" aria-labelledby="editStaffModalLabel{{ $s->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editStaffModalLabel{{ $s->id }}">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Staff Member – {{ $s->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Form -->
                <form action="{{ route('staff.update', $s->id) }}" method="POST" enctype="multipart/form-data" id="editStaffForm{{ $s->id }}">
                    @csrf
                    @method('PUT')

                    <!-- Body -->
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-person me-2"></i>Full Name*
                                </label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $s->name) }}" placeholder="Enter full name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-briefcase me-2"></i>Designation
                                </label>
                                <input type="text" name="designation" class="form-control" value="{{ old('designation', $s->designation) }}" placeholder="e.g., Admin, Nurse">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email Address*
                                </label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $s->email) }}" placeholder="name@example.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-telephone me-2"></i>Phone Number*
                                </label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $s->phone) }}" placeholder="+92 34 567 890" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-cash me-2"></i>Monthly Salary
                                </label>
                                <input type="number" step="0.01" name="salary" class="form-control" value="{{ old('salary', $s->salary) }}" placeholder="Enter amount">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bi bi-image me-2"></i>Profile Image
                                </label>
                                <input type="file" name="image" class="form-control" accept="image/*" id="editImageInput{{ $s->id }}" onchange="previewStaffImage(this, 'editImagePreview{{ $s->id }}')">
                                <div class="mt-2">
                                    @if($s->image_url)
                                        <img id="editImagePreview{{ $s->id }}" src="{{ $s->image_url }}" class="image-preview" style="display:block;">
                                    @else
                                        <img id="editImagePreview{{ $s->id }}" class="image-preview" style="display:none;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-save text-white">
                            <i class="bi bi-check-lg me-2"></i>Update Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Staff Modal -->
    <div class="modal fade delete-modal" id="deleteStaffModal{{ $s->id }}" tabindex="-1" aria-labelledby="deleteStaffModalLabel{{ $s->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="deleteStaffModalLabel{{ $s->id }}">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body text-center">
                    <i class="bi bi-person-x-fill delete-icon"></i>
                    <h4 class="mb-3">Are you sure?</h4>
                    <p class="text-muted mb-0">
                        You are about to delete staff member <strong>{{ $s->name }}</strong>.
                    </p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </button>
                    <form action="{{ route('staff.destroy', $s->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete text-white">
                            <i class="bi bi-trash me-2"></i>Yes, Delete Staff
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Image Preview Script -->
<script>
    // Preview image for Add Modal
    document.getElementById('addImageInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('addImagePreview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Preview image for Edit Modals
    function previewStaffImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview) return;
        const file = input.files && input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    function filterStaff() {
        const searchInput = document.getElementById('staffSearch');
        if (searchInput) {
            searchInput.focus();
        }
    }
</script>
@endsection