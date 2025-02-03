<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>

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

    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.min.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('css/bstreeview.css') }}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="container">
    <div class="row">
        <div class="col-lg-12 p-4">
            <h3>{{ $title }}</h3>
            <p class="text-muted no-pads">{{ $description }}</p>

            <video controls style="width: 100%;" class="mt-4" autoplay>
                <source src="{{ URL::asset('videos/' . $fileName) }}" type="video/mp4">
            </video>
        </div>
    </div>
</body>

</html>
