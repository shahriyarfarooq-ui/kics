<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login | KICS</title>
    <link href="{{ asset('layouts/assets/css/style.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class="auth-body-bg">
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Welcome Back!</h5>
                                        <p>Sign in to continue to KICS Admin.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('layouts/assets/images/profile-img.png') }}" alt="" class="img-fluid" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="{{ route('login') }}" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('layouts/assets/images/logo.svg') }}" alt="" class="avatar-xs logo-light" />
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login.submit') }}" class="form-horizontal">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <label class="form-label" for="password">Password</label>
                                            </div>
                                        </div>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100" type="submit">Log In</button>
                                    </div>
                                </form>
                                <div class="mt-3 text-center text-muted small">
                                    <!--<p class="mb-1">Total registered users: <strong>{{ $totalUsers }}</strong></p>-->
                                    <!--<p class="mb-0">Demo login: <strong>admin@example.com</strong> / <strong>password</strong></p>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p class="text-muted mb-0">© <script>document.write(new Date().getFullYear())</script> KICS Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('layouts/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('layouts/assets/js/app.js') }}"></script>
</body>
</html>
