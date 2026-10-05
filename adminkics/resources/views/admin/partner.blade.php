{{-- resources/views/admin/partner.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD - Partners</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        .partner-logo-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-preview {
            max-width: 150px;
            margin-top: 10px;
            border-radius: 8px;
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
                    <h4 class="page-title mb-0">Partners Management</h4>
                    <button type="button" class="btn btn-primary" id="addPartnerBtn">Add Partner</button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Partners Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped align-middle" id="partnersTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Title</th>
                                            <th>Link</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($partners as $partner)
                                        <tr id="partnerRow{{ $partner->id }}">
                                            <td>{{ $partner->id }}</td>
                                            <td>
                                                @if($partner->logo)
                                                    <img src="{{ asset('storage/'.$partner->logo) }}" 
                                                         class="partner-logo-img" alt="Logo">
                                                @else
                                                    <span class="text-muted">No logo</span>
                                                @endif
                                             </td>
                                            <td>{{ $partner->title }}</td>
                                            <td>
                                                @if($partner->link)
                                                    <a href="{{ $partner->link }}" target="_blank">
                                                        {{ Str::limit($partner->link, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No link</span>
                                                @endif
                                             </td>
                                            <td>{{ $partner->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning editPartnerBtn" data-id="{{ $partner->id }}">Edit</button>
                                                <button class="btn btn-sm btn-danger deletePartnerBtn" data-id="{{ $partner->id }}">Delete</button>
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
                <div class="modal fade" id="partnerModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="partnerForm" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="partnerModalLabel">Add Partner</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="partner_id" id="partner_id">

                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label>Title *</label>
                                            <input type="text" name="title" id="title" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label>Logo</label>
                                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                            <div id="logoPreview" class="mt-2"></div>
                                            <small class="text-muted">Supported formats: JPG, PNG, GIF (Max 2MB)</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-12">
                                            <label>Link (URL)</label>
                                            <input type="url" name="link" id="link" class="form-control" placeholder="https://example.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="savePartnerBtn">Save</button>
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
    // Initialize DataTable
    $('#partnersTable').DataTable();

    // CSRF setup for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Prepare URLs using Laravel routes
    var storeUrl = "{{ route('partners.store') }}";
    var updateUrlTemplate = "{{ url('admin/partners/update') }}/";
    var editUrlTemplate = "{{ url('admin/partners/edit') }}/";
    var deleteUrlTemplate = "{{ url('admin/partners/delete') }}/";

    // Add Partner Modal
    $('#addPartnerBtn').click(function() {
        $('#partnerForm')[0].reset();
        $('#partner_id').val('');
        $('#logoPreview').html('');
        $('#partnerModalLabel').text('Add Partner');
        $('#partnerModal').modal('show');
    });

    // Edit Partner
    $(document).on('click', '.editPartnerBtn', function() {
        var id = $(this).data('id');
        $.get(editUrlTemplate + id, function(data) {
            $('#partnerModalLabel').text('Edit Partner');
            $('#partner_id').val(data.id);
            $('#title').val(data.title);
            $('#link').val(data.link || '');
            
            if(data.logo){
                $('#logoPreview').html('<img src="/storage/'+data.logo+'" class="image-preview" alt="Current Logo"><br><small class="text-muted">Current logo</small>');
            } else {
                $('#logoPreview').html('');
            }
            $('#partnerModal').modal('show');
        });
    });

    // Handle file upload preview
    $('#logo').change(function(e) {
        var file = e.target.files[0];
        if(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').html('<img src="'+e.target.result+'" class="image-preview" alt="Preview"><br><small class="text-muted">New logo preview</small>');
            }
            reader.readAsDataURL(file);
        }
    });

    // Save Partner (Add/Update)
    $('#partnerForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var partnerId = $('#partner_id').val();
        var url = partnerId ? updateUrlTemplate + partnerId : storeUrl;

        $('#savePartnerBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                alert(res.success);
                location.reload();
            },
            error: function(err) {
                console.log(err);
                if(err.responseJSON && err.responseJSON.errors) {
                    var errors = err.responseJSON.errors;
                    var errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    alert(errorMsg);
                } else {
                    alert('Something went wrong!');
                }
            },
            complete: function() {
                $('#savePartnerBtn').prop('disabled', false).text('Save');
            }
        });
    });

    // Delete Partner
    $(document).on('click', '.deletePartnerBtn', function() {
        if(!confirm('Are you sure you want to delete this partner?')) return;
        
        var id = $(this).data('id');
        $.ajax({
            type: 'DELETE',
            url: deleteUrlTemplate + id,
            success: function(res) {
                alert(res.success);
                $('#partnerRow'+id).remove();
                // Reload DataTable
                $('#partnersTable').DataTable().row('#partnerRow'+id).remove().draw();
            },
            error: function(err) {
                console.log(err);
                alert('Delete failed!');
            }
        });
    });
});
</script>

</body>
</html>