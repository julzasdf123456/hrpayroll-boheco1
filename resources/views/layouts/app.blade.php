<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="color-profile" content="{{ Auth::user()->ColorProfile }}">

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

    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback"> --}}

    <!-- Font Awesome -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
          rel="stylesheet"> --}}
          
    <!-- AdminLTE -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css"
          integrity="sha512-rVZC4rf0Piwtw/LsgwXxKXzWq3L0P6atiQKBNuXYRbg2FoRbSTIY0k2DxuJcs7dk4e/ShtMzglHKBOJxW8EQyQ=="
          crossorigin="anonymous"/> --}}

    <!-- iCheck -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css"
          integrity="sha512-8vq2g5nHE062j3xor4XxPeZiPjmRDh6wlufQlfC6pdQ/9urJkU07NM0tEREeymP++NczacJ/Q59ul+/K2eYvcg=="
          crossorigin="anonymous"/> --}}

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
          integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
          crossorigin="anonymous"/> --}}

    {{-- <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" 
          integrity="sha512-aEe/ZxePawj0+G2R+AaIxgrQuKT68I28qh+wgLrcAJOz3rxCP+TwrK5SPN+E5I+1IQjNtcfvb96HDagwrKRdBw=="
          crossorigin="anonymous"/>--}}

    @yield('third_party_stylesheets')

    @stack('page_css')
    <style>
        .no-pads {
            margin: 0px !important;
            padding: 0px !important;
        }

        .border-left-light {
            border-left: 5px solid #5f5f5f;
            border-radius: 4px;
            background-color: #f5f5f5;
        }

        .border-left-dark {
            border-left: 5px solid #7e7e7e;
            border-radius: 4px;
            background-color: #3c4447;
        }

        .bl-light {
            border-left: 1px solid #e9e9e9;
        }

        .bl-dark {
            border-left: 1px solid #494c50;
        }

        .divider {
            width: 100%;
            height: 1px;
            background-color: #dedede;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .divider-dark {
            width: 100%;
            height: 1px;
            background-color: #575a61;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .ico-tab {
            margin-right: 20px;
        }

        .ico-tab-mini {
            margin-right: 10px;
        }

        .ico-tab-left {
            margin-left: 20px;
        }

        .ico-tab-left-mini {
            margin-left: 10px;
        }

        .ellipsize {
            width: 290px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ellipsize-dynamic {
            width: 350px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .radio-group-horizontal {
            border: 1px solid #dcdcdc;
            display: flex;
            padding: 6px;
            border-radius: 3px;
        }

        .radio-group-horizontal-sm {
            border: 1px solid #dcdcdc;
            display: flex;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 6px;
            padding-right: 6px;
            border-radius: 3px;
        }

        .radio-group-horizontal input,
        .radio-group-horizontal-sm input {
            margin-left: 3px;
            margin-right: 3px;
        }

        .radio-group-horizontal label,
        .radio-group-horizontal-sm label {
            margin-left: 20px;
            margin-right: 20px;
        }

        .payroll-color-absent {
            background-color: #dc3545 !important;
        }

        .payroll-color-sunday {
            background-color: #d9ddff !important;
        }

        .payroll-color-saturday {
            background-color: #f7f5cd !important;
        }

        .payroll-color-leave {
            background-color: #00ad51 !important;
            color: #ffffff !important;
        }

        /**
         * DATE RANGE PICKER
         */
        .available {
            color : #333;
        }

        .available:hover {
            background-color : #7ca9c7 !important;
            color : #333;
        }

        td.off {
            background-color: #e4e4e4 !important;
            border-radius: 0px !important;
        }

        .calendar-table table thead tr th {
            color : #333;
        }

        
        .ui-datepicker-next span.ui-icon {
            background-color: red !important;
        }

        .gone {
            display: none;
        }
    </style>
</head>
@php
    $userCache = Auth::user();
@endphp
<body class="hold-transition sidebar-mini layout-fixed {{ $userCache->ColorProfile }}">
<div class="wrapper">
    <!-- Main Header -->
    <nav class="main-header navbar navbar-expand {{ $userCache->ColorProfile != null ? 'navbar-dark' : 'navbar-light' }}">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

            {{-- FILE SHORTCUT --}}
            @if (!in_array(Route::currentRouteName(), ['tripTickets.log-vehicle-trips', 'tripTickets.log-vehicle-arrivals']))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="file-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">File </a>
                    <div class="dropdown-menu" aria-labelledby="file-menu">
                        <a href="{{ route('leaveApplications.create') }}" class="dropdown-item" title="File for leave">Leave</a>
                        <a href="{{ route('tripTickets.create') }}" class="dropdown-item" title="Make a trip ticket">Trip Ticket</a>
                        <a href="{{ route('offsetApplications.create') }}" class="dropdown-item" title="Claim an offset">Offset</a>
                        <a href="{{ route('overtimes.create') }}" class="dropdown-item" title="File for an overtime">Overtime Authorization</a>
                        <a href="{{ route('attendanceConfirmations.create') }}" class="dropdown-item" title="File for an attendance confirmation">Attendance Confirmation</a>
                    </div>
                </li>
            @endif            
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link text-warning" href="{{ route('home.reeve') }}" title="Ask Reeve for Help">
                    <i class="fas fa-poo"></i>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span id="notifier-count" class="badge badge-primary navbar-badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header"><span id="notif-count-expand">0</span> Notification(s)</span>
                    <div id="notif-handler">

                    </div>
                </div>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ URL::asset('imgs/logo.png'); }}"
                         class="user-image img-circle elevation-2" alt="User Image"> 
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header {{ $userCache->ColorProfile != null ? 'bg-light' : 'bg-dark' }}">
                        {{-- <img src="https://boheco1.com/wp-content/uploads/2018/06/boheco-1-1024x1012.png" class="user-image img-circle elevation-2" alt="User Image"> --}}
                        <img src="{{ URL::asset('imgs/logo.png'); }}"
                             class="img-circle elevation-2"
                             alt="User Image"> 
                        <br>
                        <h4 style="margin-top: 10px;"> {{ Auth::check() ? Auth::user()->name : '' }} </h4>
                    </li>
                    <table class="table table-borderless table-hover table-sm">
                        <tr>
                            <td>
                                <a href="" class="btn btn-link {{ $userCache->ColorProfile != null ? 'text-light' : 'text-dark' }}"><i class="fas fa-user-circle ico-tab"></i>My Account</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{-- <div class="custom-control custom-switch">
                                    <label class="custom-control-label" for="color-modes" id="color-modes">Dark Mode</label>
                                    <input type="checkbox" class="custom-control-input" id="color-modes">
                                </div> --}}
                                <div class="custom-control custom-switch" style="margin-left: 10px; margin-top: 6px; margin-bottom: 6px;">
                                    <input type="checkbox" {{ $userCache->ColorProfile != null ? 'checked' : '' }} class="custom-control-input" id="color-switch">
                                    <label style="font-weight: normal;" class="custom-control-label" for="color-switch" id="color-switchLabel">Dark Mode</label>
                                </div>
                                
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #e6e6e6;">
                                <a href="#" class="btn btn-link {{ $userCache->ColorProfile != null ? 'text-light' : 'text-dark' }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt ico-tab"></i>Sign out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    </table>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Left side column. contains the logo and sidebar -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            @yield('content')
        </section>
    </div>

    <!-- Main Footer -->
    {{-- <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.0.5
        </div>
        <strong>Copyright &copy; 2014-2020 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
        reserved.
    </footer> --}}
