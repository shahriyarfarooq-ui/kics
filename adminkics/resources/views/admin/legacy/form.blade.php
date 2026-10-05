<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>{{ $record ? 'Edit' : 'Add' }} {{ Str::headline($table) }} - KICS Admin</title>
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
                        <h4 class="page-title mb-0">{{ $record ? 'Edit' : 'Add' }} {{ $module['title'] ?? Str::headline($table) }}</h4>
                        <div class="text-muted small">{{ $table }}</div>
                    </div>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.legacy.table', $module['key'] ?? $table) }}">Back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ $record ? route('admin.legacy.update', [$module['key'] ?? $table, $record->{$primaryKey}]) : route('admin.legacy.store', $module['key'] ?? $table) }}">
                            @csrf
                            @if($record)
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                @foreach($columns as $column)
                                    @php
                                        $name = $column->Field;
                                        $type = strtolower($column->Type);
                                        $value = old($name, $record->{$name} ?? $column->Default);
                                        $isLong = str_contains($type, 'text') || str_contains($type, 'longtext') || strlen((string) $value) > 120;
                                        $isNumber = preg_match('/int|decimal|float|double|bit/', $type);
                                        $isDate = preg_match('/date|time|year/', $type);
                                        $isFile = preg_match('/image|img|file|document|poster|logo|avatar|thumb|banner|photo|path/', $name);
                                    @endphp

                                    <div class="{{ $isLong ? 'col-12' : 'col-md-6' }}">
                                        <label class="form-label">
                                            {{ $name }}
                                            @if($column->Null === 'NO' && $column->Default === null)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>

                                        @if(isset($relations[$name]))
                                            <select class="form-select" name="{{ $name }}">
                                                <option value="">Select {{ Str::headline($name) }}</option>
                                                @foreach($relationMaps[$name] ?? [] as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                                                        {{ $optionLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif($isFile)
                                            <input class="form-control" type="file" name="{{ $name }}">
                                            @if($value)
                                                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                                <div class="form-text">Current: {{ $value }}</div>
                                            @endif
                                        @elseif($isLong)
                                            <textarea class="form-control" name="{{ $name }}" rows="5">{{ $value }}</textarea>
                                        @elseif($isDate)
                                            <input class="form-control" type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $type }}">
                                        @elseif($isNumber)
                                            <input class="form-control" type="number" step="any" name="{{ $name }}" value="{{ $value }}">
                                        @else
                                            <input class="form-control" type="text" name="{{ $name }}" value="{{ $value }}">
                                        @endif

                                        <div class="form-text">{{ $column->Type }}{{ $column->Default !== null ? ' default: ' . $column->Default : '' }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a class="btn btn-outline-secondary" href="{{ route('admin.legacy.table', $module['key'] ?? $table) }}">Cancel</a>
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </form>
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
