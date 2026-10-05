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
                               <!-- Table and Modal Section -->
<table class="table table-striped align-middle" id="groupsTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($categories as $cat)
        <tr>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->name }}</td>
            <td>
                <button class="btn btn-sm btn-warning editBtn" 
                        data-id="{{ $cat->id }}" 
                        data-name="{{ $cat->name }}">Edit</button>

                <a href="{{ route('admin.news_categories.delete', $cat->id) }}" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this category?')">Delete</a>
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
            
<!-- Add/Edit Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="groupForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="categoryId">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" id="categoryName" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
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
<script>
$(document).ready(function() {

    // Open Add Modal
    $("#addGroupBtn").click(function () {
        $("#groupForm").attr("action", "{{ route('admin.news_categories.store') }}");
        $("#categoryName").val('');
        $("#categoryId").val('');
        $("#groupModal .modal-title").text("Add Category");
        $("#groupModal").modal('show');
    });

    // Open Edit Modal
    $(".editBtn").click(function () {
        let id = $(this).data('id');
        let name = $(this).data('name');

        $("#categoryId").val(id);
        $("#categoryName").val(name);
        $("#groupModal .modal-title").text("Edit Category");

        // Update form action dynamically
        $("#groupForm").attr("action", "/admin/news-categories/update/" + id);

        $("#groupModal").modal('show');
    });

    // Handle form submission via AJAX
    $("#groupForm").submit(function(e){
        e.preventDefault(); // prevent normal form submission

        let formAction = $(this).attr('action');
        let name = $("#categoryName").val();
        let id = $("#categoryId").val();

        // Determine method: POST for Add, PUT for Edit
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: formAction,
            type: "POST", // always POST for AJAX
            data: {
                _token: "{{ csrf_token() }}",
                _method: method,
                name: name
            },
            success: function(response){
                $("#groupModal").modal('hide'); // close modal
                alert(response.success || 'Category saved successfully!');
                location.reload(); // reload table
            },
            error: function(xhr){
                let msg = xhr.responseJSON?.error || 'Something went wrong!';
                alert(msg);
            }
        });
    });

});
</script>






</body>