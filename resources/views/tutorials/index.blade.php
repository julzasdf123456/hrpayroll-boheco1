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
        <div class="col-lg-10 offset-lg-1 col-md-12 py-4">
            <div class="pb-4 pt-4">
                <span class="float-left">
                    <h2 class="text-primary"><i class="fas fa-info-circle ico-tab"></i>HRS Help Center</h2>
                </span>

                <a href="{{ route('home') }}" class="btn btn-default float-right" title="Go back home"><i
                        class="fas fa-home"></i></a>
            </div>

            <table class="table table-hover mt-5">
                <tbody>
                    <tr>
                        <td class="v-align">
                            <h3>Registering an HRS account</h3>
                            <p class="text-muted no-pads">Creating an account in order to use the HRS system.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Registering an Account.mp4', 'Registering an HRS account', 'Creating an account in order to use the HRS system.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Logging in and Basic Navigation</h3>
                            <p class="text-muted no-pads">Logging in with your account, and basic orientation of the
                                system.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Loggin in and Basic Navigation.mp4', 'Logging in and Basic Navigation', 'Logging in with your account, and basic orientation of the system.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Leave for Myself</h3>
                            <p class="text-muted no-pads">Create a leave application for oneself.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['File a Leave for Myself.mp4', 'File a Leave for Myself', 'Create a leave application for oneself.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Leave for My Co-worker</h3>
                            <p class="text-muted no-pads">Create a leave application for other employees.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['File a Leave for Co-Worker.mp4', 'File a Leave for My Co-worker', 'Create a leave application for other employees.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Trip Tickets and GRS</h3>
                            <p class="text-muted no-pads">Create a trip ticket request, with GRS attachments.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Filing of Trip Ticket.mp4', 'File a Trip Ticket', 'Create a trip ticket request, with GRS attachments.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Duty Offset Requests</h3>
                            <p class="text-muted no-pads">Create a duty offset request.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Filing of Offsets.mp4', 'File a Duty Offset Request', 'Create an offset request.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Attendance Confirmations</h3>
                            <p class="text-muted no-pads">File for an attendance confirmation.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Filing of Attendance Confirmation.mp4', 'File for an Attendance Confirmation Request', 'File for an attendance confirmation.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Travel Orders</h3>
                            <p class="text-muted no-pads">File for an official travel order.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Filing of Travel Order.mp4', 'Travel Orders', 'File for an official travel order.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Leave Conversions</h3>
                            <p class="text-muted no-pads">File for a leave conversion request.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Filing of Leave Conversion.mp4', 'Leave Conversions', 'File for a leave conversion request.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Managing your Leave History and Credits</h3>
                            <p class="text-muted no-pads">View and manage your leave credits, credit logs, and leave
                                histories.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Viewing of Leave Credits.mp4', 'Managing your Leave Credits and History', 'View and manage your leave credits, credit logs, and leave histories.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Attendance and Profile Information</h3>
                            <p class="text-muted no-pads">Manage and monitor your attendance logs, and profile
                                information.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Viewing of Attendance and Profile.mp4', 'Attendance and Profile Information', 'Manage and monitor your attendance logs, and profile information.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>

                    <tr>
                        <td class="v-align">
                            <h3>Payroll and Incentives Information</h3>
                            <p class="text-muted no-pads">Monitor your payroll profile and incentives information.</p>
                        </td>
                        <td class="v-align text-right">
                            <a href="{{ route('tutorials.videoViewer', ['Viewing of Payroll Profile.mp4', 'Payroll and Incentives Information', 'Monitor your payroll profile and incentives information.']) }}"
                                class="btn btn-default"><i class="fas fa-play ico-tab-mini"></i> Watch
                                Video</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
