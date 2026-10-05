<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
    <script src="{{ asset('layouts/assets/js/config.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>

    .custom-modal {
        width: 99vw !important;
        max-width: 99vw !important;
        margin: 5px auto;
    }

    .custom-modal .modal-content {
        height: 96vh;
        border-radius: 20px;
        border: none;
        background: #0f172a;
        color: #f8fafc;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }

    /* HEADER */
    .custom-modal .modal-header {
        padding: 22px 35px;
        border-bottom: 1px solid #334155;
        background: linear-gradient(135deg, #111827, #1e293b);
    }

    .custom-modal .modal-title {
        color: #ffffff;
        font-size: 24px;
        font-weight: 700;
    }

    .custom-modal .btn-close {
        filter: invert(1);
        opacity: 1;
    }

    /* BODY */
    .custom-modal .modal-body {
        padding: 30px 35px;
        overflow-y: auto !important;
        max-height: calc(96vh - 145px);
        background: #0f172a;
    }

    /* FOOTER */
    .custom-modal .modal-footer {
        padding: 20px 35px;
        border-top: 1px solid #334155;
        background: #111827;
    }

    /* SCROLLBAR */
    .custom-modal .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .custom-modal .modal-body::-webkit-scrollbar-track {
        background: #111827;
    }

    .custom-modal .modal-body::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 10px;
    }

    /* GRID */
    .modal-form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .full-width {
        grid-column: span 3;
    }

    /* LABELS */
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #e2e8f0;
    }

    /* INPUTS */
    .form-control {
        min-height: 50px;
        border-radius: 12px;
        background: #1e293b;
        border: 1px solid #334155;
        color: #f8fafc;
        padding: 12px 14px;
        transition: 0.3s ease;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        background: #1e293b;
        border-color: #3b82f6;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.18);
    }

    textarea.form-control {
        min-height: 130px;
    }

    /* SUMMERNOTE */
    .note-editor.note-frame {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #334155;
        background: #1e293b;
    }

    .note-toolbar {
        background: #111827 !important;
        border-bottom: 1px solid #334155 !important;
    }

    .note-btn {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    .note-btn:hover {
        background: #334155 !important;
    }

    .note-editable {
        background: #1e293b !important;
        color: #f8fafc !important;
    }

    .note-statusbar {
        background: #111827 !important;
        border-top: 1px solid #334155 !important;
    }

    /* BUTTONS */
    .btn {
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 600;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
    }

    .btn-outline-secondary {
        border-color: #475569;
        color: #cbd5e1;
    }

    .btn-outline-secondary:hover {
        background: #334155;
        color: #fff;
    }

    /* IMAGES */
    img {
        border-radius: 10px;
        border: 1px solid #334155;
        padding: 4px;
        background: #1e293b;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {

        .modal-form-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .full-width {
            grid-column: span 2;
        }

    }

    @media (max-width: 768px) {

        .custom-modal {
            width: 100vw !important;
            max-width: 100vw !important;
            margin: 0;
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

                <div class="py-3 py-lg-4 d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0">Groups</h4>
                    <button type="button" class="btn btn-primary" id="addGroupBtn">Add Group</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Groups Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped align-middle" id="groupsTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Brief Desc</th>
                                            <th>Project List</th>
                                            <th>Services</th>
                                            <th>R&D Project</th>
                                            <th>Subgroup</th>
                                            <th>Contact Us</th>
                                            <th>Code</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groups as $group)
                                        <tr data-id="{{ $group->group_id }}">
                                            <td>{{ $group->group_id }}</td>
                                            <td>{{ $group->group_name }}</td>
                                            <td>{{ Str::limit($group->group_description, 50) }}</td>
                                            <td>{{ $group->group_briefdescription }}</td>
                                            <td>{{ $group->group_projectlist_check }}</td>
                                            <td>{{ $group->group_services_check }}</td>
                                            <td>{{ $group->group_rdproject_check }}</td>
                                            <td>{{ $group->group_subgroup_check }}</td>
                                            <td>{{ Str::limit($group->contact_us, 40) }}</td>
                                            <td>{{ $group->code }}</td>
                                            <td>
                                                <a href="{{ route('admin.groups.view', $group->group_id) }}" class="btn btn-sm btn-info">View</a>
                                                <button class="btn btn-sm btn-warning editGroupBtn">Edit</button>
                                                <form action="{{ route('admin.groups.destroy', $group->group_id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this group?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $groups->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Modal -->
                <div class="modal fade" id="groupModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 750px;">
                        <form id="groupForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title d-flex align-items-center" id="groupModalTitle">
                                        <i data-lucide="layers" class="me-2"></i>
                                        Add Group
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <input type="hidden" name="group_id" id="group_id">

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Group Name</label>
                                            <input type="text" name="group_name" id="group_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Sequence No</label>
                                            <input type="number" name="group_seqno" id="group_seqno" class="form-control" value="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Is Center</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="is_center" id="is_center" value="1">
                                                <label class="form-check-label" for="is_center">Mark as center</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted mb-3">Group details</h6>
                                        <label>Description</label>
                                        <textarea name="group_description" id="group_description" class="form-control summernote" required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label>Brief Description</label>
                                        <input type="text" name="group_briefdescription" id="group_briefdescription" class="form-control" placeholder="Short summary for listings">
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label">Project List</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="group_projectlist_check" id="group_projectlist_check" value="1">
                                                <label class="form-check-label" for="group_projectlist_check">Enabled</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Services</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="group_services_check" id="group_services_check" value="1">
                                                <label class="form-check-label" for="group_services_check">Enabled</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">R&D Project</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="group_rdproject_check" id="group_rdproject_check" value="1">
                                                <label class="form-check-label" for="group_rdproject_check">Enabled</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Subgroup</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="group_subgroup_check" id="group_subgroup_check" value="1">
                                                <label class="form-check-label" for="group_subgroup_check">Enabled</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h6 class="text-uppercase text-muted mb-3">Page content</h6>
                                        <label>Short Description</label>
                                        <textarea name="short_desc" id="short_desc" class="form-control summernote" rows="4"></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label>Home Intro</label>
                                        <textarea name="home_intro" id="home_intro" class="form-control summernote" rows="4"></textarea>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <label>Sub Site ID</label>
                                            <input type="number" name="sub_site_id" id="sub_site_id" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Code</label>
                                            <input type="text" name="code" id="code" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <label>Group Banner</label>
                                            <input type="file" name="group_banner" id="group_banner" class="form-control">
                                            <img id="banner_preview" src="" width="100" class="mt-1 d-none">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Image Path</label>
                                            <input type="file" name="img_path" id="img_path" class="form-control">
                                            <img id="img_preview" src="" width="100" class="mt-1 d-none">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label>Contact Us</label>
                                        <textarea name="contact_us" id="contact_us" class="form-control summernote" rows="4" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary" id="groupSubmitBtn">Save Group</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Modal -->

            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#groupsTable').DataTable();

    // Initialize HTML editors for rich content fields
    $('.summernote').summernote({
        height: 220,
        placeholder: 'Enter formatted content here...',
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']],
        ]
    });

    // Preview images
    $('#group_banner').on('change', function() {
        let file = this.files[0];
        if (file) {
            $('#banner_preview').attr('src', URL.createObjectURL(file)).removeClass('d-none');
        }
    });
    $('#img_path').on('change', function() {
        let file = this.files[0];
        if (file) {
            $('#img_preview').attr('src', URL.createObjectURL(file)).removeClass('d-none');
        }
    });

    // Add Group
    $('#addGroupBtn').click(function() {
        $('#groupModalTitle').text('Add Group');
        $('#groupForm')[0].reset();
        $('#groupForm').removeAttr('data-id');
        $('#groupForm').attr('action', "{{ route('admin.groups.store') }}");
        
        // Reset all Summernote editors
        $('#group_description').summernote('reset');
        $('#short_desc').summernote('reset');
        $('#home_intro').summernote('reset');
        $('#contact_us').summernote('reset');
        
        $('#banner_preview, #img_preview').addClass('d-none').attr('src', '');
        $('#is_center, #group_projectlist_check, #group_services_check, #group_rdproject_check, #group_subgroup_check').prop('checked', false);
        $('#groupModal').modal('show');
    });

    // Edit Group
    $(document).on('click', '.editGroupBtn', function() {
        const id = $(this).closest('tr').data('id');
        const editUrl = "{{ url('admin/groups') }}/" + id + "/edit";
        const updateUrl = "{{ url('admin/groups') }}/" + id;

        $.get(editUrl, function(group) {
            $('#groupModalTitle').text('Edit Group');
            $('#groupForm').attr('action', updateUrl);
            $('#groupForm').attr('data-id', id);
            $('#groupForm')[0].reset();

            // Set regular input values
            $('#group_name').val(group.group_name);
            $('#group_briefdescription').val(group.group_briefdescription);
            $('#group_seqno').val(group.group_seqno);
            $('#code').val(group.code);
            $('#sub_site_id').val(group.sub_site_id);

            // Set Summernote editors - use proper code setting
            setTimeout(function() {
                $('#group_description').summernote('code', group.group_description || '');
                $('#short_desc').summernote('code', group.short_desc || '');
                $('#home_intro').summernote('code', group.home_intro || '');
                $('#contact_us').summernote('code', group.contact_us || '');
            }, 100);

            // Set checkboxes
            $('#is_center').prop('checked', group.is_center == 1);
            $('#group_projectlist_check').prop('checked', group.group_projectlist_check == 1);
            $('#group_services_check').prop('checked', group.group_services_check == 1);
            $('#group_rdproject_check').prop('checked', group.group_rdproject_check == 1);
            $('#group_subgroup_check').prop('checked', group.group_subgroup_check == 1);

            $('#groupModal').modal('show');
        }).fail(function(xhr) {
            alert('Failed to load group data: ' + (xhr.responseJSON?.message || 'Unknown error'));
            console.error('Edit failed:', xhr);
        });
    });

    // Submit (Add/Edit)
    $('#groupForm').submit(function(e) {
        e.preventDefault();

        // Sync all Summernote editors to textareas before submission
        $('#group_description').val($('#group_description').summernote('code'));
        $('#short_desc').val($('#short_desc').summernote('code'));
        $('#home_intro').val($('#home_intro').summernote('code'));
        $('#contact_us').val($('#contact_us').summernote('code'));

        const form = $(this);
        const formData = new FormData(this);
        const id = form.attr('data-id');
        let url = form.attr('action');

        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message || 'Operation successful!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>

</body>
</html>
