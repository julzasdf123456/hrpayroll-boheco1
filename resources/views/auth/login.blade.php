<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ URL::asset('css/source_sans_pro.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/all.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap4toggle.css'); }} ">
          
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrapdatetimepicker.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/jqueryui.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.bootstrap4.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/fullcalendar.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/sweetalert2.min.css'); }}">

    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.min.css'); }}">
    
    <link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">

    <link rel="stylesheet" href="{{ URL::asset('css/bstreeview.css'); }}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/home') }}" style="width: 100%; text-align: center; font-size: 1.4em;"><strong>{{ config('app.name') }}</strong></a>
    </div>

    <!-- /.login-logo -->

    <!-- /.login-box-body -->
    <div class="card shadow-none shadow-soft">
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form method="post" action="{{ url('/login') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="text"
                           name="username"
                           value="{{ old('username') }}"
                           placeholder="Username"
                           class="form-control @error('username') is-invalid @enderror" autofocus>
                    @error('username')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           placeholder="Password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror

                </div>

                <div class="row">
                    {{-- <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember Me</label>
                        </div>
                    </div> --}}

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fas fa-unlock"></i> Sign In</button>
                    </div>

                </div>
            </form>
            <p class="text-center text-muted" style="margin-top: 12px;">or</p>
            <p class="text-center">
                <a href="{{ route('register') }}">Create a New Account</a>
            </p>
            <p class="mb-1 text-center" style="margin-top: 18px;">
                <a class="text-muted" href="{{ route('password.request') }}" style="font-size: .85em;">I forgot my password</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>

</div>


<p class="text-center text-muted" style="font-size: .9em; position: fixed; bottom: 10px;">{{ env('APP_FULLNAME') }} <br> All Rights Reserved @ {{ date('Y') }} </p>
<!-- /.login-box -->

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/js/adminlte.min.js"
        integrity="sha512-++c7zGcm18AhH83pOIETVReg0dr1Yn8XTRw+0bWSIWAVCAwz1s2PwnSj4z/OOyKlwSXc4RLg3nnjR22q0dhEyA=="
        crossorigin="anonymous"></script>

</body>
</html>
