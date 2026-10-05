<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>Vision Section | Admin Dashboard</title>
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
                        <h4 class="page-title mb-0">Vision Sections</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visionModal" id="addvisionBtn">
                            Add Vision Section
                        </button>
                    </div>

                    <!-- Add/Edit Modal -->
                    <div class="modal fade" id="visionModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Add Vision Section</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="visionForm">
                                        @csrf
                                        <input type="hidden" id="vision_id">
                                        <div class="mb-3">
                                            <label for="section_1" class="form-label">Section 1</label>
                                            <textarea class="form-control summernote" id="section_vision" name="section_vision" rows="3" required></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                    <!-- vision Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped align-middle" id="visionTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Section 1</th>
                                                
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($visions as $vision)
                                                <tr id="row_{{ $vision->id }}">
                                                    <td>{{ $vision->id }}</td>
                                                    <td>{!! $vision->section_vision !!}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $vision->id }}">Edit</button>
                                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $vision->id }}">Delete</button>
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
        $('#visionTable').DataTable();

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

        // Open modal for adding new vision
        $('#addvisionBtn').click(function() {
            $('#visionForm')[0].reset();
            $('#vision_id').val('');
            $('#modalTitle').text('Add vision Section');
            $('#saveBtn').text('Save');
            $('.summernote').summernote('code', '');
        });

        // Save or update vision
        $('#visionForm').submit(function(e) {
            e.preventDefault();
            let id = $('#vision_id').val();
            let url = id ? "{{ url('admin/vision/update') }}/" + id : "{{ route('vision.store') }}";

            // Update Summernote content
            $('#section_vision').val($('#section_vision').summernote('code'));

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
            $.get("{{ url('admin/vision/edit') }}/" + id, function(data) {
                $('#vision_id').val(data.id);
                $('#section_vision').summernote('code', data.section_vision);
                $('#modalTitle').text('Edit Vision Section');
                $('#saveBtn').text('Update');
                $('#visionModal').modal('show');
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
                        url: "{{ url('admin/vision/delete') }}/" + id,
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
