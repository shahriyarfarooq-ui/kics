<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD - Designation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    <script src="{{ asset('layouts/assets/js/config.js') }}"></script>
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
                    <h4 class="page-title mb-0">Designations</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#designationModal">
                        Add Designation
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Designations Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped align-middle" id="designationTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Seq No</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($designations as $designation)
                                            <tr>
                                                <td>{{ $designation->designation_id }}</td>
                                                <td>{{ $designation->designation_name }}</td>
                                                <td>{{ $designation->designation_seqno }}</td>
                                                <td>
                                                    @if($designation->designation_image)
                                                        <img src="{{ asset('uploads/designations/'.$designation->designation_image) }}" width="50" height="50" class="rounded">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning editBtn"
                                                        data-id="{{ $designation->designation_id }}"
                                                        data-name="{{ $designation->designation_name }}"
                                                        data-seq="{{ $designation->designation_seqno }}"
                                                        data-image="{{ $designation->designation_image }}">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('designations.destroy', $designation->designation_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this designation?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add/Edit Modal -->
                <div class="modal fade" id="designationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="designationForm" method="POST" enctype="multipart/form-data">
                           @csrf

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Add Designation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Designation Name</label>
                                        <input type="text" class="form-control" name="designation_name" id="designation_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sequence No</label>
                                        <input type="number" class="form-control" name="designation_seqno" id="designation_seqno" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Designation Image</label>
                                        <input type="file" class="form-control" name="designation_image" id="designation_image">
                                        <img id="previewImage" src="" width="80" class="mt-2 d-none">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('designationModal'));
    const form = document.getElementById('designationForm');
    const title = document.getElementById('modalTitle');
    const nameField = document.getElementById('designation_name');
    const seqField = document.getElementById('designation_seqno');
    const imgPreview = document.getElementById('previewImage');
    const imgInput = document.getElementById('designation_image');

    // Preview image
    imgInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.classList.remove('d-none');
        }
    });

    // Add mode
    document.querySelector('[data-bs-target="#designationModal"]').addEventListener('click', () => {
        form.action = "{{ route('designations.store') }}";
        form.method = "POST";
        form.reset();
        imgPreview.classList.add('d-none');
        title.innerText = "Add Designation";

        const method = form.querySelector('input[name="_method"]');
        if (method) method.remove();
    });

    // Edit mode
  // Edit mode
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        const seq = btn.getAttribute('data-seq');
        const image = btn.getAttribute('data-image');

        title.innerText = "Edit Designation";

        form.action = "{{ url('adminkics/public/designations/update') }}/" + id;
        form.method = "POST";

        nameField.value = name;
        seqField.value = seq;

        if (image) {
            imgPreview.src = `/uploads/designations/${image}`;
            imgPreview.classList.remove('d-none');
        } else {
            imgPreview.classList.add('d-none');
        }

        modal.show();
    });
});

});

</script>

</body>
</html>
