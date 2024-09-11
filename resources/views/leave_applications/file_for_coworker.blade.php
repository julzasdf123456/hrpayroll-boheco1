@php
    use App\Models\Employees;
    use App\Models\LeaveBalances;
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
    
    <div class="row">        
        {{-- LEAVE FORM --}}
        <div class="col-lg-7">          
            {{-- LEAVE FORM --}} 
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="form-group mb-4">
                        <span class="text-muted">Select Employee</span>
                        <select class="custom-select select2"  name="EmployeeId" id="EmployeeId" style="width: 100%;" required>
                            <option value="">-- Select --</option>
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}">{{ Employees::getMergeNameFormal($item) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <span class="text-muted">Select Leave Type</span>
                    <div class="form-group pl-5 mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="LeaveType" id="Vacation" value="Vacation">
                            <label class="form-check-label" for="Vacation">Vacation</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="LeaveType" id="Sick" value="Sick">
                            <label class="form-check-label" for="Sick">Sick</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="LeaveType" id="Special" value="Special">
                            <label class="form-check-label" for="Special">Special</label>
                        </div>
                        <div class="form-check gone" id="op-paternity">
                            <input class="form-check-input" type="radio" name="LeaveType" id="Paternity" value="Paternity">
                            <label class="form-check-label" for="Paternity">Paternity</label>
                        </div>

                        <div class="form-check gone" id="op-maternity">
                            <input class="form-check-input" type="radio" name="LeaveType" id="Maternity" value="Maternity">
                            <label class="form-check-label" for="Maternity">Maternity</label>
                        </div>
                        <div class="form-check gone" id="op-maternity-solo">
                            <input class="form-check-input" type="radio" name="LeaveType" id="MaternityForSoloMother" value="MaternityForSoloMother">
                            <label class="form-check-label" for="MaternityForSoloMother">Maternity For Solo Mother</label>
                        </div>
                        <div class="form-check gone" id="op-solo">
                            <input class="form-check-input" type="radio" name="LeaveType" id="SoloParent" value="SoloParent">
                            <label class="form-check-label" for="SoloParent">Solo Parent</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <span class="text-muted">Reason</span>
                        <input type="text" name="Reason" id="Reason" class="form-control"/>
                    </div>

                    <div class="form-group mb-3">
                        <span class="text-muted">Date Filed</span>
                        <input type="text" name="DateFiled" id="DateFiled" class="form-control" value="{{ date('Y-m-d') }}"/>
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
                        <input type="text" name="Day" id="Day" class="form-control"/>
                    </div>

                    <table id="dates-table" class="table table-hover table-borderless table-sm">
                        <tbody>
                            
                        </tbody>
                    </table>
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
                                maxYear: parseInt(moment().format('YYYY'),10)
                            }, function(start, end, label) {
                                addDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
                            });
                        </script>
                    @endpush
                </div>
                <div class="card-footer">
                    <button id="saveLeave" onclick="saveLeave()" class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Publish Leave</button>

                    <p class="text-muted gone" id="not-allowed-note">Non-regular employees are not allowed to file leave yet.</p>
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
                    <div class="card bg-danger gone" id="alert-no-sig">
                        <div class="card-body">
                            <h4><i class="fas fa-exclamation-triangle ico-tab"></i>Oops!</h4>
                            <p>No signatory found for this employee! Contact IT for troubleshooting.</p>
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
        var leaveDates = []
        var leaveBalances = []
        var signatories = []
        var otherSigs = []

        /**
         * LEAVE BALANCES 
         */
        function getLeaveBalances(employeeId) {
            leaveBalances = []
            $.ajax({
                url : "{{ route('leaveApplications.get-leave-balances-by-employee') }}",
                type : "GET",
                data : {
                    EmployeeId : employeeId
                },
                success : function(res) {
                    leaveBalances = res
                    updateLeaveBalancesTable(res.EmployeeData)
                    getSignatories(employeeId, res.EmployeeData)
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting leave balances'
                    })
                }
            })
        }

        function updateLeaveBalancesTable(employeeData) {
            if (employeeData.Mother === 'Yes') {
                $('#balance-maternity').html(isNull(leaveBalances.Maternity) ? '...' : leaveBalances.Maternity + " <span class='text-muted'>days</span>")
                $('#op-maternity').removeClass('gone')
            } else {
                $('#balance-maternity').html("")
                $('#op-maternity').addClass('gone')
            }

            if (employeeData.SoloMother === 'Yes') {
                $('#balance-maternity-solo-parent').html(isNull(leaveBalances.MaternityForSoloMother) ? '...' : leaveBalances.MaternityForSoloMother + " <span class='text-muted'>days</span>")
                $('#op-maternity-solo').removeClass('gone')
            } else {
                $('#balance-maternity-solo-parent').html("")
                $('#op-maternity-solo').addClass('gone')
            }

            if (employeeData.SoloParent === 'Yes') {
                $('#balance-solo-parent').html(isNull(leaveBalances.SoloParent) ? '...' : leaveBalances.SoloParent + " <span class='text-muted'>days</span>")
                $('#op-solo').removeClass('gone')
            } else {
                $('#balance-solo-parent').html("")
                $('#op-solo').addClass('gone')
            }

            if (employeeData.Father === 'Yes') {
                $('#balance-paternity').html(isNull(leaveBalances.Paternity) ? '...' : leaveBalances.Paternity + " <span class='text-muted'>days</span>")
                $('#op-paternity').removeClass('gone')
            } else {
                $('#balance-paternity').html("")
                $('#op-paternity').addClass('gone')
            }

            $('#balance-vacation').html(isNull(leaveBalances.Vacation) ? '...' : leaveBalances.VacationExpanded)
            $('#balance-sick').html(isNull(leaveBalances.Sick) ? '...' : leaveBalances.SickExpanded)
            $('#balance-special').html(isNull(leaveBalances.Special) ? '...' : leaveBalances.Special + " <span class='text-muted'>days</span>")
            
            // validate select
            $('input[name="LeaveType"]').prop('checked', false);

        }

        function getSignatories(employeeId, employeeData) {
            signatories = []
            otherSigs = []
            $('#signatories-table tbody tr').remove()

            $.ajax({
                url : "{{ route('leaveApplications.get-signatories-for-employee') }}",
                type : "GET",
                data : {
                    EmployeeId : employeeId,
                },
                success : function(res) {
                    if (isNull(res.Signatories) | res.length < 1 | res.Signatories.length < 1) {
                        $('#alert-no-sig').removeClass('gone')
                        $('#saveLeave').addClass('gone')
                    } else {
                        $('#alert-no-sig').addClass('gone')
                        $('#saveLeave').removeClass('gone')
                        
                        // don't allow leave filing if not regular
                        if (employeeData.PositionStatus === 'Regular') {
                            $('#saveLeave').removeClass('gone')
                            $('#not-allowed-note').addClass('gone')
                        } else {
                            $('#saveLeave').addClass('gone')
                            $('#not-allowed-note').removeClass('gone')
                        }

                        signatories = res.Signatories
                        otherSigs = res.OtherSignatories
                        updateSignatoriesTable()
                    }
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting signatories for this employee'
                    })
                }
            })
        }

        function updateSignatoriesTable() {
            $.each(signatories, function(i, el) {
                $('#signatories-table tbody').append(`
                    <tr>
                        <td>
                            <span class='text-muted text-sm'>Approver #${ i+1 }</span>
                            <br>
                            <select class="custom-select select2">
                                ` + setSignatoryOptions(signatories[i].id) + `
                            </select>
                        </td>
                    </tr>
                `)
            })
        }

        function setSignatoryOptions(parentSignatorySelect) {
            var opts = ``
            for(let x=0; x<otherSigs.length; x++) {
                var selected = otherSigs[x].id===parentSignatorySelect ? 'selected' : ''
                opts += `<option value='${ otherSigs[x].id }' ${ selected }>${ otherSigs[x].LastName + ', ' + otherSigs[x].FirstName }</option>`
            }

            return opts
        }

        function getSelectedSignatories() {
            var signatoryUserIds= []
            const table = document.getElementById('signatories-table');

            const tds = table.getElementsByTagName('td');

            for (let i = 0; i < tds.length; i++) {
                const select = tds[i].querySelector('select');
                
                if (select) {
                    const selectedOption = select.options[select.selectedIndex].value;
                    
                    signatoryUserIds.push({
                        Rank : i+1,
                        UserId : selectedOption
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
        }

        function addDates(start, end) {
            enumerateDaysBetweenDates(start, end)
            //filter duplicate dates
            leaveDates = leaveDates.filter((obj, index, self) =>
                    index === self.findIndex((t) => t.LeaveDate === obj.LeaveDate)
                )

            populateLeaveTable(leaveDates)
        }

        function enumerateDaysBetweenDates(startDate, endDate) {
            var now = moment(startDate).clone(), dates = [];

            while (now.isSameOrBefore(endDate)) {
                leaveDates.push({
                    LeaveDate : now.format('YYYY-MM-DD'),
                    Duration : 'WHOLE'
                });
                now.add(1, 'days');
            }
        }

        function populateLeaveTable() {
            $('#dates-table tbody tr').remove()
            for (let i=0; i<leaveDates.length; i++) {
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
                obj.LeaveDate === date ? { ...obj, Duration: val } : obj
            )
        }

        /**
         * SAVE 
         */
        function saveLeave() {
            var employee = $('#EmployeeId').val()
            var leaveType = $('input[name="LeaveType"]:checked').val()
            var dateFiled = $('#DateFiled').val()
            var reason = $('#Reason').val()

            if (isNull(employee) | isNull(leaveType) | isNull(dateFiled) | isNull(reason) | leaveDates.length < 1) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Please supply all fields'
                })
            } else {
                $.ajax({
                    url : "{{ route('leaveApplications.save-for-coworker') }}",
                    type : "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        EmployeeId : employee,
                        LeaveType : leaveType,
                        Reason : reason,
                        DateFiled : dateFiled,
                        Days : leaveDates,
                        Signatories : getSelectedSignatories(),
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Leave entry for ' + $('#EmployeeId option:selected').text() + ' added'
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
                    },
                    error : function(err) {
                        console.log(err)
                        Toast.fire({
                            icon : 'error',
                            text : 'Leave failed!'
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
            })
        })
    </script>
@endpush