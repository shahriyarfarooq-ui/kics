<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD</title>
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
                <div class="py-3 d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0">Publications</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#publicationModal">
                        Add Publication
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Table -->
         <div class="row">
             <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        

                        <table class="table table-bordered align-middle" id="datatable">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Journal</th>
                                <th>Year</th>
                                <th>Category</th>
                                <th>Completed</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($publications as $pub)
                                <tr>
                                    <td>{{ $pub->publication_id }}</td>
                                    <td>{{ Str::limit($pub->publication_title, 30) }}</td>
                                    <td>{{ $pub->author }}</td>
                                    <td>{{ Str::limit($pub->journal, 30) }}</td>
                                    <td>{{ $pub->publication_year }}</td>
                                    <td>{{ $pub->category }}</td>
                                    <td>{{ $pub->publication_iscompleted ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $pub->publication_id }}">Edit</button>
                                        <form action="{{ route('admin.publications.destroy', $pub->publication_id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $pub->publication_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <form method="POST" action="{{ route('admin.publications.update', $pub->publication_id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Publication</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body row g-3">
                                                    <div class="col-md-6">
                                                        <label>Title</label>
                                                        <textarea name="publication_title" class="form-control" required>{{ $pub->publication_title }}</textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Author</label>
                                                        <input type="text" name="author" value="{{ $pub->author }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Journal</label>
                                                        <textarea name="journal" class="form-control" required>{{ $pub->journal }}</textarea>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Volume</label>
                                                        <input type="text" name="volume" value="{{ $pub->volume }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Year</label>
                                                        <input type="number" name="publication_year" value="{{ $pub->publication_year }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Seq No</label>
                                                        <input type="number" name="publication_seqno" value="{{ $pub->publication_seqno }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Group</label>
                                                        <select name="group_id" class="form-select">
                                                            <option value="">Select Group</option>
                                                            @foreach($groups as $group)
                                                                <option value="{{ $group->group_id }}" {{ $group->group_id == $pub->group_id ? 'selected' : '' }}>
                                                                    {{ $group->group_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>People</label>
                                                        <select name="people_id" class="form-select">
                                                            <option value="">Select Person</option>
                                                            @foreach($people as $p)
                                                                <option value="{{ $p->people_id }}" {{ $p->people_id == $pub->people_id ? 'selected' : '' }}>
                                                                    {{ $p->people_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Abstract</label>
                                                        <textarea name="publication_abstract" class="form-control">{{ $pub->publication_abstract }}</textarea>
                                                    </div>
                                                    <div class="col-md-3 form-check mt-3">
                                                        <input type="checkbox" class="form-check-input" name="publication_iscompleted" {{ $pub->publication_iscompleted ? 'checked' : '' }}>
                                                        <label class="form-check-label">Completed</label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Category</label>
                                                        <input type="text" name="category" value="{{ $pub->category }}" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $publications->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>

        <!-- Add Modal -->
        <div class="modal fade" id="publicationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.publications.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Publication</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label>Title</label>
                                <textarea name="publication_title" class="form-control" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Author</label>
                                <input type="text" name="author" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Journal</label>
                                <textarea name="journal" class="form-control" required></textarea>
                            </div>
                            <div class="col-md-3">
                                <label>Volume</label>
                                <input type="text" name="volume" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Year</label>
                                <input type="number" name="publication_year" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Seq No</label>
                                <input type="number" name="publication_seqno" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Group</label>
                                <select name="group_id" class="form-select">
                                    <option value="">Select Group</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->group_id }}">{{ $group->group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>People</label>
                                <select name="people_id" class="form-select">
                                    <option value="">Select Person</option>
                                    @foreach($people as $p)
                                        <option value="{{ $p->people_id }}">{{ $p->people_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Abstract</label>
                                <textarea name="publication_abstract" class="form-control"></textarea>
                            </div>
                            <div class="col-md-3 form-check mt-3">
                                <input type="checkbox" class="form-check-input" name="publication_iscompleted" checked>
                                <label class="form-check-label">Completed</label>
                            </div>
                            <div class="col-md-6">
                                <label>Category</label>
                                <input type="text" name="category" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>

 <!-- DataTables JS -->
<script src="{{ asset('layouts/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#datatable')) {
        $('#datatable').DataTable().destroy();
    }

    var table = $('#datatable').DataTable({
        responsive: true,
        
        
        language: {
            search: "",
            searchPlaceholder: "Type to filter..."
        }
    });

    // hide built-in search if you prefer to use custom input
   // $('.dataTables_filter').hide();

   
});
</script>


</body>
</html>
