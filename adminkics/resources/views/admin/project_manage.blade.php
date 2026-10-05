<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>Manage Project - {{ $project->projectlist_Name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <div class="py-3 py-lg-4 d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h4 class="page-title mb-1">Manage Project</h4>
                        <div class="fw-semibold">{{ $project->projectlist_Name }}</div>
                        <div class="text-muted small">
                            {{ $project->group->group_name ?? 'No group' }} | Project ID: {{ $project->projectlist_id }}
                        </div>
                    </div>
                    <a class="btn btn-outline-secondary" href="{{ route('project.list') }}">Back to Projects</a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-muted small">Category</div>
                                <div>{{ $project->project_category ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Code</div>
                                <div>{{ $project->code ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Status</div>
                                <div>{{ $project->is_completed ? 'Completed' : 'Ongoing' }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-muted small">Description</div>
                                <div>{{ Str::limit(strip_tags($project->projectlist_description), 220) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs" role="tablist">
                    @foreach($sections as $key => $section)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif"
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{ $key }}"
                                    type="button"
                                    role="tab">
                                {{ $section['title'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content pt-3">
                    @foreach($sections as $key => $section)
                        @php
                            $records = $sectionRecords[$key] ?? collect();
                            $primaryKey = $section['primary_key'];
                        @endphp

                        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $key }}" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                        <h5 class="mb-0">{{ $section['title'] }}</h5>
                                        <button class="btn btn-primary btn-sm"
                                                type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#add_{{ $key }}">
                                            Add {{ $section['title'] }}
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    @foreach($section['fields'] as $field)
                                                        <th>{{ $field['label'] ?? Str::headline($field['name']) }}</th>
                                                    @endforeach
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($records as $record)
                                                    <tr>
                                                        <td>{{ $record->{$primaryKey} }}</td>
                                                        @foreach($section['fields'] as $field)
                                                            @php
                                                                $fieldName = $field['name'];
                                                                $type = $field['type'] ?? 'text';
                                                                $rawValue = $record->{$fieldName} ?? '';
                                                                $displayValue = $rawValue;

                                                                if ($type === 'select') {
                                                                    $displayValue = ($field['options'][$rawValue] ?? $rawValue) ?: '-';
                                                                } elseif ($type === 'checkbox') {
                                                                    $displayValue = (int) $rawValue === 1 ? 'Yes' : 'No';
                                                                } elseif ($type === 'textarea') {
                                                                    $displayValue = Str::limit(strip_tags((string) $rawValue), 90);
                                                                }
                                                            @endphp
                                                            <td>
                                                                @if($type === 'file' && $rawValue)
                                                                    <a href="{{ asset('uploads/project-management/' . $rawValue) }}" target="_blank">{{ $rawValue }}</a>
                                                                @else
                                                                    {{ $displayValue ?: '-' }}
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                        <td class="text-end">
                                                            <button class="btn btn-sm btn-warning"
                                                                    type="button"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#edit_{{ $key }}_{{ $record->{$primaryKey} }}">
                                                                Edit
                                                            </button>
                                                            <form action="{{ route('project.manage.records.destroy', [$project, $key, $record->{$primaryKey}]) }}"
                                                                  method="POST"
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('Delete this record?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="{{ count($section['fields']) + 2 }}" class="text-center text-muted py-4">
                                                            No records yet.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="add_{{ $key }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form class="modal-content"
                                          action="{{ route('project.manage.records.store', [$project, $key]) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add {{ $section['title'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                @foreach($section['fields'] as $field)
                                                    @include('admin.partials.project_manage_field', [
                                                        'field' => $field,
                                                        'record' => null,
                                                        'idPrefix' => 'add_' . $key,
                                                    ])
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @foreach($records as $record)
                                <div class="modal fade" id="edit_{{ $key }}_{{ $record->{$primaryKey} }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <form class="modal-content"
                                              action="{{ route('project.manage.records.update', [$project, $key, $record->{$primaryKey}]) }}"
                                              method="POST"
                                              enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit {{ $section['title'] }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    @foreach($section['fields'] as $field)
                                                        @include('admin.partials.project_manage_field', [
                                                            'field' => $field,
                                                            'record' => $record,
                                                            'idPrefix' => 'edit_' . $key . '_' . $record->{$primaryKey},
                                                        ])
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>
</body>
</html>