</div>
{{-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" 
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" 
        crossorigin="anonymous"></script>
        
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/js/adminlte.min.js"
        integrity="sha512-++c7zGcm18AhH83pOIETVReg0dr1Yn8XTRw+0bWSIWAVCAwz1s2PwnSj4z/OOyKlwSXc4RLg3nnjR22q0dhEyA=="
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"
        integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg=="
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
        integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg=="
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous"></script>
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" integrity="sha512-J+763o/bd3r9iW+gFEqTaeyi+uAphmzkE/zU8FxY6iAvD3nQKXa+ZAWkBI9QS9QkYEKddQoiy0I5GDxKf/ORBA==" crossorigin="anonymous"></script> --}}


 {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/jquery.min.css'); }}"></script>

{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
<script src="{{ URL::asset('js/jqueryui.css'); }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" 
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/popper.min.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ URL::asset('js/bootstrap.bundle.min.js'); }}"></script>
        
{{-- <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script> --}}
<script src="{{ URL::asset('js/bscustomfileinput.min.js'); }}"></script>


<!-- AdminLTE App -->
{{-- <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script> --}}
<script src="{{ URL::asset('js/adminlte.min.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"
        integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/moment.js'); }}"></script>
<script src="{{ URL::asset('js/moment-timezone.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
        integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/datetimepicker.min.js'); }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script> --}}
<script src="{{ URL::asset('js/bootstrap4toggle.min.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" integrity="sha512-J+763o/bd3r9iW+gFEqTaeyi+uAphmzkE/zU8FxY6iAvD3nQKXa+ZAWkBI9QS9QkYEKddQoiy0I5GDxKf/ORBA==" crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/bootstrapswitch.min.js'); }}"></script>

{{-- <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script> --}}
<script src="{{ URL::asset('js/lordicon.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script> --}}
<script src="{{ URL::asset('js/chart.min.js'); }}"></script>

{{-- <script src="https://www.jqueryscript.net/demo/jQuery-Plugin-To-Connect-HTML-Elements-with-Lines-HTML-SVG-Connect/jquery.html-svg-connect.js"></script> --}}
<script src="{{ URL::asset('js/svgconnect.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/select2/js/select2.full.min.js"></script> --}}
<script src="{{ URL::asset('js/select2min.js'); }}"></script>

