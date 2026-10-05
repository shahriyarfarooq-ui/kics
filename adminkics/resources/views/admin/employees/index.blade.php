@extends('layouts.app')

@section('title', 'ERP Employees')

@section('content')
<div class="main-menu">
    @include('components.logo')
    @include('components.sidebar')
</div>

<div class="page-content">
    @include('components.topbar')
    <div class="px-3">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <h4 class="page-title mb-0">ERP Employees</h4>
                <a href="{{ route('admin.erp.employees.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Employee</a>
            </div>

            @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            <div class="card mb-4"><div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-7"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search name, employee ID, position, department, or email"></div>
                    <div class="col-md-3">
                        <select class="form-select" name="visibility">
                            <option value="">All visibility</option>
                            <option value="visible" @selected(request('visibility') === 'visible')>Visible</option>
                            <option value="hidden" @selected(request('visibility') === 'hidden')>Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-2"><button class="btn btn-outline-primary w-100" type="submit">Filter</button></div>
                </form>
            </div></div>

            <div class="card"><div class="card-body"><div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead><tr><th>Employee ID</th><th>Name</th><th>Position</th><th>Department</th><th>Email</th><th>Visibility</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>{{ $employee->emp_id }}</td>
                                <td>{{ $employee->complete_name ?: $employee->name }}</td>
                                <td>{{ $employee->job_title ?: '-' }}</td>
                                <td>{{ $employee->department ?: '-' }}</td>
                                <td>{{ $employee->work_email ?: '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.erp.employees.visibility', $employee) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-{{ $employee->is_visible ? 'success' : 'secondary' }}" type="submit">
                                            <i class="bi bi-eye{{ $employee->is_visible ? '' : '-slash' }} me-1"></i>{{ $employee->is_visible ? 'Visible' : 'Hidden' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.erp.employees.edit', $employee) }}" class="btn btn-primary btn-sm" aria-label="Edit employee"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.erp.employees.destroy', $employee) }}" class="d-inline" onsubmit="return confirm('Delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" aria-label="Delete employee"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No employees found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>{{ $employees->links() }}</div></div>
        </div>
    </div>
</div>
@endsection
