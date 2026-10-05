@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title">{{ $erpDepartment->name }}</h4>
                    <p class="text-muted mb-0">
                        Department Code: {{ $erpDepartment->dept_code ?? 'N/A' }} 
                        @if($erpDepartment->manager)
                            | Manager: {{ $erpDepartment->manager }}
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.erp.departments.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left me-1"></i> Back to Departments
                    </a>
                    <a href="{{ route('admin.erp.sync') }}" class="btn btn-success">
                        <i class="bi bi-arrow-repeat me-1"></i> Sync
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-folder me-2"></i>
                        Projects in {{ $erpDepartment->name }}
                        <span class="badge bg-primary rounded-pill ms-2">{{ $projects->total() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Project Name</th>
                                    <th>Manager</th>
                                    <th>Coordinator</th>
                                    <th>State</th>
                                    <th>Type</th>
                                    <th>Visibility</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td>{{ $project->kics_id }}</td>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->project_manager ?? '-' }}</td>
                                        <td>{{ $project->project_coordinator ?? '-' }}</td>
                                        <td>
                                            @php
                                                $stateColors = [
                                                    'Active' => 'success',
                                                    'In Progress' => 'warning',
                                                    'Completed' => 'primary',
                                                    'On Hold' => 'danger',
                                                    'Pending' => 'secondary'
                                                ];
                                                $state = $project->project_states ?? 'Unknown';
                                                $color = $stateColors[$state] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }} rounded-pill">
                                                {{ $state }}
                                            </span>
                                        </td>
                                        <td>{{ $project->project_type ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button"
                                                    class="btn btn-{{ $project->is_visible ? 'success' : 'secondary' }} btn-sm toggle-visibility"
                                                    data-id="{{ $project->id }}"
                                                    data-type="project"
                                                    data-visible="{{ $project->is_visible ? 'true' : 'false' }}">
                                                    <i class="bi bi-eye{{ $project->is_visible ? '' : '-slash' }}"></i>
                                                </button>
                                                <span class="badge bg-{{ $project->is_visible ? 'success' : 'danger' }} rounded-pill">
                                                    {{ $project->is_visible ? 'Visible' : 'Hidden' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                            No projects found for this department.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>

            <!-- Department Stats Summary -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-primary">{{ $projects->total() }}</h3>
                            <p class="text-muted mb-0">Total Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-success">{{ $projects->where('project_states', 'Active')->count() }}</h3>
                            <p class="text-muted mb-0">Active Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="text-warning">{{ $erpDepartment->manager ? 'Yes' : 'No' }}</h3>
                            <p class="text-muted mb-0">Has Manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('.toggle-visibility').click(function () {
            const id = $(this).data('id');
            const type = $(this).data('type');
            const btn = $(this);

            $.ajax({
                url: '{{ route("admin.erp.toggle.visibility") }}',
                type: 'POST',
                data: {
                    id: id,
                    type: type,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        const icon = response.is_visible ? 'eye' : 'eye-slash';
                        const cls = response.is_visible ? 'btn-success' : 'btn-secondary';
                        btn.removeClass('btn-success btn-secondary').addClass(cls);
                        btn.html('<i class="bi bi-' + icon + '"></i>');
                        btn.closest('td').find('.badge').text(response.is_visible ? 'Visible' : 'Hidden');
                        btn.closest('td').find('.badge').removeClass('bg-success bg-danger').addClass(response.is_visible ? 'bg-success' : 'bg-danger');
                    }
                },
                error: function (xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error toggling visibility';
                    alert(message);
                }
            });
        });
    });
</script>
@endpush
@endsection