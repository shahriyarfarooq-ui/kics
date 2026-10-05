<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>Admin Modules - KICS Admin</title>
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
                        <h4 class="page-title mb-0">Admin Modules</h4>
                        <div class="text-muted small">Manage old KICS database modules with their table relationships.</div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    @foreach($tables as $table)
                        <div class="col-md-6 col-xl-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between gap-3">
                                        <div>
                                            <h5 class="mb-1">{{ $table['label'] }}</h5>
                                            <div class="text-muted small">{{ $table['name'] }}</div>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary align-self-start">
                                            {{ number_format($table['count']) }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="text-muted small">
                                            PK: {{ $table['primaryKey'] ?: 'none' }}
                                        </span>
                                        <a class="btn btn-sm btn-primary" href="{{ route('admin.legacy.table', $table['key']) }}">
                                            Open
                                        </a>
                                    </div>
                                </div>
                            </div>
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
