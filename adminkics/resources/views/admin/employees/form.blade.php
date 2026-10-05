@extends('layouts.app')

@section('title', $editing ? 'Edit Employee' : 'Add Employee')

@section('content')
<div class="main-menu">
    @include('components.logo')
    @include('components.sidebar')
</div>

<div class="page-content">
    @include('components.topbar')
    <div class="px-3"><div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h4 class="page-title mb-0">{{ $editing ? 'Edit Employee' : 'Add Employee' }}</h4>
            <a href="{{ route('admin.erp.employees.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back to Employees</a>
        </div>

        <div class="card"><div class="card-body">
            <form method="POST" action="{{ $editing ? route('admin.erp.employees.update', $employee) : route('admin.erp.employees.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif
                <div class="row">
                    @php
                        $fields = [
                            ['emp_id', 'ERP Employee ID *', 'number', true], ['name', 'Name *', 'text', true],
                            ['complete_name', 'Complete Name', 'text'], ['prefix', 'Prefix', 'text'],
                            ['father_name', 'Father Name', 'text'], ['job_title', 'Job Title', 'text'],
                            ['department', 'Department', 'text'], ['department_id', 'Department ID', 'number'],
                            ['campus', 'Campus', 'text'], ['campus_id', 'Campus ID', 'number'],
                            ['manager', 'Manager', 'text'], ['coach', 'Coach', 'text'],
                            ['work_phone', 'Work Phone', 'text'], ['work_email', 'Work Email', 'email'],
                            ['mobile_phone', 'Mobile Phone', 'text'], ['state', 'State', 'text'],
                            ['joining_date', 'Joining Date', 'date'], ['experience', 'Experience (years)', 'number'],
                            ['type', 'Employee Type', 'text'],
                        ];
                    @endphp
                    @foreach($fields as $field)
                        @php [$key, $label, $type] = $field; @endphp
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="{{ $key }}">{{ $label }}</label>
                            <input class="form-control @error($key) is-invalid @enderror" id="{{ $key }}" name="{{ $key }}" type="{{ $type }}" value="{{ old($key, $employee->$key) }}" @if($type === 'number' && $key === 'experience') step="0.01" min="0" @endif @if(in_array($key, ['emp_id', 'name'])) required @endif>
                            @error($key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                    <div class="col-12"><hr><h5 class="mb-3">Status and Website Visibility</h5></div>
                    @foreach(['active' => 'Active', 'is_active' => 'ERP Active', 'is_visible' => 'Visible on Website'] as $key => $label)
                        <div class="col-md-4 mb-3"><div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="{{ $key }}" name="{{ $key }}" value="1" @checked(old($key, $employee->$key ?? true))>
                            <label class="form-check-label" for="{{ $key }}">{{ $label }}</label>
                        </div></div>
                    @endforeach
                    <div class="col-12 mt-3"><button class="btn btn-primary" type="submit">{{ $editing ? 'Save Employee' : 'Create Employee' }}</button></div>
                </div>
            </form>
        </div></div>
    </div></div>
</div>
@endsection
