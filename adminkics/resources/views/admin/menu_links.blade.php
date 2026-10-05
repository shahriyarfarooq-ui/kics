<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Links</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Menu Links</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMenuLinkModal">
                        <i class="fas fa-plus"></i> Add Link
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Label</th>
                                        <th>URL</th>
                                        <th>Section</th>
                                        <th>Order Index</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menuLinks as $link)
                                    <tr>
                                        <td>{{ $link->label }}</td>
                                        <td>{{ $link->url }}</td>
                                        <td>{{ $link->menuSection->title }}</td>
                                        <td>{{ $link->order_index }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editMenuLinkModal"
                                                    data-id="{{ $link->id }}"
                                                    data-menu-section-id="{{ $link->menu_section_id }}"
                                                    data-label="{{ $link->label }}"
                                                    data-url="{{ $link->url }}"
                                                    data-description="{{ $link->description }}"
                                                    data-order-index="{{ $link->order_index }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteMenuLinkModal"
                                                    data-id="{{ $link->id }}"
                                                    data-label="{{ $link->label }}">
                                                <i class="fas fa-trash"></i>
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

    <!-- Create Menu Link Modal -->
    <div class="modal fade" id="createMenuLinkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('menu-links.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Menu Link</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="menu_section_id" class="form-label">Menu Section *</label>
                            <select class="form-control" id="menu_section_id" name="menu_section_id" required>
                                <option value="">Select Section</option>
                                @foreach($menuSections as $section)
                                    <option value="{{ $section->id }}">{{ $section->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="label" class="form-label">Label *</label>
                            <input type="text" class="form-control" id="label" name="label" required>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">URL *</label>
                            <input type="text" class="form-control" id="url" name="url" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="order_index" class="form-label">Order Index *</label>
                            <input type="number" class="form-control" id="order_index" name="order_index" value="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Menu Link Modal -->
    <div class="modal fade" id="editMenuLinkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editMenuLinkForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Menu Link</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_menu_section_id" class="form-label">Menu Section *</label>
                            <select class="form-control" id="edit_menu_section_id" name="menu_section_id" required>
                                <option value="">Select Section</option>
                                @foreach($menuSections as $section)
                                    <option value="{{ $section->id }}">{{ $section->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_label" class="form-label">Label *</label>
                            <input type="text" class="form-control" id="edit_label" name="label" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_url" class="form-label">URL *</label>
                            <input type="text" class="form-control" id="edit_url" name="url" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_order_index" class="form-label">Order Index *</label>
                            <input type="number" class="form-control" id="edit_order_index" name="order_index" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Menu Link Modal -->
    <div class="modal fade" id="deleteMenuLinkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteMenuLinkForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Menu Link</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete "<span id="deleteLinkLabel"></span>"?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Modal Script
        document.getElementById('editMenuLinkModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const menuSectionId = button.getAttribute('data-menu-section-id');
            const label = button.getAttribute('data-label');
            const url = button.getAttribute('data-url');
            const description = button.getAttribute('data-description');
            const orderIndex = button.getAttribute('data-order-index');
            
            const form = document.getElementById('editMenuLinkForm');
            form.action = `/menu-links/${id}`;
            
            document.getElementById('edit_menu_section_id').value = menuSectionId;
            document.getElementById('edit_label').value = label;
            document.getElementById('edit_url').value = url;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_order_index').value = orderIndex;
        });

        // Delete Modal Script
        document.getElementById('deleteMenuLinkModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const label = button.getAttribute('data-label');
            
            const form = document.getElementById('deleteMenuLinkForm');
            form.action = `/menu-links/${id}`;
            
            document.getElementById('deleteLinkLabel').textContent = label;
        });
    </script>
</body>
</html>