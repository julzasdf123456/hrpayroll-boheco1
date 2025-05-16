@php
    use App\Models\Employees;
    use App\Models\LeaveBalances;
    use App\Models\Permission;

    // $specialLeaveReasons = ["Enrollment","Graduation","Birthday","Medical Examination","Wedding Anniversary","Fiesta"];

    function specialLeaveReasonExists($specialLeaves, $reason): bool
    {
        return $specialLeaves->contains(function ($leave) use ($reason) {
            return $leave->Content === $reason;
        });
    }
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4><strong>File for Co-Worker: </strong> Leave Application</h4>
                </div>
            </div>
        </div>
    </section>



    @include('flash::message')

    <div class="row">
        {{-- LEAVE FORM --}}
        <div class="col-lg-7">
            {{-- LEAVE FORM --}}
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="form-group mb-4">
                        <span class="text-muted">Select Employee</span>
                        <select class="custom-select select2" name="EmployeeId" id="EmployeeId" style="width: 100%;"
                            required>
                            <option value="">-- Select --</option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}">{{ Employees::getMergeNameFormal($item) }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="card-body">
                        <span class="text-muted">Select Leave Type</span>
                        <div class="pl-5 mb-4 d-flex ">

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="LeaveType" id="Vacation"
                                        value="Vacation">
                                    <label class="form-check-label" for="Vacation">Vacation</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="LeaveType" id="Sick"
                                        value="Sick">
                                    <label class="form-check-label" for="Sick">Sick</label>
                                </div>
                                <div class="form-check">
                                    {{-- @if (count($specialLeaves) >= 3)
                                        <input class="form-check-input" type="radio" name="LeaveType" id="Special"
                                            value="Special" disabled>
                                        <label class="form-check-label"
                                            title="You only had 3 special leaves. Cannot exceed more this year."
                                            for="Special">Special</label>
                                    @else --}}
                                        <input class="form-check-input" type="radio" name="LeaveType" id="Special"
                                            value="Special">
                                        <label class="form-check-label" for="Special">Special</label>
                                    {{-- @endif --}}
                                </div>
                                {{-- @if ($employee->Father === 'Yes') --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="LeaveType" id="Paternity"
                                        value="Paternity">
                                    <label class="form-check-label" for="Paternity">Paternity</label>
                                </div>
                                {{-- @endif --}}
                                {{-- @if ($employee->Mother === 'Yes')  --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="LeaveType" id="Maternity"
                                        value="Maternity">
                                    <label class="form-check-label" for="Maternity">Maternity</label>
                                </div>
                                {{-- @endif
                            @if ($employee->SoloMother === 'Yes')  --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="LeaveType"
                                        id="MaternityForSoloMother" value="MaternityForSoloMother">
                                    <label class="form-check-label" for="MaternityForSoloMother">Maternity For Solo
                                        Mother</label>
                                </div>
                                {{-- @endif
                            @if ($employee->SoloParent === 'Yes')  --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="LeaveType" id="SoloParent"
                                        value="SoloParent">
                                    <label class="form-check-label" for="SoloParent">Solo Parent</label>
                                </div>
                                {{-- @endif --}}
                            </div>


                            <div class="pl-3 ml-5 mb-5">
                                <div class="form-check" id="salary-deduction-item">
                                    <input class="form-check-input" type="checkbox" name="SalaryDeduction"
                                        onchange="updateSalaryCheck()" id="SalaryDeduction" />
                                    <label class="form-check-label" for="SalaryDeduction">
                                        This employee shall publish this leave with his/her salary deduction instead of the
                                        leave balance credits for this matter.
                                    </label>
                                </div>
                                <div class="my-5" id="insuff-message" style="color:#ffbe33;">
                                    <strong>WARNING: </strong>
                                    <p>Insufficent leave credits. The rest of the later leave dates will be marked as unpaid
                                        if you wish to proceed publishing his/her leave.</p>
                                </div>
                                <div class="my-5" id="irreg-message" style="color:#ffbe33;">
                                    <strong>WARNING: </strong>
                                    <p>This won't deduct leave balance credits until this employee will become a PERMANENT type. Instead, he/she will have a SALARY DEDUCTION after declaring a leave.</p>
                                </div>
                            </div>
                        </div>

                        <!-- FOR SPECIAL LEAVE Field -->
                        <div class="form-group mb-3" id="special-dropdown">
                            <span class="text-muted">Select Reason</span>
                            <select name="SpecialReason" id="SpecialReason" class="form-control">
                                {{-- <option value="">-- Select --</option> --}}
                                <option id="SpecialValue-Enrollment" value="Enrollment">Enrollment</option>
                                <option id="SpecialValue-Graduation" value="Graduation">Graduation</option>
                                <option id="SpecialValue-Birthday" value="Birthday">Birthday</option>
                                <option id="SpecialValue-MedicalExamination" value="Medical Examination">Medical
                                    Examination</option>
                                <option id="SpecialValue-WeddingAnniversary" value="Wedding Anniversary">Wedding
                                    Anniversary</option>
                                <option id="SpecialValue-Fiesta" value="Fiesta">Fiesta</option>
                            </select>
                        </div>

                        <div class="form-group mb-3" id="reason-field">
                            <span class="text-muted">Reason</span>
                            <input type="text" name="Reason" id="Reason" class="form-control" />
                        </div>

                        <div class="form-group mb-3">
                            <span class="text-muted">Date Filed</span>
                            <input type="text" name="DateFiled" id="DateFiled" class="form-control"
                                value="{{ date('Y-m-d') }}"
                                {{ Permission::hasDirectPermission(['god permission', 'view employee']) ? '' : 'disabled' }} />
                            @push('page_scripts')
                                <script type="text/javascript">
                                    $('#DateFiled').datetimepicker({
                                        format: 'YYYY-MM-DD',
                                        useCurrent: true,
                                        sideBySide: true
                                    })
                                </script>
                            @endpush
                        </div>

                        <div class="form-group">
                            <span class="text-muted">Select Days</span>
                            <input type="text" name="Day" id="Day" class="form-control" />
                        </div>
                        <table id="dates-table" class="table table-hover table-borderless table-sm">
                            <tbody>

                            </tbody>
                        </table>

                        <div id="upload-section">
                            <div class="divider"></div>
                            <input type="file" accept="image/png, image/gif, image/jpeg" id="img-attachment"
                                style="display: none" />
                            <button class="btn btn-sm btn-primary float-right" onclick="thisFileUpload()"><i
                                    class="fas fa-upload ico-tab-mini"></i>Upload File(s)</button>
                            <p class="text-muted">Attachments (Physician Consults, Medical Certs, etc.)</p>

                            <div class="row" id="imgs-data">
                                @foreach ($leaveImgs as $item)
                                    <div class="col-md-3" id="{{ $item->id }}">
                                        <button class="btn btn-xs btn-danger" style=""
                                            onclick="removeImg('{{ $item->id }}')"><i
                                                class="fas fa-trash"></i></button>
                                        <img src="{{ $item->HexImage }}" style="width: 100%;" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @push('page_scripts')
                            <script>
                                var holidays = "{{ $holidays }}"
                                holidays = holidays.split(',')

                                $('#Day').daterangepicker({
                                    showDropdowns: true,
                                    alwaysShowCalendars: true,
                                    isInvalidDate: function(date) {
                                        // if (date.day() == 0 | holidays.includes(date.format('YYYY-MM-DD'))) {
                                        //     return true
                                        // } else {
                                        return false
                                        // }
                                    },
                                    minYear: 1901,
                                    maxYear: parseInt(moment().format('YYYY'), 10)
                                }, function(start, end, label) {
                                    addDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
                                });
                            </script>
                        @endpush
                    </div>



                    @push('page_scripts')
                        <script>
                            var holidays = "{{ $holidays }}"
                            holidays = holidays.split(',')

                            $('#Day').daterangepicker({
                                showDropdowns: true,
                                alwaysShowCalendars: true,
                                isInvalidDate: function(date) {
                                    if (date.day() == 0 | holidays.includes(date.format('YYYY-MM-DD'))) {
                                        return true
                                    } else {
                                        return false
                                    }
                                },
                                minYear: 1901,
                                maxYear: parseInt(moment().format('YYYY'), 10)
                            }, function(start, end, label) {
                                addDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
                            });
                        </script>
                    @endpush
                </div>
                <div class="card-footer">
                    <button id="saveLeave" onclick="saveLeave()" class="btn btn-primary float-right"><i
                            class="fas fa-check-circle ico-tab-mini"></i>Publish Leave</button>

                    {{-- <p class="text-muted gone" id="not-allowed-note">Non-regular employees are not allowed to file leave yet.</p> --}}
                </div>
            </div>
        </div>

        {{-- LEAVE BALANCES HERE --}}
        <div class="col-lg-5">
            {{-- LEAVA BALANCES --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Leave Balances</span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm table-hover">
                        <tbody>
                            <tr>
                                <td>VACATION</td>
                                <td class="text-right" id="balance-vacation">...</td>
                            </tr>
                            <tr>
                                <td>SICK</td>
                                <td class="text-right" id="balance-sick">...</td>
                            </tr>
                            <tr>
                                <td>SPECIAL</td>
                                <td class="text-right" id="balance-special">...</td>
                            </tr>
                            <tr>
                                <td>MATERNITY</td>
                                <td class="text-right" id="balance-maternity">...</td>
                            </tr>
                            <tr>
                                <td>MATERNITY (SOLO PARENT)</td>
                                <td class="text-right" id="balance-maternity-solo-parent">...</td>
                            </tr>
                            <tr>
                                <td>PATERNITY</td>
                                <td class="text-right" id="balance-paternity">...</td>
                            </tr>
                            <tr>
                                <td>SOLO PARENT</td>
                                <td class="text-right" id="balance-solo-parent">...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SIGNATORIES --}}
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Signatories</span>
                </div>
                <div class="card-body table-responsive">
                    {{-- <div class="card bg-danger gone" id="alert-no-sig">
                        <div class="card-body">
                            <h4><i class="fas fa-exclamation-triangle ico-tab"></i>Oops!</h4>
                            <p>No signatory found for this employee! Contact IT for troubleshooting.</p>
                        </div>
                    </div> --}}

                    <div class="card gone" id="alert-no-sig">
                        <div class="card-body">
                            {{-- <h4><i class="fas fa-exclamation-triangle ico-tab"></i>Oops!</h4> --}}
                            <p>No signatories are available for this employee.</p>
                        </div>
                    </div>
                    <table id="signatories-table" class="table table-hover">
                        <tbody>

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
        var salaryDeducted = false
        var leaveDates = []
        var leaveBalances = []
        var signatories = []
        var otherSigs = []
        var leaveBalanceCounter = 0
        var leaveDateDurationCounter = 0
        var regularPosition = false;

        function getSpecialLeaves(empId) {
            $("#SpecialReason").prop('selectedIndex', 0);
            $.ajax({
                url: "show-special-leaves/" + empId,
                type: 'GET',
                success: function({
                    status,
                    count,
                    reasons
                }) {
                    if (status !== 'Regular') {
                        regularPosition = false;
                        $('#irreg-message').show();
                        return;
                    } else {
                        regularPosition = true;
                        $('#irreg-message').hide();
                    }

                    if (count >= 3) {
                        $('#Special').attr('disabled', true);
                        $('#SpecialLabel').attr("title",
                            "This employee only had 3 special leaves. Cannot exceed more this year.");
                    } else {
                        $('#Special').attr('disabled', false);
                        $('#SpecialLabel').attr("title", null);
                    }
                    console.log(count, reasons)

                    if (reasons.Enrollment) {
                        $('#SpecialValue-Enrollment').attr('disabled', true);
                        $('#SpecialValue-Enrollment').attr('value', '');
                        $('#SpecialValue-Enrollment').attr("title",
                            "This employee already had this special reason this year.");
                    }
                    if (reasons.Graduation) {
                        $('#SpecialValue-Graduation').attr('disabled', true);
                        $('#SpecialValue-Graduation').attr('value', '');
                        $('#SpecialValue-Graduation').attr("title",
                            "This employee already had this special reason this year.");
                    }
                    if (reasons.Birthday) {
                        $('#SpecialValue-Birthday').attr('disabled', true);
                        $('#SpecialValue-Birthday').attr('value', '');
                        $('#SpecialValue-Birthday').attr("title",
                            "This employee already had this special reason this year.");
                    }
                    if (reasons.MedicalExamination) {
                        $('#SpecialValue-MedicalExamination').attr('disabled', true);
                        $('#SpecialValue-MedicalExamination').attr('value', '');
                        $('#SpecialValue-MedicalExamination').attr("title",
                            "This employee already had this special reason this year.");
                    }
                    if (reasons.WeddingAnniversary) {
                        $('#SpecialValue-WeddingAnniversary').attr('disabled', true);
                        $('#SpecialValue-WeddingAnniversary').attr('value', '');
                        $('#SpecialValue-WeddingAnniversary').attr("title",
                            "This employee already had this special reason this year.");
                    }
                    if (reasons.Fiesta) {
                        $('#SpecialValue-Fiesta').attr('disabled', true);
                        $('#SpecialValue-Fiesta').attr('value', '');
                        $('#SpecialValue-Fiesta').attr("title",
                            "This employee already had this special reason this year.");
                    }

                }
            })
        }


        /**
         * LEAVE BALANCES 
         */
        function getLeaveBalances(employeeId) {
            leaveBalances = []
            $.ajax({
                url: "{{ route('leaveApplications.get-leave-balances-by-employee') }}",
                type: "GET",
                data: {
                    EmployeeId: employeeId
                },
                success: function(res) {
                    leaveBalances = res
                    updateLeaveBalancesTable(res.EmployeeData)
                    getSignatories(employeeId, res.EmployeeData)
                },
                error: function(err) {
                    Toast.fire({
                        icon: 'error',
                        text: 'Error getting leave balances'
                    })
                }
            })
        }


        function thisFileUpload() {
            document.getElementById("img-attachment").click();
        };



        function removeImg(id) {
            Swal.fire({
                title: 'Remove Confirmation',
                text: 'You sure you wanna remove this image attachment?',
                showDenyButton: true,
                confirmButtonText: 'Remove',
                denyButtonText: `Close`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leaveApplications.remove-image') }}",
                        type: 'GET',
                        data: {
                            id: id,
                        },
                        success: function(res) {
                            $('#' + id).remove()
                        },
                        error: function(err) {
                            Swal.fire({
                                icon: 'error',
                                text: 'Error removing image attachment'
                            })
                        }
                    })
                } else if (result.isDenied) {

                }
            })
        }



        $('#img-attachment').on('change', function() {
            readURL(this)
        })



        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $.ajax({
                        url: "{{ route('leaveApplications.add-image-attachments') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            LeaveId: "{{ $id }}",
                            HexImage: encodeURIComponent(e.target.result),
                        },
                        success: function(res) {
                            $('#imgs-data').append(
                                "<div class='col-md-3' id='" + res['id'] + "'>" +
                                "<button class='btn btn-xs btn-danger' style='position: absolute; right: 10px; top: 5px;' onclick=removeImg('" +
                                res['id'] + "')><i class='fas fa-trash'></i></button>" +
                                "<img src='" + res['HexImage'] + "' style='width: 100%;'/>" +
                                "</div>"
                            )
                        },
                        error: function(err) {
                            Swal.fire({
                                icon: 'error',
                                text: 'Error uploading image attachment'
                            })
                        }
                    })

                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function getCheckSpecialDays() {
            var leave = $('input[name="LeaveType"]:checked').val()
            if (leaveDateDurationCounter != 1 && leave === "Special") {
                $('#saveLeave').attr('disabled', true);
                $('#saveLeave').attr("title", "This special leave should have only one day off.");
            } else {
                $('#saveLeave').attr('disabled', false);
                $('#saveLeave').attr("title", null);
            }

        }


        function updateLeaveBalancesTable(employeeData) {
            // if (employeeData.Mother === 'Yes') {
            //     $('#balance-maternity').html(isNull(leaveBalances.Maternity) ? '...' : leaveBalances.Maternity +
            //         " <span class='text-muted'>days</span>")
            //     $('#op-maternity').removeClass('gone')
            // } else {
            //     $('#balance-maternity').html("")
            //     $('#op-maternity').addClass('gone')
            // }

            // if (employeeData.SoloMother === 'Yes') {
            //     $('#balance-maternity-solo-parent').html(isNull(leaveBalances.MaternityForSoloMother) ? '...' :
            //         leaveBalances.MaternityForSoloMother + " <span class='text-muted'>days</span>")
            //     $('#op-maternity-solo').removeClass('gone')
            // } else {
            //     $('#balance-maternity-solo-parent').html("")
            //     $('#op-maternity-solo').addClass('gone')
            // }

            // if (employeeData.SoloParent === 'Yes') {
            //     $('#balance-solo-parent').html(isNull(leaveBalances.SoloParent) ? '...' : leaveBalances.SoloParent +
            //         " <span class='text-muted'>days</span>")
            //     $('#op-solo').removeClass('gone')
            // } else {
            //     $('#balance-solo-parent').html("")
            //     $('#op-solo').addClass('gone')
            // }

            // if (employeeData.Father === 'Yes') {
            //     $('#balance-paternity').html(isNull(leaveBalances.Paternity) ? '...' : leaveBalances.Paternity +
            //         " <span class='text-muted'>days</span>")
            //     $('#op-paternity').removeClass('gone')
            // } else {
            //     $('#balance-paternity').html("")
            //     $('#op-paternity').addClass('gone')
            // }

            $('#balance-vacation').html(isNull(leaveBalances.Vacation) ? '...' : leaveBalances.VacationExpanded)
            $('#balance-sick').html(isNull(leaveBalances.Sick) ? '...' : leaveBalances.SickExpanded)
            $('#balance-special').html(isNull(leaveBalances.Special) ? '...' : leaveBalances.Special +
                " <span class='text-muted'>days</span>")
            $('#balance-maternity').html(isNull(leaveBalances.Maternity) ? '...' : leaveBalances.Maternity +
                " <span class='text-muted'>days</span>")
            $('#balance-maternity-solo-parent').html(isNull(leaveBalances.MaternityForSoloMother) ? '...' : leaveBalances
                .MaternityForSoloMother + " <span class='text-muted'>days</span>")
            $('#balance-paternity').html(isNull(leaveBalances.Paternity) ? '...' : leaveBalances.Paternity +
                " <span class='text-muted'>days</span>")
            $('#balance-solo-parent').html(isNull(leaveBalances.SoloParent) ? '...' : leaveBalances.SoloParent +
                " <span class='text-muted'>days</span>")

            // validate select
            $('input[name="LeaveType"]').prop('checked', false);

        }

        function getSignatories(employeeId, employeeData) {
            signatories = []
            otherSigs = []
            $('#signatories-table tbody tr').remove()

            $.ajax({
                url: "{{ route('leaveApplications.get-signatories-for-employee') }}",
                type: "GET",
                data: {
                    EmployeeId: employeeId,
                },
                success: function(res) {
                    if (isNull(res.Signatories) | res.length < 1 | res.Signatories.length < 1) {
                        $('#alert-no-sig').removeClass('gone')
                        $('#saveLeave').addClass('gone')
                    } else {
                        $('#alert-no-sig').addClass('gone')
                        $('#saveLeave').removeClass('gone')

                        // don't allow leave filing if not regular
                        // if (employeeData.PositionStatus === 'Regular') {
                        //     $('#saveLeave').removeClass('gone')
                        //     $('#not-allowed-note').addClass('gone')
                        // } else {
                        //     $('#saveLeave').addClass('gone')
                        //     $('#not-allowed-note').removeClass('gone')
                        // }

                        signatories = res.Signatories
                        otherSigs = res.OtherSignatories
                        updateSignatoriesTable()
                    }
                },
                error: function(err) {
                    Toast.fire({
                        icon: 'error',
                        text: 'Error getting signatories for this employee'
                    })
                }
            })
        }


        $('#special-dropdown').hide()
        $('#upload-section').hide()
        $('#insuff-message').hide()
        $('#salary-deduction-item').hide()
        $('#irreg-message').hide()


        $('input[type=radio][name=LeaveType]').change(function() {
            var value = this.value


            if (value == 'Special') {
                $('#special-dropdown').show()
                $('#reason-field').hide()
                $('#Reason').attr('readonly', true)
                $('#Reason').val($('#SpecialReason').val())
                $('#upload-section').hide()
                $('#salary-deduction-item').hide()
            } else if (value == 'Sick') {
                $('#special-dropdown').hide()
                $('#reason-field').show()
                $('#Reason').removeAttr('readonly')
                $('#Reason').val(null)
                $('#upload-section').show()
                $('#salary-deduction-item').show()
            } else if (value == 'Vacation') {
                $('#special-dropdown').hide()
                $('#reason-field').show()
                $('#Reason').removeAttr('readonly')
                $('#Reason').val(null)
                $('#upload-section').hide()
                $('#salary-deduction-item').show()
            } else if (value == 'Paternity' | value == 'Maternity') {
                $('#special-dropdown').hide()
                $('#reason-field').show()
                $('#Reason').removeAttr('readonly')
                $('#Reason').val(null)
                $('#upload-section').hide()
            } else {
                $('#salary-deduction-item').hide()
                $('#special-dropdown').hide()
                $('#reason-field').show()
                $('#Reason').val(null)
                $('#Reason').removeAttr('readonly')
                $('#upload-section').hide()
            }

            console.log(regularPosition);

            
            if (!regularPosition) {
                $('#insuff-message').hide()
                $('#salary-deduction-item').hide()
                $('#irreg-message').show();
            } else {
                $('#irreg-message').hide();
            }

            // filter leave balance
            if (!isNull(leaveBalances)) {
                if (value === 'Vacation') {
                    leaveBalanceCounter = isNull(leaveBalances.Vacation) ? 0 : parseFloat(leaveBalances.Vacation)

                    if (leaveBalanceCounter) {
                        leaveBalanceCounter = Math.round(((leaveBalanceCounter / 8 / 60) + Number.EPSILON) * 100) /
                            100
                    } else {
                        leaveBalanceCounter = -1
                    }
                } else if (value === 'Sick') {
                    leaveBalanceCounter = isNull(leaveBalances.Sick) ? 0 : parseFloat(leaveBalances.Sick)

                    if (leaveBalanceCounter) {
                        leaveBalanceCounter = Math.round(((leaveBalanceCounter / 8 / 60) + Number.EPSILON) * 100) /
                            100
                    } else {
                        leaveBalanceCounter = -1
                    }
                } else if (value === 'Special') {
                    leaveBalanceCounter = isNull(leaveBalances.Special) ? 0 : parseFloat(leaveBalances.Special)
                } else if (value === 'Paternity') {
                    leaveBalanceCounter = isNull(leaveBalances.Paternity) ? 0 : parseFloat(leaveBalances.Paternity)
                } else if (value === 'Maternity') {
                    leaveBalanceCounter = isNull(leaveBalances.Maternity) ? 0 : parseFloat(leaveBalances.Maternity)
                } else if (value === 'MaternityForSoloMother') {
                    leaveBalanceCounter = isNull(leaveBalances.MaternityForSoloMother) ? 0 : parseFloat(
                        leaveBalances.MaternityForSoloMother)
                } else {
                    leaveBalanceCounter = isNull(leaveBalances.SoloParent) ? 0 : parseFloat(leaveBalances
                        .SoloParent)
                }
            } else {
                leaveBalanceCounter = 0
            }

            checkIfSufficentBalance();
            // console.log(leaveBalanceCounter)
        })


        function updateSignatoriesTable() {
            $.each(signatories, function(i, el) {
                $('#signatories-table tbody').append(`
                    <tr>
                        <td>
                            <span class='text-muted text-sm'>Approver #${ i+1 }</span>
                            <br>
                            <select class="custom-select select2" disabled>
                                ` + setSignatoryOptions(signatories[i].id) + `
                            </select>
                        </td>
                    </tr>
                `)
            })
        }

        function setSignatoryOptions(parentSignatorySelect) {
            var opts = ``
            for (let x = 0; x < otherSigs.length; x++) {
                var selected = otherSigs[x].id === parentSignatorySelect ? 'selected' : ''
                opts +=
                    `<option value='${ otherSigs[x].id }' ${ selected }>${ otherSigs[x].LastName + ', ' + otherSigs[x].FirstName }</option>`
            }

            return opts
        }

        function getSelectedSignatories() {
            var signatoryUserIds = []
            const table = document.getElementById('signatories-table');

            const tds = table.getElementsByTagName('td');

            for (let i = 0; i < tds.length; i++) {
                const select = tds[i].querySelector('select');

                if (select) {
                    const selectedOption = select.options[select.selectedIndex].value;

                    signatoryUserIds.push({
                        Rank: i + 1,
                        UserId: selectedOption
                    })
                }
            }

            return signatoryUserIds
        }

        /**
         * LEAVE DATES 
         */
        function removeDate(id) {
            $(`#${id}`).remove()
            leaveDates = leaveDates.filter(obj => obj.LeaveDate !== id)
            populateLeaveTable()
            checkIfSufficentBalance();
        }

        function updateSalaryCheck() {
            salaryDeducted = $("#SalaryDeduction").prop("checked");
            checkIfSufficentBalance()
        }

        function addDates(start, end) {
            enumerateDaysBetweenDates(start, end)
            //filter duplicate dates
            leaveDates = leaveDates.filter((obj, index, self) =>
                index === self.findIndex((t) => t.LeaveDate === obj.LeaveDate)
            )

            checkIfSufficentBalance();
            populateLeaveTable(leaveDates)
        }

        function enumerateDaysBetweenDates(startDate, endDate) {
            var now = moment(startDate).clone(),
                dates = [];

            while (now.isSameOrBefore(endDate)) {
                leaveDates.push({
                    LeaveDate: now.format('YYYY-MM-DD'),
                    Duration: 'WHOLE'
                });
                now.add(1, 'days');
            }
        }


        function counteringDateDuration() {
            leaveDateDurationCounter = 0;
            for (let i = 0; i < leaveDates.length; i++) {
                if (leaveDates[i].Duration === "WHOLE") {
                    leaveDateDurationCounter += 1;
                } else {
                    leaveDateDurationCounter += 0.5;
                }
            }
        }


        function checkIfSufficentBalance() {
            counteringDateDuration();
            // console.log("LeaveBalanceCounter: " + leaveBalanceCounter)
            // console.log("LeaveDatesDuration: " + leaveDateDurationCounter)
            // console.log("check if: " + leaveBalanceCounter < leaveDateDurationCounter && !salaryDeducted)
            if (leaveBalanceCounter < leaveDateDurationCounter && !salaryDeducted && regularPosition) {
                $('#insuff-message').show()
            } else {
                $('#insuff-message').hide()
            }
            getCheckSpecialDays();
        }


        function populateLeaveTable() {
            $('#dates-table tbody tr').remove()
            for (let i = 0; i < leaveDates.length; i++) {
                $('#dates-table tbody').append(`
                    <tr id='${ leaveDates[i].LeaveDate }'>
                        <td>${ moment(leaveDates[i].LeaveDate).format("MMMM DD, YYYY") }</td>
                        <td>
                            <select id='longevity-${ leaveDates[i].LeaveDate }' onchange='updateDuration("${ leaveDates[i].LeaveDate }")' class='form-control form-control-sm'>
                                <option value='WHOLE' selected>Whole Day</option>
                                <option value='AM'>Morning Only</option>
                                <option value='PM'>Afternoon Only</option>
                            </select>
                        </td>
                        <td class='text-right'>
                            <button onclick='removeDate("${ leaveDates[i].LeaveDate }")' class='btn btn-link btn-sm text-danger'><i class='fas fa-trash'></i></button>
                        </td>
                    </tr>
                `)
            }
        }

        function updateDuration(date) {
            var val = $('#longevity-' + date).val()
            leaveDates = leaveDates.map(obj =>
                obj.LeaveDate === date ? {
                    ...obj,
                    Duration: val
                } : obj
            )
            checkIfSufficentBalance();
        }

        /**
         * SAVE 
         */
        function saveLeave() {
            var employee = $('#EmployeeId').val()
            var leaveImgs = $('#img-attachment')[0].files;
            var leaveType = $('input[name="LeaveType"]:checked').val()
            var dateFiled = $('#DateFiled').val()
            var reason = $('#Reason').val()
            var salaryDeduction = $('#SalaryDeduction').prop('checked')

            if (isNull(employee) | isNull(leaveType) | isNull(dateFiled) | isNull(reason) | (leaveType == "Sick" &&
                    leaveImgs.length < 1) | leaveDates.length < 1) {
                Toast.fire({
                    icon: 'warning',
                    text: 'Please supply all fields'
                })
            } else {
                $.ajax({
                    url: "{{ route('leaveApplications.save-for-coworker') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        Id: "{{ $id }}",
                        EmployeeId: employee,
                        LeaveType: leaveType,
                        Reason: reason,
                        DateFiled: dateFiled,
                        Days: leaveDates,
                        Signatories: getSelectedSignatories(),
                        SalaryDeduction: salaryDeduction
                    },
                    success: function(res) {
                        Toast.fire({
                            icon: 'success',
                            text: 'Leave entry for ' + $('#EmployeeId option:selected').text() +
                                ' added'
                        })

                        // clear
                        leaveDates = []
                        signatories = []
                        otherSigs = []
                        $('#signatories-table tbody tr').remove()

                        // populateLeaveTable()
                        $('#dates-table tbody tr').remove()
                        $('#EmployeeId').val('')
                        $('input[name="LeaveType"]').prop('checked', false)
                        $('#Reason').val('')

                        // clear leaves
                        $('#balance-vacation').html('...')
                        $('#balance-sick').html('...')
                        $('#balance-special').html('...')
                        $('#balance-maternity').html('...')
                        $('#balance-maternity-solo-parent').html('...')
                        $('#balance-paternity').html('...')
                        $('#balance-solo-parent').html('...')
                        window.location.reload()
                    },
                    error: function(err) {
                        console.log(err)
                        Toast.fire({
                            icon: 'error',
                            text: 'Leave failed!'
                        })
                    }
                })
            }
        }

        $(document).ready(function() {
            $('#EmployeeId').on('change', function() {
                leaveDates = []
                populateLeaveTable()
                getLeaveBalances(this.value)
                getSpecialLeaves(this.value)
            })
        })
    </script>
@endpush
