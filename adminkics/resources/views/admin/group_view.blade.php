<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>Group Details - {{ $group->group_name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="layout-wrapper">
    <div class="main-menu">
        @include('components.logo')
        @include('components.sidebar')
    </div>

    <div class="page-content">
        @include('components.topbar')

        <div class="container py-4">
            <h3 class="mb-4">{{ $group->group_name }} (ID: {{ $group->group_id }})</h3>

            <ul class="nav nav-tabs" id="groupTabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#info">Info</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#projects">Projects</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#people">People</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#news">News</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#publications">Publications</a></li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="info">
                    <h5>Group Description</h5>
                    <p>{{ $group->group_description }}</p>
                    <p><strong>Code:</strong> {{ $group->code }}</p>
                    <p><strong>Contact:</strong> {{ $group->contact_us }}</p>
                </div>

                <div class="tab-pane fade" id="projects">
    <h5>Projects</h5>
    @if($group->projects->isEmpty())
        <p>No projects found for this group.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Project Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group->projects as $project)
                <tr>
                    <td>{{ $project->projectlist_id }}</td>
                    <td>{{ Str::limit($project->projectlist_Name, 50) }}</td>
                    <td>{{ Str::limit($project->projectlist_description, 50) }}</td>
                    <td>{{ $project->is_completed ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-project" data-id="{{ $project->projectlist_id }}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-project" data-id="{{ $project->projectlist_id }}">Delete</button>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


 <div class="tab-pane fade" id="people">
    <h5>Staff Members</h5>

    @if($group->staff->isEmpty())
        <p>No staff assigned to this lab yet.</p>
    @else
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                    <th>Role (in this Lab)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group->staff as $person)
                <tr>
                    <td>{{ $person->people_id }}</td>
                    <td>{{ $person->fname }} {{ $person->lname }}</td>
                    <td>{{ $person->email }}</td>
                    <td>{{ $person->designation->designation_name ?? 'N/A' }}</td>
                    <td>
                        {{-- role comes from pivot table staff_lab --}}
                        {{ $person->pivot->role ?? 'Member' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.people.edit', $person->people_id) }}" 
                           class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.people.destroy', $person->people_id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Remove this staff member?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>



            


               <div class="tab-pane fade" id="publications">
    <h5>Publications</h5>
    @if($group->publications->isEmpty())
        <p>No publications found for this group.</p>
    @else
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group->publications as $pub)
                <tr>
                    <td>{{ $pub->publication_id }}</td>
                    <td>{{ $pub->publication_title }}</td>
                    <td>{{ $pub->author ?? 'N/A' }}</td>
                    <td>{{ $pub->publication_year ?? 'N/A' }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

            </div>

            <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary mt-4">Back to Groups</a>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editProjectForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="edit_project_id">

          <div class="mb-3">
            <label>Project Name</label>
            <input type="text" class="form-control" id="edit_project_name" name="projectlist_Name" required>
          </div>

          <div class="mb-3">
            <label>Description</label>
            <textarea class="form-control" id="edit_project_description" name="projectlist_description"></textarea>
          </div>

          <div class="mb-3">
            <label>Category</label>
            <input type="text" class="form-control" id="edit_project_category" name="project_category">
          </div>

          <div class="mb-3">
            <label>Status</label>
            <select class="form-control" id="edit_project_status" name="is_completed">
              <option value="0">Ongoing</option>
              <option value="1">Completed</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>

<script>
$(document).ready(function() {

    // 🟡 Edit Project
    $('.edit-project').click(function() {
        var id = $(this).data('id');
        $.get("{{ url('projects') }}/" + id + "/edit", function(data) {
            $('#edit_project_id').val(data.projectlist_id);
            $('#edit_project_name').val(data.projectlist_Name);
            $('#edit_project_description').val(data.projectlist_description);
            $('#edit_project_category').val(data.project_category);
            $('#edit_project_status').val(data.is_completed);
            $('#editProjectModal').modal('show');
        });
    });

    // 🟢 Update Project
    $('#editProjectForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_project_id').val();
        $.ajax({
            url: "{{ url('projects') }}/" + id + "/update",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });

    // 🔴 Delete Project
    $('.delete-project').click(function() {
        if (!confirm('Are you sure you want to delete this project?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: "{{ url('projects') }}/" + id + "/delete",
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });

});
</script>

</body>
</html>
