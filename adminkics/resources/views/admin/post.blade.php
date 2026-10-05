 <!---------------------------------------------STAFF POST--------------------------------------------->
<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD - Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    <script src="{{ asset('layouts/assets/js/config.js') }}"></script>
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
                    <h4 class="page-title mb-0">Staff Post</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">
                        Add Post
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Posts Table -->
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Post Name</th>
                                    <th>Seq No</th>
                                    <th>Designation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->post_id }}</td>
                                        <td>{{ $post->post_name }}</td>
                                        <td>{{ $post->post_seqno }}</td>
                                        <td>{{ $post->designation->designation_name ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning editBtn"
                                                data-id="{{ $post->post_id }}"
                                                data-name="{{ $post->post_name }}"
                                                data-seq="{{ $post->post_seqno }}"
                                                data-des="{{ $post->des_id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <form id="postForm" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalTitle">Add Post</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Post Name</label>
                                        <input type="text" class="form-control" name="post_name" id="post_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sequence No</label>
                                        <input type="number" class="form-control" name="post_seqno" id="post_seqno" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Designation</label>
                                        <select class="form-select" name="des_id" id="des_id" required>
                                            <option value="" disabled selected>Select Designation</option>
                                            @foreach($designations as $des)
                                                <option value="{{ $des->designation_id }}">{{ $des->designation_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('postModal'));
    const form = document.getElementById('postForm');
    const title = document.getElementById('modalTitle');
    const nameField = document.getElementById('post_name');
    const seqField = document.getElementById('post_seqno');
    const desSelect = document.getElementById('des_id');

    // Add mode
    document.querySelector('[data-bs-target="#postModal"]').addEventListener('click', () => {
        form.action = "{{ route('posts.store') }}";
        form.method = "POST";
        form.reset();
        title.innerText = "Add Post";
    });

    // Edit mode
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const seq = btn.getAttribute('data-seq');
            const des = btn.getAttribute('data-des');

            title.innerText = "Edit Post";
            form.action = `/admin/posts/update/${id}`;
            form.method = "POST";

            nameField.value = name;
            seqField.value = seq;
            desSelect.value = des;

            modal.show();
        });
    });
});
</script>

</body>
</html>
