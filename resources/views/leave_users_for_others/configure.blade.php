@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-9 col-md-10">
                    <h4>Configure Employees Allowed to be Filed With Leave</h4>
                </div>
                <div class="col-lg-3 col-md-2">
                    <button onclick="save()" class="btn btn-success float-right">Save <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            {{-- ISD --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title" style="display: flex; align-items: center; gap: 10px; justify-self: center;">
                        <input type="checkbox" class="form-control form-control-sm" id="isd" style="width: 16px;">
                        <label for="isd" style="padding-top: 8px">ISD</label>
                    </span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <tbody>
                            @foreach ($isd as $item)
                                <tr>
                                    <td style="width: 60px;" class="v-align">
                                        <input type="checkbox" {{ in_array($item->id, $allowedEmployees) ? 'checked' : '' }} class="form-control form-control-sm isd" id="{{ $item->id }}" name="Allowed" value="{{ $item->id }}" style="width: 16px;">
                                    </td>
                                    <td class="v-align">
                                        <label for="{{ $item->id }}">{{ $item->LastName . ', ' . $item->FirstName }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>

            {{-- OGM --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title" style="display: flex; align-items: center; gap: 10px; justify-self: center;">
                        <input type="checkbox" class="form-control form-control-sm" id="ogm" style="width: 16px;">
                        <label for="ogm" style="padding-top: 8px">OGM</label>
                    </span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <tbody>
                            @foreach ($ogm as $item)
                                <tr>
                                    <td style="width: 60px;" class="v-align">
                                        <input type="checkbox" {{ in_array($item->id, $allowedEmployees) ? 'checked' : '' }} class="form-control form-control-sm ogm" id="{{ $item->id }}" name="Allowed" value="{{ $item->id }}" style="width: 16px;">
                                    </td>
                                    <td class="v-align">
                                        <label for="{{ $item->id }}">{{ $item->LastName . ', ' . $item->FirstName }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
        
        {{-- ESD --}}
        <div class="col-lg-3 col-md-6">
            {{-- ESD --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title" style="display: flex; align-items: center; gap: 10px; justify-self: center;">
                        <input type="checkbox" class="form-control form-control-sm" id="esd" style="width: 16px;">
                        <label for="esd" style="padding-top: 8px">ESD</label>
                    </span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <tbody>
                            @foreach ($esd as $item)
                                <tr>
                                    <td style="width: 60px;">
                                        <input type="checkbox" {{ in_array($item->id, $allowedEmployees) ? 'checked' : '' }} class="form-control form-control-sm v-align esd" id="{{ $item->id }}" name="Allowed" value="{{ $item->id }}" style="width: 16px;">
                                    </td>
                                    <td class="v-align">
                                        <label for="{{ $item->id }}">{{ $item->LastName . ', ' . $item->FirstName }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

        {{-- OSD --}}
        <div class="col-lg-3 col-md-6">
            {{-- OSD --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title" style="display: flex; align-items: center; gap: 10px; justify-self: center;">
                        <input type="checkbox" class="form-control form-control-sm" id="osd" style="width: 16px;">
                        <label for="osd" style="padding-top: 8px">OSD</label>
                    </span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <tbody>
                            @foreach ($osd as $item)
                                <tr>
                                    <td style="width: 60px;">
                                        <input type="checkbox" {{ in_array($item->id, $allowedEmployees) ? 'checked' : '' }} class="form-control form-control-sm v-align osd" id="{{ $item->id }}" name="Allowed" value="{{ $item->id }}" style="width: 16px;">
                                    </td>
                                    <td class="v-align">
                                        <label for="{{ $item->id }}">{{ $item->LastName . ', ' . $item->FirstName }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

        {{-- PGD --}}
        <div class="col-lg-3 col-md-6">
            {{-- PGD --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title" style="display: flex; align-items: center; gap: 10px; justify-self: center;">
                        <input type="checkbox" class="form-control form-control-sm" id="pgd" style="width: 16px;">
                        <label for="pgd" style="padding-top: 8px">PGD</label>
                    </span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <tbody>
                            @foreach ($pgd as $item)
                                <tr>
                                    <td style="width: 60px;">
                                        <input type="checkbox" {{ in_array($item->id, $allowedEmployees) ? 'checked' : '' }} class="form-control form-control-sm v-align pgd" id="{{ $item->id }}" name="Allowed" value="{{ $item->id }}" style="width: 16px;">
                                    </td>
                                    <td class="v-align">
                                        <label for="{{ $item->id }}">{{ $item->LastName . ', ' . $item->FirstName }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>

            {{-- SEEAD --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title" style="display: flex; align-items: center; gap: 10px; justify-self: center;">
                        <input type="checkbox" class="form-control form-control-sm" id="seead" style="width: 16px;">
                        <label for="seead" style="padding-top: 8px">SEEAD</label>
                    </span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <tbody>
                            @foreach ($seead as $item)
                                <tr>
                                    <td style="width: 60px;">
                                        <input type="checkbox" {{ in_array($item->id, $allowedEmployees) ? 'checked' : '' }} class="form-control form-control-sm v-align seead" id="{{ $item->id }}" name="Allowed" value="{{ $item->id }}" style="width: 16px;">
                                    </td>
                                    <td class="v-align">
                                        <label for="{{ $item->id }}">{{ $item->LastName . ', ' . $item->FirstName }}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            // $('#page-title').html("<span class='text-muted'>Process and Generate</span> <strong>Bi-Monthly Payroll</strong>")

            $('#isd').change(function() {
                if ($(this).is(':checked')) {
                    $('.isd').prop('checked', true)
                } else {
                    $('.isd').prop('checked', false)
                }
            })

            $('#esd').change(function() {
                if ($(this).is(':checked')) {
                    $('.esd').prop('checked', true)
                } else {
                    $('.esd').prop('checked', false)
                }
            })

            $('#ogm').change(function() {
                if ($(this).is(':checked')) {
                    $('.ogm').prop('checked', true)
                } else {
                    $('.ogm').prop('checked', false)
                }
            })

            $('#osd').change(function() {
                if ($(this).is(':checked')) {
                    $('.osd').prop('checked', true)
                } else {
                    $('.osd').prop('checked', false)
                }
            })

            $('#pgd').change(function() {
                if ($(this).is(':checked')) {
                    $('.pgd').prop('checked', true)
                } else {
                    $('.pgd').prop('checked', false)
                }
            })

            $('#seead').change(function() {
                if ($(this).is(':checked')) {
                    $('.seead').prop('checked', true)
                } else {
                    $('.seead').prop('checked', false)
                }
            })
        })

        function save() {
            var selected = $('input[name="Allowed"]:checked')

            var selectedData = []
            selected.each(function() {
                selectedData.push($(this).val())
            })

            Swal.fire({
                title: "Confirmation",
                text : `By continuing, you are allowing this user/employee to file leave applications for the selected users.`,
                showCancelButton: true,
                confirmButtonText: "Continue Save",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('leaveUsersForOthers.post') }}",
                        type : "POST",
                        data :  {
                            _token : "{{ csrf_token() }}",
                            EmployeeIds : selectedData,
                            CreatorId : "{{ $user->employee_id }}"
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Data saved!'
                            })
                            window.location.href = "{{ url('/employees/') }}/{{ $user->employee_id }}"
                        },
                        error : function(err) {
                            console.log(err)
                            Toast.fire({
                                icon : 'error',
                                text : 'Error saving data!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush
