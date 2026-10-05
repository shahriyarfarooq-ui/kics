@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="page-title">Edit Department</h4>
                <a href="{{ route('admin.erp.departments.show', $erpDepartment) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.erp.departments.update', $erpDepartment) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Name</label>
                                <input type="text" class="form-control @error('web_department_name') is-invalid @enderror" 
                                       name="web_department_name" 
                                       value="{{ old('web_department_name', $erpDepartment->web_department_name) }}">
                                @error('web_department_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department Code</label>
                                <input type="text" class="form-control @error('dept_code') is-invalid @enderror" 
                                       name="dept_code" 
                                       value="{{ old('dept_code', $erpDepartment->dept_code) }}">
                                @error('dept_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Manager</label>
                                <input type="text" class="form-control @error('manager') is-invalid @enderror" 
                                       name="manager" 
                                       value="{{ old('manager', $erpDepartment->manager) }}">
                                @error('manager')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                @if($erpDepartment->logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $erpDepartment->logo) }}" 
                                             width="100" class="img-thumbnail">
                                        <a href="#" class="btn btn-danger btn-sm delete-image" data-field="logo">Remove</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Cover Image</label>
                                <input type="file" class="form-control" name="cover_image" accept="image/*">
                                @if($erpDepartment->cover_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $erpDepartment->cover_image) }}" 
                                             width="200" class="img-thumbnail">
                                        <a href="#" class="btn btn-danger btn-sm delete-image" data-field="cover_image">Remove</a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('web_detail_description') is-invalid @enderror" 
                                          name="web_detail_description" 
                                          rows="4">{{ old('web_detail_description', $erpDepartment->web_detail_description) }}</textarea>
                                @error('web_detail_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Vision</label>
                                <textarea class="form-control @error('vision') is-invalid @enderror" 
                                          name="vision" 
                                          rows="3">{{ old('vision', $erpDepartment->vision) }}</textarea>
                                @error('vision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mission</label>
                                <textarea class="form-control @error('mission') is-invalid @enderror" 
                                          name="mission" 
                                          rows="3">{{ old('mission', $erpDepartment->mission) }}</textarea>
                                @error('mission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Admin Notes</label>
                                <textarea class="form-control" name="admin_notes" rows="2">{{ old('admin_notes', $erpDepartment->admin_notes) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="active" 
                                           id="active" value="1" 
                                           {{ old('active', $erpDepartment->active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_visible" 
                                           id="is_visible" value="1" 
                                           {{ old('is_visible', $erpDepartment->is_visible) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_visible">Visible on Website</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Update Department</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    $('.delete-image').click(function(e) {
        e.preventDefault();
        if (!confirm('Delete this image?')) return;

        const field = $(this).data('field');
        const id = {{ $erpDepartment->id }};

        $.ajax({
            url: "{{ url('admin/erp/departments') }}/" + id + "/delete-image",
            type: 'POST',
            data: {
                field: field,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error deleting image');
            }
        });
    });
});
</script>
@endsection
@endsection