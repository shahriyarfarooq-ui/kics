<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('layouts/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <script src="{{ asset('layouts/assets/js/config.js') }}"></script>
    <style>

    .custom-modal {
        width: 95vw !important;
        max-width: 95vw !important;
    }

    .custom-modal .modal-content {
        min-height: 92vh;
        border-radius: 18px;
        border: none;
    }

    .custom-modal .modal-header {
        padding: 20px 30px;
        border-bottom: 1px solid #e9ecef;
    }

    .custom-modal .modal-footer {
        padding: 20px 30px;
        border-top: 1px solid #e9ecef;
    }

    .custom-modal .modal-body {
        padding: 30px;
        overflow-y: visible !important;
        max-height: unset !important;
    }

    .modal-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .full-width {
        grid-column: span 2;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        min-height: 45px;
        border-radius: 10px;
    }

    textarea.form-control {
        min-height: 120px;
    }

    .note-editor.note-frame {
        border-radius: 10px;
    }

    @media (max-width: 768px) {

        .custom-modal {
            width: 98vw !important;
            max-width: 98vw !important;
            margin: 0 auto;
        }

        .modal-form-grid {
            grid-template-columns: 1fr;
        }

        .full-width {
            grid-column: span 1;
        }

        .custom-modal .modal-body {
            padding: 20px;
        }

    }

</style>
</head>

<body>
<div class="layout-wrapper">
    <div class="main-menu">
        @include('components.logo')
        @include('components.sidebar')
    </div>

    <div class="page-content">
        @include('components.topbar')

        <div class="px-3">
            <div class="container-fluid">

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="py-3 py-lg-4 d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0">Projects</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        Create New Project
                    </button>
                </div>

               <!-- Create Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="createProjectModalLabel">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="createProjectForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="modal-form-grid">

                        <div class="form-group mb-2">
                            <label>Name</label>
                            <input type="text" class="form-control" name="projectlist_Name" required>
                        </div>

                        <div class="form-group mb-2">
                            <label>Sequence No</label>
                            <input type="number" class="form-control" name="projectlist_seqno" value="0" required>
                        </div>

                        <div class="form-group mb-2 full-width">
                            <label>Description</label>
                            <textarea class="form-control summernote"
                                      id="projectlist_description"
                                      name="projectlist_description"
                                      required></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label>Small Picture</label>
                            <input type="file" class="form-control" name="projectlist_small_picture">
                        </div>

                        <div class="form-group mb-2">
                            <label>Group</label>
                            <select class="form-control" name="group_id" required>
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->group_id }}">
                                        {{ $group->group_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label>Subgroup ID</label>
                            <input type="number" class="form-control" name="subgroup_id" value="0">
                        </div>

                        <div class="form-group mb-2">
                            <label>Sub Site ID</label>
                            <input type="number" class="form-control" name="sub_site_id" value="0">
                        </div>

                        <div class="form-group mb-2">
                            <label>Code</label>
                            <input type="text" class="form-control" name="code">
                        </div>

                        <div class="form-group mb-2">
                            <label>Project Category</label>
                            <input type="text" class="form-control" name="project_category">
                        </div>

                        <div class="form-group mb-2">
                            <label>Is Completed</label>
                            <select class="form-control" name="is_completed">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label>Inactive</label>
                            <select class="form-control" name="inactive">
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group mb-2 full-width">
                            <label>Funded By</label>
                            <textarea class="form-control" name="fundedby"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Create Project
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
                <!-- End Create Modal -->

                <!-- Edit Modal -->
                <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editProjectForm" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="modal-body">

                    <input type="hidden" id="edit_project_id">

                    <div class="modal-form-grid">

                        <div class="form-group mb-2">
                            <label>Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="edit_projectlist_Name"
                                   name="projectlist_Name"
                                   required>
                        </div>

                        <div class="form-group mb-2">
                            <label>Sequence No</label>
                            <input type="number"
                                   class="form-control"
                                   id="edit_projectlist_seqno"
                                   name="projectlist_seqno"
                                   required>
                        </div>

                        <div class="form-group mb-2 full-width">
                            <label>Description</label>
                            <textarea class="form-control summernote"
                                      id="edit_projectlist_description"
                                      name="projectlist_description"
                                      required></textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label>Small Picture</label>
                            <input type="file"
                                   class="form-control"
                                   name="projectlist_small_picture">
                        </div>

                        <div class="form-group mb-2">
                            <label>Group</label>
                            <select class="form-control"
                                    id="edit_group_id"
                                    name="group_id"
                                    required>

                                <option value="">Select Group</option>

                                @foreach($groups as $group)
                                    <option value="{{ $group->group_id }}">
                                        {{ $group->group_name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label>Subgroup ID</label>
                            <input type="number"
                                   class="form-control"
                                   id="edit_subgroup_id"
                                   name="subgroup_id">
                        </div>

                        <div class="form-group mb-2">
                            <label>Sub Site ID</label>
                            <input type="number"
                                   class="form-control"
                                   id="edit_sub_site_id"
                                   name="sub_site_id">
                        </div>

                        <div class="form-group mb-2">
                            <label>Code</label>
                            <input type="text"
                                   class="form-control"
                                   id="edit_code"
                                   name="code">
                        </div>

                        <div class="form-group mb-2">
                            <label>Project Category</label>
                            <input type="text"
                                   class="form-control"
                                   id="edit_project_category"
                                   name="project_category">
                        </div>

                        <div class="form-group mb-2">
                            <label>Is Completed</label>
                            <select class="form-control"
                                    id="edit_is_completed"
                                    name="is_completed">

                                <option value="0">No</option>
                                <option value="1">Yes</option>

                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label>Inactive</label>
                            <select class="form-control"
                                    id="edit_inactive"
                                    name="inactive">

                                <option value="0">Active</option>
                                <option value="1">Inactive</option>

                            </select>
                        </div>

                        <div class="form-group mb-2 full-width">
                            <label>Funded By</label>
                            <textarea class="form-control"
                                      id="edit_fundedby"
                                      name="fundedby"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
                <!-- End Edit Modal -->

                <!-- Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped align-middle" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Group</th>
                                            <th>Category</th>
                                            <th>Completed</th>
                                            <th>Inactive</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>{{ $project->projectlist_id }}</td>
                                                <td>{{ $project->projectlist_Name }}</td>
                                                <td>{{ Str::limit($project->projectlist_description, 50) }}</td>
                                                <td>{{ $project->group->group_name ?? 'N/A' }}</td>
                                                <td>{{ $project->project_category }}</td>
                                                <td>{{ $project->is_completed ? 'Yes' : 'No' }}</td>
                                                <td>{{ $project->inactive ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a class="btn btn-info btn-sm"
                                                       href="{{ route('project.manage', $project) }}">
                                                        View
                                                    </a>

                                                    <button class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editProjectModal"
                                                            data-id="{{ $project->projectlist_id }}"
                                                            data-name="{{ $project->projectlist_Name }}"
                                                            data-description="{{ $project->projectlist_description }}"
                                                            data-seqno="{{ $project->projectlist_seqno }}"
                                                            data-group="{{ $project->group_id }}"
                                                            data-subgroup="{{ $project->subgroup_id }}"
                                                            data-subsite="{{ $project->sub_site_id }}"
                                                            data-code="{{ $project->code }}"
                                                            data-category="{{ $project->project_category }}"
                                                            data-completed="{{ $project->is_completed }}"
                                                            data-fundedby="{{ $project->fundedby }}"
                                                            data-inactive="{{ $project->inactive }}">
                                                        Edit
                                                    </button>

                                                    <form action="{{ route('project.delete', $project->projectlist_id) }}"
                                                          method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center mt-3">
                                    {{ $projects->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Table -->

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>

<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('layouts/assets/js/app.js') }}"></script>

    <!-- DataTables JS -->
    <script src="{{ asset('layouts/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('layouts/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();

            $('.summernote').summernote({
                height: 220,
                placeholder: 'Enter project description...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview']],
                ]
            });

            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Edit Modal - Populate data when modal opens
            $('#editProjectModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);

                var id = button.data('id');
                var name = button.data('name');
                var description = button.data('description');
                var seqno = button.data('seqno');
                var group = button.data('group');
                var subgroup = button.data('subgroup');
                var subsite = button.data('subsite');
                var code = button.data('code');
                var category = button.data('category');
                var completed = button.data('completed');
                var fundedby = button.data('fundedby');
                var inactive = button.data('inactive');

                var modal = $(this);

                modal.find('#edit_project_id').val(id);
                modal.find('#edit_projectlist_Name').val(name);
                modal.find('#edit_projectlist_description').summernote('code', description || '');
                modal.find('#edit_projectlist_seqno').val(seqno);
                modal.find('#edit_group_id').val(group);
                modal.find('#edit_subgroup_id').val(subgroup);
                modal.find('#edit_sub_site_id').val(subsite);
                modal.find('#edit_code').val(code);
                modal.find('#edit_project_category').val(category);
                modal.find('#edit_is_completed').val(completed);
                modal.find('#edit_fundedby').val(fundedby);
                modal.find('#edit_inactive').val(inactive);

                // Set form action to use the proper route
                var updateUrl = "{{ url('projects') }}/" + id;
                $('#editProjectForm').attr('action', updateUrl);
            });

            // Handle Edit Form Submission
            $('#editProjectForm').submit(function(e) {
                e.preventDefault();

                $('#edit_projectlist_description').val($('#edit_projectlist_description').summernote('code'));
                
                const formData = new FormData(this);
                const projectId = $('#edit_project_id').val();
                const url = "{{ url('projects') }}/" + projectId;

                // Add PUT method for form submission
                formData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            alert(response.message || 'Project updated successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'Failed to update project'));
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Something went wrong!';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMsg = errors.join('\n');
                            }
                        }
                        console.error('AJAX Error:', xhr.responseText);
                        alert('Error: ' + errorMsg);
                    }
                });
            });

            // Handle Create Form Submission  
            $('#createProjectForm').submit(function(e) {
                e.preventDefault();

                $('#projectlist_description').val($('#projectlist_description').summernote('code'));
                
                const formData = new FormData(this);
                const url = "{{ route('project.store') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            alert(response.message || 'Project created successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'Failed to create project'));
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Something went wrong!';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMsg = errors.join('\n');
                            }
                        }
                        console.error('AJAX Error:', xhr.responseText);
                        alert('Error: ' + errorMsg);
                    }
                });
        });
    });
</script>

</body>
</html>