{{-- CALENDAR --}}
{{-- <script src="https://adminlte.io/themes/v3/plugins/jquery-ui/jquery-ui.min.js"></script> --}}
{{-- <script src="https://adminlte.io/themes/v3/plugins/fullcalendar/main.js"></script> --}}
<script src="{{ URL::asset('js/jqueryuicalendar.min.js'); }}"></script>
<script src="{{ URL::asset('js/calendarfulljs.min.js'); }}"></script>

{{-- SWEETALERT2 --}}
<script src="{{ URL::asset('js/sweetalert2.all.min.js'); }}"></script>

{{-- INPUT MASK --}}
<script src="{{ URL::asset('js/inputmask.min.js'); }}"></script>
<script src="{{ URL::asset('js/daterangepicker.min.js'); }}"></script>
<script>
    $('.select2').select2({
            theme: 'bootstrap4'
        })

    $(function () {
        bsCustomFileInput.init();
    });
    
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    /**
     * FOR TREEVIEW CHILD
     */
     var url = window.location;
    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
        return this.href == url;
    }).addClass('active');
    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');

    $(document).ready(function() {
        /**
         * TOWN CHANGE
         */
        fetchBarangayFromTown($('#TownCurrent').val(), $('#Def_Brgy').text());
        fetchBarangayFromTownPermanent($('#TownPermanent').val(), $('#Def_Brgy_Permanent').text());
        fetchNotifications();
        countNotifications();

        $('#TownCurrent').on('change', function() {
            fetchBarangayFromTown(this.value, $('#Def_Brgy').text());
        });

        $('#TownPermanent').on('change', function() {
            fetchBarangayFromTownPermanent(this.value, $('#Def_Brgy_Permanent').text());
        });

        /**
         * COLOR MODES CONTROLLER 
         **/
         $('#color-switch').on('change', function(e) {
            var col = ''
            if (e.target.checked) {
               col = 'dark-mode'
            } else {
               col = null
            }

            $.ajax({
                url : "{{ route('users.switch-color-modes') }}",
                type : "GET",
                data : {
                    id : "{{ Auth::id() }}",
                    Color : col,
                },
                success : function(res) {
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error changing color profile'
                    })
                }
            })
         })
    });

    /**
     * FUNCTIONS
     */
     function fetchBarangayFromTown(townId, prevValue) {
        $.ajax({
            url : "{{ url('/barangays/get-barangays-json') }}/" + townId,
            type: "GET",
            dataType : "json",
            success : function(data) {
                $('#BarangayCurrent option').remove();
                $.each(data, function(index, element) {
                    $('#BarangayCurrent').append("<option value='" + element + "' " + (element==prevValue ? "selected='selected'" : " ") + ">" + index + "</option>");
                });
            },
            error : function(error) {
                // alert(error);
                // console.log(error);
            }
        });
    }

    function fetchBarangayFromTownPermanent(townId, prevValue) {
        $.ajax({
            url : "{{ url('/barangays/get-barangays-json') }}/" + townId,
            type: "GET",
            dataType : "json",
            success : function(data) {
                $('#BarangayPermanent option').remove();
                $.each(data, function(index, element) {
                    $('#BarangayPermanent').append("<option value='" + element + "' " + (element==prevValue ? "selected='selected'" : " ") + ">" + index + "</option>");
                });
            },
            error : function(error) {
                // alert(error);
                // console.log(error);
            }
        });
    }

    function fetchNotifications() {
        $.ajax({
            url : "{{ url('/notifications/get-all-notifications') }}",
            type : 'GET',
            success : function(response) {
                if (jQuery.isEmptyObject(response)) {
                    $('#notif-handler').append('<p>No notifications for the moment</p>');
                } else {
                    $('#notif-handler').append(response);
                }
            },
            error : function(err) {
                // alert('Error fetching notifications')
            }
        })
    }

    function countNotifications() {
        $.ajax({
            url : "{{ url('/notifications/get-notif-counter') }}",
            type : 'GET',
            success : function(response) {
                if (jQuery.isEmptyObject(response)) {
                    $('#notifier-count').text("0");
                    $('#notif-count-expand').text('0');
                    $('#notifier-count').removeClass('badge-danger').addClass('badge-primary');
                } else {
                    if (response['res'] == '0') {
                        $('#notifier-count').removeClass('badge-danger').addClass('badge-primary');
                    } else {
                        $('#notifier-count').removeClass('badge-primary').addClass('badge-danger');
                    }
                    $('#notifier-count').text(response['res']);
                    $('#notif-count-expand').text(response['res']);
                }
            },
            error : function(err) {
                // alert('Error fetching notifications')
            }
        })
    }

    /**
     * TOAST 
     **/
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    function serializeEmployeeName(fName, lName, mName, suffix) {
        if (jQuery.isEmptyObject(mName)) {
            return fName + " " + lName + " " + validateNulls(suffix)
        } else {
            return fName + " " + validateNulls(mName) + " " + lName + " " + validateNulls(suffix)
        }
    }

    function serializeEmployeeNameFormal(fName, lName, mName, suffix) {
        if (jQuery.isEmptyObject(mName)) {
            return lName + ", " + fName + " " + validateNulls(suffix)
        } else {
            return lName + ", " + fName + " " + validateNulls(mName) + " " + validateNulls(suffix)
        }
    }

    function serializeEmployeeNameFormalNoMiddle(fName, lName, suffix) {
        return lName + ", " + fName + " " + validateNulls(suffix)
    }

    function validateNulls(regex) {
        if (jQuery.isEmptyObject(regex)) {
            return ''
        } else {
            return regex
        }
    }

    function isNull(regex) {
        if (jQuery.isEmptyObject(regex)) {
            return true
        } else {
            return false
        }
    }

    function toMoney(value) {
        return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
    }

    function round(value) {
        return Math.round((value + Number.EPSILON) * 100) / 100
    }
    
</script>

@yield('third_party_scripts')

@stack('page_scripts')
</body>
</html>
