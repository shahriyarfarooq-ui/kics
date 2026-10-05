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



    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS for tags multi-select -->
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
                    <h4 class="page-title mb-0">News</h4>
                    <button type="button" class="btn btn-primary" id="addGroupBtn">New News</button>
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
                            <img src="{{ asset('storage/'.$n->thumbnail) }}" width="60" height="60">
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
<div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <form id="newsForm" enctype="multipart/form-data">
    <div class="modal-header">
      <h5 class="modal-title" id="newsModalLabel">Add News</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <label>Tags</label>
                <select name="tags[]" id="tags" class="form-control" multiple="multiple">
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

        <!-- Row 3: Description (full width) -->
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


<script>
$(document).ready(function() {

    // Initialize Select2 for tags
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

    // Open Add News Modal
    $('#addNewsBtn').click(function() {
        $('#newsForm')[0].reset();
        $('#description').summernote('code', ''); // clear Summernote content
        $('#tags').val(null).trigger('change');
        $('#news_id').val('');
        $('#thumbnailPreview').html('');
        $('#newsModalLabel').text('Add News');
        $('#newsModal').modal('show');
    });

    // Edit News
    $(document).on('click', '.editNewsBtn', function() {
        var id = $(this).data('id');
        $.get("/admin/news/edit/"+id, function(data) {
            $('#newsModalLabel').text('Edit News');
            $('#news_id').val(data.id);
            $('#title').val(data.title);
            $('#category_id').val(data.category_id);
            $('#description').summernote('code', data.description || ''); // load content into Summernote
            var tagIds = data.tags.map(tag => tag.id);
            $('#tags').val(tagIds).trigger('change');
            if(data.thumbnail) {
                $('#thumbnailPreview').html('<img src="/storage/'+data.thumbnail+'" width="100">');
            } else { 
                $('#thumbnailPreview').html(''); 
            }
            $('#newsModal').modal('show');
        });
    });

    // Save News (Add/Update)
    $('#newsForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var newsId = $('#news_id').val();
        var url = newsId ? '/admin/news/update/'+newsId : '/admin/news/store';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType:false,
            processData:false,
            success:function(res) {
                alert(res.success);
                location.reload(); // simple reload to refresh table
            },
            error:function(err){
                console.log(err);
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
            url: '/admin/news/delete/'+id,
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


<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>


</body>
</html>