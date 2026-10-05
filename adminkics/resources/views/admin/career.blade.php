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
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <h4 class="page-title mb-0">Career</h4>
                    <button type="button" class="btn btn-primary" id="addCareerBtn">New Career</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Career Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped align-middle" id="careerTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Job Title</th>
                                            <th>Company</th>
                                            <th>Location</th>
                                            <th>Closing Date</th>
                                            <th>Tags</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($careers as $career)
                                        <tr>
                                            <td>{{ $career->id }}</td>
                                            <td>{{ $career->job_title }}</td>
                                            <td>{{ $career->company ?? '--' }}</td>
                                            <td>{{ $career->location ?? '--' }}</td>
                                            <td>{{ $career->job_close_date ?? '--' }}</td>
                                            <td>
                                                @foreach($career->tags as $tag)
                                                    <span class="badge bg-info">{{ $tag->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary editBtn"
                                                    data-id="{{ $career->id }}"
                                                    data-title="{{ $career->job_title }}"
                                                    data-group="{{ $career->group_id }}"
                                                    data-company="{{ $career->company }}"
                                                    data-location="{{ $career->location }}"
                                                    data-date="{{ $career->job_close_date }}"
                                                    data-description="{{ $career->description }}"
                                                    data-tags="{{ $career->tags->pluck('id') }}">
                                                    Edit
                                                </button>

                                                <form action="{{ route('admin.career.delete',$career->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this?')">Delete</button>
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
                <div class="modal fade" id="careerModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
    <form method="POST" id="careerForm">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Career Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="career_id">

                <!-- Row 1: Job Title + Group -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Job Title</label>
                        <input type="text" class="form-control" name="job_title" id="job_title" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label>Group</label>
                        <select class="form-control" name="group_id" id="group_id" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->group_id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Row 2: Tags + Company -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Tags</label>
                        <select class="form-control" name="tags[]" id="tags" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label>Company</label>
                        <input type="text" class="form-control" name="company" id="company">
                    </div>
                </div>

                <!-- Row 3: Location + Closing Date -->
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Location</label>
                        <input type="text" class="form-control" name="location" id="location">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label>Closing Date</label>
                        <input type="date" class="form-control" name="job_close_date" id="job_close_date">
                    </div>
                </div>

                <!-- Row 4: Description (full width) -->
                <div class="row">
                    <div class="mb-3 col-12">
                        <label>Description</label>
                        <textarea class="form-control summernote" name="description" id="description"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Save</button>
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

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.note-editable { background: white !important; }
</style>

<script>
$(document).ready(function() {
    // Summernote
    $('.summernote').summernote({ height: 200 });

    // Select2 for tags
    $('#tags').select2({
        placeholder: "Select tags",
        allowClear: true,
        width: '100%'
    });

    // Add Career button
    $('#addCareerBtn').click(function() {
        $('#careerForm').attr('action', "{{ route('admin.career.store') }}");
        $('#careerForm')[0].reset();
        $('#description').summernote('code', '');
        $('#tags').val(null).trigger('change'); // reset tags
        $('#careerModal').modal('show');
    });

    // Edit Career button
    $('.editBtn').click(function() {
        let id = $(this).data('id');

        // Set form action
        let url = "{{ url('admin/career/update') }}/" + id;
        $('#careerForm').attr('action', url);

        // Set other fields
        $('#career_id').val(id);
        $('#job_title').val($(this).data('title'));
        $('#group_id').val($(this).data('group'));
        $('#company').val($(this).data('company'));
        $('#location').val($(this).data('location'));
        $('#job_close_date').val($(this).data('date'));
        $('#description').summernote('code', $(this).data('description'));

        // Handle tags
        let tagsData = $(this).data('tags'); // expects comma-separated string: "1,3,5"
        if(tagsData){
            let selectedTags = tagsData.toString().split(','); // convert to array
            $('#tags').val(selectedTags).trigger('change');
        } else {
            $('#tags').val(null).trigger('change');
        }

        $('#careerModal').modal('show');
    });
});

</script>

</body>
</html>
