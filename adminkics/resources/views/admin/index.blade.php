<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light" class="sidebar-disable">




<head>
    <meta charset="utf-8" />
    <title>KICS ADMIN DASHBOARD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('layouts/assets/images/favicon.ico') }}">

<link href="{{ asset('layouts/assets/libs/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css --><link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

<script src="{{ asset('layouts/assets/js/config.js') }}"></script>
</head>

<body>

    <!-- Begin page -->
    <div class="layout-wrapper">

        <!-- ========== Left Sidebar ========== -->
        <div class="main-menu">
            <!-- Brand Logo -->
           @include('components.logo')
            <!--- Menu -->
           @include('components.sidebar')
        </div>



        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <!-- ========== Topbar Start ========== -->
            @include('components.topbar')
            <!-- ========== Topbar End ========== -->

            <div class="px-3">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Dashboard</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">kICS</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Total Labs</h6>
                                            <span class="h3 mb-0">{{ $totalLabs }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-primary">Labs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Total Staff</h6>
                                            <span class="h3 mb-0">{{ $totalStaff }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-success">Staff</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Active Staff</h6>
                                            <span class="h3 mb-0">{{ $activeStaff }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-info">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Active Labs</h6>
                                            <span class="h3 mb-0">{{ $activeLabs }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-warning">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row-->

                    <div class="row mt-3">
                        <div class="col-lg-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Total Projects</h6>
                                            <span class="h3 mb-0">{{ $totalProjects }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-danger">Projects</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Total Users</h6>
                                            <span class="h3 mb-0">{{ $totalUsers }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="badge badge-soft-secondary">Users</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row-->

                   
                    <!-- end row-->

                    
                    <!-- end row-->

                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © KICS</div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                                <p class="mb-0">Design & Develop by <a href="" target="_blank">KICS</a> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- App js -->
  <script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
<script src="{{ asset('layouts/assets/js/app.js') }}"></script>


    <!-- Jquery Sparkline Chart  -->
<script src="{{ asset('layouts/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Jquery-knob Chart Js-->
<script src="{{ asset('layouts/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>


    <!-- Morris Chart Js-->
     <script src="{{ asset('layouts/assets/libs/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('layouts/assets/libs/raphael/raphael.min.js') }}"></script>


    <!-- Dashboard init-->
<script src="{{ asset('layouts/assets/js/pages/dashboard.js') }}"></script>

</body>


<!-- Mirrored from myrathemes.com/drezoc/layouts/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 25 Nov 2024 07:19:19 GMT -->

</html>