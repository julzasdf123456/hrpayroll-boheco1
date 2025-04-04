<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | Registration Page</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('css/source_sans_pro.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/all.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap4toggle.css') }} ">
          
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte.min.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrapdatetimepicker.min.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/jqueryui.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.bootstrap4.min.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/fullcalendar.min.css') }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/sweetalert2.min.css') }}">
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/home') }}"><b>{{ config('app.name') }}</b></a>
    </div>

    <div class="card shadow-soft">
        <div class="card-body">
            <p class="login-box-msg text-muted">Sign Up</p>

            <form method="post" action="{{ route('register') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="text"
                           name="employee_id"
                           id="employee_id"
                           class="form-control @error('employee_id') is-invalid @enderror"
                           value="{{ old('employee_id') }}"
                           placeholder="Type Your Employee ID">
                    @error('employee_id')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <button id="verify-id" class="btn btn-primary" style="width: 100%;"> <i class="fas fa-shield-alt" style="margin-right: 10px;"></i>Verify Employee ID</button>
                </div>

                <div style="width: 100%; height: 1px; background-color: #adadad; margin-bottom: 10px;"></div>

                <div class="input-group mb-3">
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Full name" readonly>
                    @error('name')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text"
                           name="username"
                           id="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           placeholder="Username" disabled>
                    @error('username')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email" disabled>
                    @error('email')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Password" disabled>
                    @error('password')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="form-control"
                           placeholder="Retype password" disabled>
                </div>

                <div class="row">
                    {{-- <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                            <label for="agreeTerms">
                                I agree to the <a href="#">terms</a>
                            </label>
                        </div>
                    </div> --}}
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-check-circle" style="margin-right: 10px;"></i>Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="text-center" style="margin-top: 16px;">
                <a href="{{ route('login') }}" class="text-muted">Go back to Login</a>
            </p>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->

    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<script src="{{ URL::asset('js/jquery.min.css') }}"></script>

<script src="{{ URL::asset('js/jqueryui.css') }}"></script>

<script src="{{ URL::asset('js/popper.min.js') }}"></script>

<script src="{{ URL::asset('js/bootstrap.bundle.min.js') }}"></script>
        
<script src="{{ URL::asset('js/bscustomfileinput.min.js') }}"></script>

<script src="{{ URL::asset('js/adminlte.min.js') }}"></script>

<script src="{{ URL::asset('js/moment.js') }}"></script>

<script src="{{ URL::asset('js/datetimepicker.min.js') }}"></script>

<script src="{{ URL::asset('js/bootstrap4toggle.min.js') }}"></script>

<script src="{{ URL::asset('js/bootstrapswitch.min.js') }}"></script>

<script src="{{ URL::asset('js/lordicon.js') }}"></script>

<script src="{{ URL::asset('js/chart.min.js') }}"></script>

<script src="{{ URL::asset('js/svgconnect.js') }}"></script>

<script src="{{ URL::asset('js/select2min.js') }}"></script>

<script src="{{ URL::asset('js/jqueryuicalendar.min.js') }}"></script>
<script src="{{ URL::asset('js/calendarfulljs.min.js') }}"></script>

<script src="{{ URL::asset('js/sweetalert2.all.min.js') }}"></script>

<script src="{{ URL::asset('js/inputmask.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })

        $('#verify-id').on('click', function(e) {
            e.preventDefault()
            var id = $('#employee_id').val()

            if (jQuery.isEmptyObject(id)) {
                Swal.fire({
                    icon : 'warning',
                    text : 'Please input a valid employee ID!'
                })
            } else {
                $.ajax({
                    url : "{{ route('register.get-employee-ajax') }}",
                    type : 'GET',
                    data : {
                        id : id,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Employee found!'
                        })
                        /**
                         * Populate Data
                         */
                        $(this).attr('disabled', true)
                        $('#employee_id').attr('readonly', true)
                        $('#name').val(res['FirstName'] + ' ' + res['LastName'])
                        $('#username').removeAttr('disabled')
                        $('#username').focus()
                        $('#email').removeAttr('disabled')
                        $('#password').removeAttr('disabled')
                        $('#password_confirmation').removeAttr('disabled')
                    },
                    error : function(xhr, status, error) {
                        Swal.fire({
                            icon : 'warning',
                            title : 'Error Getting Employee Info',
                            text : xhr.responseText
                        })
                    }
                })
            }
        })
    })
</script>

</body>
</html>
