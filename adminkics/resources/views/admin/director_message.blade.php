<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>Director Message | Admin Dashboard</title>
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
                        <h4 class="page-title mb-0">Director Message</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#directorModal" id="addDirectorBtn">
                            Add Director Message
                        </button>
                    </div>

                    <!-- Add/Edit Modal -->
                    <div class="modal fade" id="directorModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Add Director Message</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="directorForm">
                                        @csrf
                                        <input type="hidden" id="director_id">
                                        <div class="mb-3">
                                            <label for="section_message" class="form-label">Director Message</label>
                                            <textarea class="form-control summernote" id="section_message" name="section_message" rows="3" required></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                    <!-- Director Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped align-middle" id="directorTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Message</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($director as $item)
                                                <tr id="row_{{ $item->id }}">
                                                    <td>{{ $item->id }}</td>
                                                    <td>{!! $item->section_message !!}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $item->id }}">Edit</button>
                                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}">Delete</button>
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
        $('#directorTable').DataTable();

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

        // Open modal for adding
        $('#addDirectorBtn').click(function() {
            $('#directorForm')[0].reset();
            $('#director_id').val('');
            $('#modalTitle').text('Add Director Message');
            $('#saveBtn').text('Save');
            $('.summernote').summernote('code', '');
        });

        // Save or update
        $('#directorForm').submit(function(e) {
            e.preventDefault();
            let id = $('#director_id').val();
            let url = id ? "{{ url('admin/director_message/update') }}/" + id : "{{ route('director_message.store') }}";

            // Get Summernote content
            $('#section_message').val($('#section_message').summernote('code'));

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    Swal.fire('Success', res.success, 'success').then(() => location.reload());
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
            $.get("{{ url('admin/director_message/edit') }}/" + id, function(data) {
                $('#director_id').val(data.id);
                $('#section_message').summernote('code', data.section_message);
                $('#modalTitle').text('Edit Director Message');
                $('#saveBtn').text('Update');
                $('#directorModal').modal('show');
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
                        url: "{{ url('admin/director_message/delete') }}/" + id,
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        success: function(res) {
                            Swal.fire('Deleted!', res.success, 'success');
                            $('#row_' + id).remove();
                        },
                        error: function() {
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
