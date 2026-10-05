<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>{{ Str::headline($table) }} - KICS Admin</title>
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
                    <div>
                        <h4 class="page-title mb-0">{{ $module['title'] ?? Str::headline(str_replace(['kic_', 'tbl_'], '', $table)) }}</h4>
                        <div class="text-muted small">{{ $table }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-secondary" href="{{ route('admin.legacy.index') }}">All Tables</a>
                        <a class="btn btn-primary" href="{{ route('admin.legacy.create', $module['key'] ?? $table) }}">Add Record</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(!$primaryKey)
                    <div class="alert alert-warning">
                        This table has no primary key. Records can be viewed and created, but edit/delete is disabled.
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form class="row g-2 mb-3" method="GET">
                            <div class="col-md-8 col-lg-5">
                                <input class="form-control" name="q" value="{{ $query }}" placeholder="Search text columns">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-outline-primary" type="submit">Search</button>
                            </div>
                            @if($query)
                                <div class="col-auto">
                                    <a class="btn btn-outline-secondary" href="{{ route('admin.legacy.table', $module['key'] ?? $table) }}">Clear</a>
                                </div>
                            @endif
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        @foreach($displayColumns as $column)
                                            <th>{{ $column }}</th>
                                        @endforeach
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $record)
                                        <tr>
                                            @foreach($displayColumns as $column)
                                                @php($value = $record->{$column} ?? '')
                                                <td style="max-width: 260px;">
                                                    <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                                        {{ isset($relationMaps[$column][$value]) ? $relationMaps[$column][$value] : (is_scalar($value) ? strip_tags((string) $value) : '') }}
                                                    </span>
                                                </td>
                                            @endforeach
                                            <td class="text-end">
                                                @if($primaryKey)
                                                    <a class="btn btn-sm btn-warning" href="{{ route('admin.legacy.edit', [$module['key'] ?? $table, $record->{$primaryKey}]) }}">
                                                        Edit
                                                    </a>
                                                    <form class="d-inline" method="POST" action="{{ route('admin.legacy.destroy', [$module['key'] ?? $table, $record->{$primaryKey}]) }}" onsubmit="return confirm('Delete this record?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">No primary key</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($displayColumns) + 1 }}" class="text-center text-muted py-4">
                                                No records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>
</body>
</html>
