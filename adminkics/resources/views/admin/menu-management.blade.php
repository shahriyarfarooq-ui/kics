<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD - Menu Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/libs/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
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

                <div class="py-3 py-lg-4">
                    <h4 class="page-title mb-3">Menu Management</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <!-- Menu Sections -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Menu</h5>
                            <button class="btn btn-primary" id="addSectionBtn">New Menu</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="sectionsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Order Index</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($menuSections as $section)
                                    <tr>
                                        <td>{{ $section->id }}</td>
                                        <td>{{ $section->title }}</td>
                                        <td>{{ $section->order_index }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary editSectionBtn"
                                                data-id="{{ $section->id }}"
                                                data-title="{{ $section->title }}"
                                                data-order="{{ $section->order_index }}">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger deleteSectionBtn"
                                                data-id="{{ $section->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Menu Links -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Sub Menu</h5>
                            <button class="btn btn-primary" id="addLinkBtn">New Sub Menu</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="linksTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Label</th>
                                        <th>URL</th>
                                        <th>Section</th>
                                        <th>Description</th>
                                        <th>Order Index</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($menuLinks as $link)
                                    <tr>
                                        <td>{{ $link->id }}</td>
                                        <td>{{ $link->label }}</td>
                                        <td>{{ $link->url }}</td>
                                        <td>{{ $link->menuSection->title ?? '-' }}</td>
                                        <td>{{ $link->description}}</td>
                                        <td>{{ $link->order_index }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary editLinkBtn"
                                                data-id="{{ $link->id }}"
                                                data-label="{{ $link->label }}"
                                                data-url="{{ $link->url }}"
                                                data-section="{{ $link->menu_section_id }}"
                                                data-description="{{ $link->description }}"
                                                data-order="{{ $link->order_index }}">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger deleteLinkBtn"
                                                data-id="{{ $link->id }}">
                                                Delete
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
        </div>
    </div>
</div>

<!-- Menu Section Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
    <h5 class="modal-title" id="sectionModalTitle">
        <span id="sectionModalMode">New</span> Menu Section
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
            <div class="modal-body">
                <form id="sectionForm">
                    @csrf
                    <input type="hidden" id="section_id" name="id">
                    <input type="hidden" id="section_method" name="_method" value="POST">
                    
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" id="section_title" required>
                    </div>
                    <div class="mb-3">
                        <label>Order Index</label>
                        <input type="number" class="form-control" name="order_index" id="section_order" required>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="sectionSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Menu Link Modal -->
<div class="modal fade" id="linkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
    <h5 class="modal-title" id="linkModalTitle">
        <span id="linkModalMode">New</span> Menu Link
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
            <div class="modal-body">
                <form id="linkForm">
                    @csrf
                    <input type="hidden" id="link_id" name="id">
                    <input type="hidden" id="link_method" name="_method" value="POST">
                    
                    <div class="mb-3">
                        <label>Label</label>
                        <input type="text" class="form-control" name="label" id="link_label" required>
                    </div>
                    <div class="mb-3">
                        <label>URL</label>
                        <input type="text" class="form-control" name="url" id="link_url" required>
                    </div>
                    <div class="mb-3">
                        <label>Menu Section</label>
                        <select class="form-select" name="menu_section_id" id="link_section" required>
                            <option value="">Select Section</option>
                            @foreach($menuSections as $section)
                                <option value="{{ $section->id }}">{{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" class="form-control" name="description" id="link_description">
                    </div>
                    <div class="mb-3">
                        <label>Order Index</label>
                        <input type="number" class="form-control" name="order_index" id="link_order" required>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="linkSubmitBtn">Save</button>
                    </div>
                </form>
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

    // Initialize DataTables
    $('#sectionsTable, #linksTable').DataTable();

    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // ============================================
    // SECTION OPERATIONS
    // ============================================

    // Add new section
    $('#addSectionBtn').click(function() {
        $('#sectionForm')[0].reset();
        $('#section_id').val('');
        $('#section_method').val('POST');
        $('#sectionModalTitle').text('New Menu');
        $('#sectionSubmitBtn').text('Create');
        $('#sectionForm').attr('action', "{{ route('menu-sections.store') }}");
        $('#sectionModal').modal('show');
    });

    // Edit section
    $(document).on('click', '.editSectionBtn', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const order = $(this).data('order');

        // Populate form
        $('#section_id').val(id);
        $('#section_title').val(title);
        $('#section_order').val(order);
        $('#section_method').val('PUT');
        $('#sectionModalTitle').text('Edit Menu');
        $('#sectionSubmitBtn').text('Update');
        
        // Set action URL for update
        $('#sectionForm').attr('action', "{{ url('menu-sections') }}/" + id);
        
        $('#sectionModal').modal('show');
    });

    // Submit section form
    $('#sectionForm').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const method = $('#section_method').val();
        
        // Collect form data
        const formData = {
            title: $('#section_title').val(),
            order_index: $('#section_order').val()
        };

        // Disable submit button
        $('#sectionSubmitBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                    $('#sectionSubmitBtn').prop('disabled', false).text('Save');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error occurred!';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                    }
                }
                alert(errorMsg);
                $('#sectionSubmitBtn').prop('disabled', false).text('Save');
            }
        });
    });

    // Delete section
    $(document).on('click', '.deleteSectionBtn', function() {
        if (!confirm('Delete this section? This will also delete all links under it.')) return;
        
        const id = $(this).data('id');
        
        if (!confirm('Are you sure you want to delete section #' + id + '?')) return;
        
        $.ajax({
            url: "{{ url('menu-sections') }}/" + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Delete failed'));
                }
            },
            error: function(xhr) {
                alert('Error deleting section');
            }
        });
    });

    // ============================================
    // LINK OPERATIONS
    // ============================================

    // Add new link
    $('#addLinkBtn').click(function() {
        $('#linkForm')[0].reset();
        $('#link_id').val('');
        $('#link_method').val('POST');
        $('#linkModalTitle').text('New Sub Menu');
        $('#linkSubmitBtn').text('Create');
        $('#linkForm').attr('action', "{{ route('menu-links.store') }}");
        $('#linkModal').modal('show');
    });

    // Edit link
    $(document).on('click', '.editLinkBtn', function() {
        const id = $(this).data('id');
        const label = $(this).data('label');
        const urlVal = $(this).data('url');
        const section = $(this).data('section');
        const description = $(this).data('description');
        const order = $(this).data('order');

        // Populate form
        $('#link_id').val(id);
        $('#link_label').val(label);
        $('#link_url').val(urlVal);
        $('#link_section').val(section);
        $('#link_description').val(description || '');
        $('#link_order').val(order);
        $('#link_method').val('PUT');
        $('#linkModalTitle').text('Edit Sub Menu');
        $('#linkSubmitBtn').text('Update');
        
        // Set action URL for update
        $('#linkForm').attr('action', "{{ url('menu-links') }}/" + id);
        
        $('#linkModal').modal('show');
    });

    // Submit link form
    $('#linkForm').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const method = $('#link_method').val();
        
        // Collect form data
        const formData = {
            menu_section_id: $('#link_section').val(),
            label: $('#link_label').val(),
            url: $('#link_url').val(),
            description: $('#link_description').val(),
            order_index: $('#link_order').val()
        };

        // Disable submit button
        $('#linkSubmitBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                    $('#linkSubmitBtn').prop('disabled', false).text('Save');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error occurred!';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                    }
                }
                alert(errorMsg);
                $('#linkSubmitBtn').prop('disabled', false).text('Save');
            }
        });
    });

    // Delete link
    $(document).on('click', '.deleteLinkBtn', function() {
        if (!confirm('Delete this sub menu?')) return;
        
        const id = $(this).data('id');
        
        $.ajax({
            url: "{{ url('menu-links') }}/" + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Delete failed'));
                }
            },
            error: function(xhr) {
                alert('Error deleting sub menu');
            }
        });
    });

});
</script>

</body>
</html>