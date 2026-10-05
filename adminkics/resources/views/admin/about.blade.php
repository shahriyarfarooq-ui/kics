<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>About Section | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
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
                        <h4 class="page-title mb-0">About Sections</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aboutModal" id="addAboutBtn">
                            Add About Section
                        </button>
                    </div>

                    <!-- Add/Edit Modal -->
                    <div class="modal fade" id="aboutModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Add About Section</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="aboutForm">
                                        @csrf
                                        <input type="hidden" id="about_id">
                                        <div class="mb-3">
                                            <label for="section_1" class="form-label">Section 1</label>
                                            <textarea class="form-control summernote" id="section_1" name="section_1" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="section_2" class="form-label">Section 2</label>
                                            <textarea class="form-control summernote" id="section_2" name="section_2" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                    <!-- About Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped align-middle" id="aboutTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Section 1</th>
                                                <th>Section 2</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($abouts as $about)
                                                <tr id="row_{{ $about->id }}">
                                                    <td>{{ $about->id }}</td>
                                                    <td>{!! $about->section_1 !!}</td>
                                                    <td>{!! $about->section_2 !!}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $about->id }}">Edit</button>
                                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $about->id }}">Delete</button>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#aboutTable').DataTable();

        // Initialize Summernote
        $('.summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Open modal for adding new about
        $('#addAboutBtn').click(function() {
            $('#aboutForm')[0].reset();
            $('#about_id').val('');
            $('#modalTitle').text('Add About Section');
            $('#saveBtn').text('Save');
            $('.summernote').summernote('code', '');
        });

        // Save or update about
        $('#aboutForm').submit(function(e) {
            e.preventDefault();
            let id = $('#about_id').val();
            let url = id ? "{{ url('admin/about/update') }}/" + id : "{{ route('about.store') }}";

            // Update Summernote content
            $('#section_1').val($('#section_1').summernote('code'));
            $('#section_2').val($('#section_2').summernote('code'));

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    Swal.fire('Success', res.success, 'success').then(() => {
                        location.reload(); // you can replace this with dynamic row update if needed
                    });
                },
                error: function(err) {
                    let errors = err.responseJSON.errors;
                    let msg = '';
                    $.each(errors, function(key, value) {
                        msg += value + '<br>';
                    });
                    Swal.fire('Error', msg, 'error');
                }
            });
        });

        // Edit button
        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get("{{ url('admin/about/edit') }}/" + id, function(data) {
                $('#about_id').val(data.id);
                $('#section_1').summernote('code', data.section_1);
                $('#section_2').summernote('code', data.section_2);
                $('#modalTitle').text('Edit About Section');
                $('#saveBtn').text('Update');
                $('#aboutModal').modal('show');
            });
        });

        // Delete button
        $(document).on('click', '.deleteBtn', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/about/delete') }}/" + id,
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        success: function(res) {
                            Swal.fire('Deleted!', res.success, 'success');
                            $('#row_' + id).remove();
                        },
                        error: function(err) {
                            Swal.fire('Error', 'Failed to delete. Please try again.', 'error');
                        }
                    });
                }
            });
        });

    });
    </script>
</body>
</html>
