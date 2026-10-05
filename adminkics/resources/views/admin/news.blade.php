<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD - News</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <h4 class="page-title mb-0">News Management</h4>
                    <button type="button" class="btn btn-primary" id="addNewsBtn">Add News</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- News Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped align-middle" id="newsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Thumbnail</th>
                                            <th>Tags</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($news as $n)
                                        <tr id="newsRow{{ $n->id }}">
                                            <td>{{ $n->id }}</td>
                                            <td>{{ $n->title }}</td>
                                            <td>{{ $n->category->name ?? '' }}</td>
                                            <td>
                                                @if($n->thumbnail)
                                                    <img src="{{ asset('storage/'.$n->thumbnail) }}" width="40" height="40" alt="thumbnail">
                                                @endif
                                            </td>
                                            <td>
                                                @foreach($n->tags as $tag)
                                                    <span class="badge bg-info">{{ $tag->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning editNewsBtn" data-id="{{ $n->id }}">Edit</button>
                                                <button class="btn btn-sm btn-danger deleteNewsBtn" data-id="{{ $n->id }}">Delete</button>
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
                <div class="modal fade" id="newsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="newsForm" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newsModalLabel">Add News</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="news_id" id="news_id">

                                    <!-- Row 1: Title + Category -->
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label>Title</label>
                                            <input type="text" name="title" id="title" class="form-control" required>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label>Category</label>
                                            <select name="category_id" id="category_id" class="form-control" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Row 2: Tags + Thumbnail -->
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label text-white">Tags</label>
                                            <select name="tags[]" id="tags" class="form-control bg-black text-black" multiple="multiple">
                                                @foreach($tags as $tag)
                                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label>Thumbnail</label>
                                            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                                            <div id="thumbnailPreview" class="mt-2"></div>
                                        </div>
                                    </div>

                                    <!-- Row 3: Description -->
                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label>Description</label>
                                            <textarea name="description" id="description" class="form-control" rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="saveNewsBtn">Save</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
    $('#newsTable').DataTable();

    // Initialize Select2
    $('#tags').select2({ width: '100%' });

    // Initialize Summernote
    $('#description').summernote({
        height: 300,
        placeholder: 'Enter news description...',
    });

    // CSRF setup for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Prepare URLs using Laravel helpers
    var storeUrl = "{{ route('news.store') }}";
    var updateUrlTemplate = "{{ url('admin/news/update') }}/"; // append ID
    var editUrlTemplate = "{{ url('admin/news/edit') }}/";
    var deleteUrlTemplate = "{{ url('admin/news/delete') }}/";
    // Base URL for storage assets
    var storageBase = "{{ asset('storage') }}";

    // Add News Modal
    $('#addNewsBtn').click(function() {
        $('#newsForm')[0].reset();
        $('#description').summernote('code', '');
        $('#tags').val(null).trigger('change');
        $('#news_id').val('');
        $('#thumbnailPreview').html('');
        $('#newsModalLabel').text('Add News');
        $('#newsModal').modal('show');
    });

    // Edit News
    $(document).on('click', '.editNewsBtn', function() {
        var id = $(this).data('id');
        $.get(editUrlTemplate + id, function(data) {
            $('#newsModalLabel').text('Edit News');
            $('#news_id').val(data.id);
            $('#title').val(data.title);
            $('#category_id').val(data.category_id);
            $('#description').summernote('code', data.description || '');
            var tagIds = data.tags.map(tag => tag.id);
            $('#tags').val(tagIds).trigger('change');
            if(data.thumbnail){
                $('#thumbnailPreview').html('<img src="'+ storageBase + '/' + data.thumbnail + '" width="100">');
            } else {
                $('#thumbnailPreview').html('');
            }
            $('#newsModal').modal('show');
        });
    });

    // Save News (Add/Update)
    $('#newsForm').submit(function(e) {
        e.preventDefault();
        // Ensure Summernote HTML is written back into the textarea before we build FormData.
        $('#description').val($('#description').summernote('code'));

        var formData = new FormData(this);
        var newsId = $('#news_id').val();
        var url = newsId ? updateUrlTemplate + newsId : storeUrl;

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType:false,
            processData:false,
            success:function(res) {
                console.log('News save response:', res);
                alert(res.success + '\nthumbnail: ' + (res.news && res.news.thumbnail ? res.news.thumbnail : 'none'));
                location.reload();
            },
            error:function(err){
                console.log(err); // check console for exact error
                alert('Something went wrong!');
            }
        });
    });

    // Delete News
    $(document).on('click', '.deleteNewsBtn', function() {
        if(!confirm('Are you sure?')) return;
        var id = $(this).data('id');
        $.ajax({
            type: 'DELETE',
            url: deleteUrlTemplate + id,
            success:function(res){
                alert(res.success);
                $('#newsRow'+id).remove();
            },
            error:function(err){
                console.log(err);
                alert('Delete failed!');
            }
        });
    });
});
</script>


</body>
</html>
