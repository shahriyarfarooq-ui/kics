@extends('layouts.app')

@section('title', 'ERP Departments')

@section('content')
    <div class="main-menu">
        @include('components.logo')
        @include('components.sidebar')
    </div>

    <div class="page-content erp-department-page">
        @include('components.topbar')
        <div class="px-3">
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="page-title">ERP Departments</h4>
                            <div>
                                <a href="{{ route('admin.erp.sync') }}" class="btn btn-success me-2">
                                    <i class="bi bi-arrow-repeat me-1"></i> Sync ERP Data
                                </a>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#bulkActionModal">
                                    <i class="bi bi-check2-square me-1"></i> Bulk Actions
                                </button>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Filters -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="GET" class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by name, code, manager..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="visibility" class="form-select">
                                            <option value="">All Visibility</option>
                                            <option value="visible" {{ request('visibility') == 'visible' ? 'selected' : '' }}>Visible</option>
                                            <option value="hidden" {{ request('visibility') == 'hidden' ? 'selected' : '' }}>
                                                Hidden</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="departmentsTable">
                                        <thead>
                                            <tr>
                                                <th width="40">
                                                    <input type="checkbox" id="selectAll">
                                                </th>
                                                <th>ID</th>
                                                <th>Logo</th>
                                                <th>Department Name</th>
                                                <th>Code</th>
                                                <th>Manager</th>
                                                <th>Projects</th>
                                                <th>Status</th>
                                                <th>Visibility</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($departments as $department)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="row-checkbox"
                                                            value="{{ $department->id }}">
                                                    </td>
                                                    <td>{{ $department->kics_id }}</td>
                                                    <td>
                                                        @if($department->logo)
                                                            <img src="{{ asset('storage/' . $department->logo) }}"
                                                                alt="{{ $department->name }}" width="40" height="40"
                                                                class="rounded-circle object-fit-cover">
                                                        @else
                                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width:40px;height:40px;">
                                                                <i class="bi bi-building fs-5 text-secondary"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $department->display_name ?? $department->name }}</td>
                                                    <td>{{ $department->dept_code ?? '-' }}</td>
                                                    <td>{{ $department->manager ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-info rounded-pill">
                                                            {{ $department->projects_count }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $department->active ? 'success' : 'danger' }}">
                                                            {{ $department->active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button type="button"
                                                                class="btn btn-{{ $department->is_visible ? 'success' : 'secondary' }} btn-sm toggle-visibility"
                                                                data-id="{{ $department->id }}" data-type="department"
                                                                data-visible="{{ $department->is_visible ? 'true' : 'false' }}">
                                                                <i class="bi bi-eye{{ $department->is_visible ? '' : '-slash' }}"></i>
                                                            </button>
                                                            <span class="badge bg-{{ $department->is_visible ? 'success' : 'danger' }}">
                                                                {{ $department->is_visible ? 'Visible' : 'Hidden' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm edit-department"
                                                            data-id="{{ $department->id }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <a href="{{ route('admin.erp.departments.show', $department) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted py-4">
                                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                                        No departments found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    {{ $departments->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDepartmentForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Name</label>
                                <input type="text" class="form-control" name="web_department_name" id="edit_web_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department Code</label>
                                <input type="text" class="form-control" name="dept_code" id="edit_dept_code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Manager</label>
                                <input type="text" class="form-control" name="manager" id="edit_manager">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                <div id="edit_logo_preview" class="mt-2"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Cover Image</label>
                                <input type="file" class="form-control" name="cover_image" accept="image/*">
                                <div id="edit_cover_preview" class="mt-2"></div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="web_detail_description" id="edit_description"
                                    rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vision</label>
                                <textarea class="form-control" name="vision" id="edit_vision" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mission</label>
                                <textarea class="form-control" name="mission" id="edit_mission" rows="2"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Admin Notes</label>
                                <textarea class="form-control" name="admin_notes" id="edit_admin_notes" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="active" id="edit_active"
                                        value="1">
                                    <label class="form-check-label" for="edit_active">Active</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_visible" id="edit_visible"
                                        value="1">
                                    <label class="form-check-label" for="edit_visible">Visible on Website</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Action Modal -->
    <div class="modal fade" id="bulkActionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bulkActionForm" method="POST" action="{{ route('admin.erp.bulk.action') }}">
                    @csrf
                    <div class="modal-body">
                        <p>Selected departments: <span id="selectedCount">0</span></p>
                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select" required>
                                <option value="">Select Action...</option>
                                <option value="visible">Make Visible</option>
                                <option value="hidden">Make Hidden</option>
                                <option value="active">Activate</option>
                                <option value="inactive">Deactivate</option>
                                <option value="delete">Delete</option>
                            </select>
                        </div>
                        <input type="hidden" name="ids" id="bulkIds">
                        <input type="hidden" name="type" value="department">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Action</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .erp-department-page .card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        }

        .erp-department-page .table thead th {
            background: #f8fafc;
            color: #0f172a;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            vertical-align: middle;
        }

        .erp-department-page .table td,
        .erp-department-page .table th {
            vertical-align: middle;
            padding: 0.9rem 0.8rem;
        }

        .erp-department-page .toggle-visibility,
        .erp-department-page .btn,
        .erp-department-page .form-control,
        .erp-department-page .form-select {
            transition: all 0.2s ease-in-out;
        }

        .erp-department-page .toggle-visibility {
            min-width: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .erp-department-page .toggle-visibility:hover,
        .erp-department-page .btn:hover {
            transform: translateY(-1px);
        }

        .erp-department-page .badge {
            font-size: 0.72rem;
            padding: 0.45rem 0.65rem;
            border-radius: 999px;
        }

        .erp-department-page .d-flex.align-items-center.gap-2 {
            min-height: 38px;
        }

        @media (max-width: 767.98px) {
            .erp-department-page .page-title {
                font-size: 1.5rem;
            }

            .erp-department-page .d-flex.justify-content-between {
                display: block !important;
            }

            .erp-department-page .d-flex.justify-content-between > div {
                margin-top: 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            if ($.fn.DataTable) {
                $('#departmentsTable').DataTable({
                    "paging": false,
                    "ordering": true,
                    "info": false,
                    "searching": false
                });
            }

            // Edit Department
            $('.edit-department').click(function () {
                const id = $(this).data('id');
                const form = $('#editDepartmentForm');
                const url = "{{ url('admin/erp/departments') }}/" + id;

                form.attr('action', url);

                // Fetch department data
                $.ajax({
                    url: "{{ url('admin/erp/departments') }}/" + id + "/edit",
                    type: 'GET',
                    success: function (data) {
                        // Populate form fields
                        $('#edit_web_name').val(data.web_department_name || '');
                        $('#edit_dept_code').val(data.dept_code || '');
                        $('#edit_manager').val(data.manager || '');
                        $('#edit_description').val(data.web_detail_description || '');
                        $('#edit_vision').val(data.vision || '');
                        $('#edit_mission').val(data.mission || '');
                        $('#edit_admin_notes').val(data.admin_notes || '');
                        $('#edit_active').prop('checked', data.active === 1);
                        $('#edit_visible').prop('checked', data.is_visible === 1);

                        // Show logo preview
                        if (data.logo) {
                            $('#edit_logo_preview').html(
                                '<img src="{{ asset('storage') }}/' + data.logo + '" width="100" class="img-thumbnail">' +
                                '<button type="button" class="btn btn-danger btn-sm ms-2 delete-image" data-field="logo">Remove</button>'
                            );
                        } else {
                            $('#edit_logo_preview').html('');
                        }

                        if (data.cover_image) {
                            $('#edit_cover_preview').html(
                                '<img src="{{ asset('storage') }}/' + data.cover_image + '" width="100" class="img-thumbnail">' +
                                '<button type="button" class="btn btn-danger btn-sm ms-2 delete-image" data-field="cover_image">Remove</button>'
                            );
                        } else {
                            $('#edit_cover_preview').html('');
                        }

                        $('#editDepartmentModal').modal('show');
                    },
                    error: function (xhr) {
                        alert('Error loading department data: ' + xhr.status);
                    }
                });
            });

            // Toggle Visibility
            $('.toggle-visibility').click(function () {
                const id = $(this).data('id');
                const type = $(this).data('type');
                const btn = $(this);

                $.ajax({
                    url: "{{ route('admin.erp.toggle.visibility') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        type: type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            const icon = response.is_visible ? 'eye' : 'eye-slash';
                            const cls = response.is_visible ? 'btn-success' : 'btn-secondary';
                            btn.removeClass('btn-success btn-secondary').addClass(cls);
                            btn.html('<i class="bi bi-' + icon + '"></i>');
                            btn.data('visible', response.is_visible);
                        }
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Error toggling visibility';
                        alert(message);
                    }
                });
            });

            // Select All
            $('#selectAll').change(function () {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            $('.row-checkbox').change(function () {
                updateSelectedCount();
            });

            function updateSelectedCount() {
                const count = $('.row-checkbox:checked').length;
                $('#selectedCount').text(count);
                const ids = $('.row-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();
                $('#bulkIds').val(ids.join(','));
            }

            // Delete Image
            $(document).on('click', '.delete-image', function () {
                if (!confirm('Delete this image?')) return;
                const field = $(this).data('field');
                const id = $('#editDepartmentForm').attr('action').split('/').pop();

                $.ajax({
                    url: "{{ url('admin/erp/departments') }}/" + id + "/delete-image",
                    type: 'POST',
                    data: {
                        field: field,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            if (field === 'logo') {
                                $('#edit_logo_preview').html('');
                            } else {
                                $('#edit_cover_preview').html('');
                            }
                        }
                    },
                    error: function (xhr) {
                        alert('Error deleting image');
                    }
                });
            });

            // Submit Bulk Action
            $('#bulkActionForm').submit(function (e) {
                const ids = $('#bulkIds').val();
                if (!ids) {
                    e.preventDefault();
                    alert('Please select at least one department.');
                    return false;
                }
            });
        });
    </script>
@endpush