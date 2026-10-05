<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'KICS Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">
    
    <!-- CSS -->
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons (for modals and icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles')
</head>
<body>
    <div class="layout-wrapper">
        @yield('content')
    </div>

    <!-- ================================================== -->
    <!-- JavaScript Libraries -->
    <!-- ================================================== -->
    
    <!-- jQuery (required for all admin features) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AdminLTE / Theme Scripts -->
    <script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('layouts/assets/js/app.js') }}"></script>
    
    <!-- DataTables -->
    <script src="{{ asset('layouts/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('layouts/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>