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
    <script src="{{ asset('layouts/assets/js/config.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                        <h4 class="page-title mb-0">Staff</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#peopleModal">
                            Add Staff
                        </button>
                    </div>

                    <!-- Add/Edit Modal -->
                    <div class="modal fade" id="peopleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
<form id="peopleForm"
      action="{{ route('admin.staff.store') }}"
      method="POST"
      enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="edit_id" name="people_id">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Add / Edit Staff</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Title</label>
                                            <input type="text" id="title" name="title" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" id="fname" name="fname" class="form-control" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" id="lname" name="lname" class="form-control" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">URL Name</label>
                                            <input type="text" id="url_name_display" class="form-control" readonly disabled>
                                            <small class="form-text text-muted">Auto-generated from name</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email" name="email" class="form-control" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Cell No</label>
                                            <input type="text" id="cell_no" name="cell_no" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Office No</label>
                                            <input type="text" id="off_no" name="off_no" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Extension</label>
                                            <input type="text" id="ext" name="ext" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Fax No</label>
                                            <input type="text" id="fax_no" name="fax_no" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Personal URL</label>
                                            <input type="url" id="url" name="url" class="form-control" placeholder="https://example.com">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Biography</label>
                                            <textarea id="biography" name="biography" class="form-control" rows="3"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Research Interest</label>
                                            <textarea id="research_interest" name="research_interest" class="form-control" rows="2"></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Designation</label>
                                            <select name="designation_id" id="designation_id" class="form-select">
                                                <option value="">Select Designation</option>
                                                @foreach($designations as $d)
                                                    <option value="{{ $d->designation_id }}">{{ $d->designation_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Primary Group</label>
                                            <select name="group_id" id="group_id" class="form-select">
                                                <option value="">Select Group</option>
                                                @foreach($groups as $g)
                                                    <option value="{{ $g->group_id }}">{{ $g->group_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Post</label>
                                            <select name="post_id" id="post_id" class="form-select">
                                                <option value="">Select Post</option>
                                                @foreach($posts as $p)
                                                    <option value="{{ $p->post_id }}">{{ $p->post_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Sequence Number</label>
                                            <input type="number" id="seqno" name="seqno" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-select" required>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Image</label>
                                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                            <div id="imagePreview" class="mt-2"></div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input type="checkbox" id="is_core_team" name="is_core_team" value="1" class="form-check-input">
                                                <label class="form-check-label" for="is_core_team">Core Team Member</label>
                                            </div>
                                        </div>

                                        <!-- Labs Section -->
                                        <div class="col-12">
                                            <hr>
                                            <h6>Labs/Groups Assignment</h6>
                                            <div id="labsContainer">
                                                <!-- Dynamic labs will be added here -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addLabField()">
                                                + Add Lab/Group
                                            </button>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                    <!-- Staff Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped align-middle" id="datatable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Cell</th>
                                                <th>Designation</th>
                                                {{-- <th>bio</th>
                                                <th>research_interest</th> --}}

                                                <th>Group</th>
                                                <th>Status</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($people as $p)
                                                <tr>
                                                    <td>{{ $p->seqno ?? $p->people_id }}</td>
                                                    <td>
                                                        {{ $p->title ? $p->title . ' ' : '' }}{{ $p->fname }} {{ $p->lname }}
                                                        @if($p->is_core_team)
                                                            <span class="badge bg-warning ms-1">Core</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $p->email }}</td>
                                                    <td>{{ $p->cell_no ?? '-' }}</td>
                                                    <td>{{ $p->designation->designation_name ?? '-' }}</td>
                                                    {{-- <td>{{ $p->biography }}</td>
                                                    <td>{{ $p->research_interest}}</td> --}}

                                                    <td>{{ $p->group->group_name ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ (int) $p->status === 0 ? 'success' : 'secondary' }}">
                                                            {{ (int) $p->status === 0 ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($p->image_name)
                                                            <img src="{{ asset('storage/people/'.$p->image_name) }}" 
                                                                 width="50" height="50" class="rounded-circle object-fit-cover">
                                                        @else
                                                            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" 
                                                                 style="width:50px;height:50px;">
                                                                <i class="fas fa-user text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning editBtn" data-id="{{ $p->people_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $p->people_id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    <script src="{{ asset('layouts/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('layouts/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function () {
    const staffEditUrl = "{{ route('admin.staff.edit', ':id') }}";
    const staffUpdateUrl = "{{ route('admin.staff.update', ':id') }}";

    /* ==============================
     |  Datatable Init
     ============================== */
    $('#datatable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });

    /* ==============================
     |  Auto URL Name Generator
     ============================== */
    $('#title, #fname, #lname').on('input', function () {
        const title = $('#title').val().trim();
        const fname = $('#fname').val().trim();
        const lname = $('#lname').val().trim();

        if (fname || lname) {
            const fullName = [title, fname, lname].filter(Boolean).join(' ');
            const urlName = fullName.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');

            $('#url_name_display').val(urlName);
        }
    });

    /* ==============================
     |  EDIT STAFF
     ============================== */
  $('body').on('click', '.editBtn', function () {

    let id = $(this).data('id');

    fetch(staffEditUrl.replace(':id', id), {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {

        $('#edit_id').val(data.people_id);
        $('#title').val(data.title);
        $('#fname').val(data.fname);
        $('#lname').val(data.lname);
        $('#email').val(data.email);
        $('#cell_no').val(data.cell_no);
        $('#off_no').val(data.off_no);
        $('#ext').val(data.ext);
        $('#fax_no').val(data.fax_no);
        $('#url').val(data.url);
        $('#biography').val(data.biography);
        $('#research_interest').val(data.research_interest);
        $('#designation_id').val(data.designation_id);
        $('#group_id').val(data.group_id);
        $('#post_id').val(data.post_id);
        $('#seqno').val(data.seqno);
        $('#status').val(parseInt(data.status, 10) === 0 ? 'active' : 'inactive');
        $('#is_core_team').prop('checked', data.is_core_team == 1);

        // UPDATE form action
        $('#peopleForm').attr('action', staffUpdateUrl.replace(':id', id));

        if (!$('#peopleForm input[name="_method"]').length) {
            $('#peopleForm').append('<input type="hidden" name="_method" value="PUT">');
        }

        loadLabs(data.labs || []);

        $('.modal-title').text('Edit Staff');
        new bootstrap.Modal(document.getElementById('peopleModal')).show();
    })
    .catch(() => {
        Swal.fire('Error', 'Edit data load failed', 'error');
    });
});


    /* ==============================
     |  DELETE STAFF
     ============================== */
    $('body').on('click', '.deleteBtn', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                let deleteUrl = "{{ route('admin.staff.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', id);

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire('Error', data.message || 'Delete failed', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Network error occurred', 'error');
                });
            }
        });
    });

    /* ==============================
     |  RESET MODAL
     ============================== */
    $('#peopleModal').on('hidden.bs.modal', function () {
        $('#peopleForm')[0].reset();
        $('#peopleForm').attr('action', "{{ route('admin.staff.store') }}");
        $('#peopleForm input[name="_method"]').remove();
        $('#edit_id').val('');
        $('#imagePreview').empty();
        $('#labsContainer').empty();
        $('.modal-title').text('Add Staff');
    });

    /* ==============================
     |  IMAGE PREVIEW
     ============================== */
    $('#image').on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').html(`
                    <img src="${e.target.result}" class="img-thumbnail"
                         style="max-width:150px;max-height:150px;">
                `);
            }
            reader.readAsDataURL(file);
        }
    });
});

/* ==============================
 |  FORM SUBMIT
 ============================== */
$('#peopleForm').on('submit', function (e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);
    let actionUrl = form.action;

    fetch(actionUrl, {
        method: 'POST', // ALWAYS POST
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        let data = await res.json();
        if (!res.ok) throw data;
        return data;
    })
    .then(data => {
        Swal.fire('Success', data.message, 'success');
        bootstrap.Modal.getInstance(document.getElementById('peopleModal')).hide();
        setTimeout(() => location.reload(), 800);
    })
    .catch(err => {
        let msg = 'Something went wrong';
        if (err.errors) {
            msg = Object.values(err.errors).flat().join('\n');
        }
        Swal.fire('Error', msg, 'error');
    });
});


/* ==============================
 |  LABS FUNCTIONS
 ============================== */
function addLabField(labId = '', role = '') {
    const index = Date.now();
    const html = `
        <div class="row g-2 mb-2 lab-row" id="labRow${index}">
            <div class="col-md-6">
                <select name="labs[]" class="form-select form-select-sm">
                    <option value="">Select Lab/Group</option>
                    @foreach($groups as $g)
                        <option value="{{ $g->group_id }}"
                            ${labId == {{ $g->group_id }} ? 'selected' : ''}>
                            {{ $g->group_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="lab_roles[]" class="form-control form-control-sm"
                       placeholder="Role" value="${role}">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="removeLabField('${index}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    $('#labsContainer').append(html);
}

function removeLabField(id) {
    $('#labRow' + id).remove();
}

function loadLabs(labs) {
    $('#labsContainer').empty();
    labs.forEach(lab => {
        if (lab.pivot) {
            addLabField(lab.group_id, lab.pivot.role);
        }
    });
}
</script>
<script>
window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>

</body>
</html>
