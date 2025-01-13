@php
    use App\Models\Employees;
    use App\Models\LeaveBalances;
@endphp
@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="row">
            {{-- FORM --}}
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>File Here</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-borderless">
                            <tbody>
                                <tr>
                                    <td style="max-width: 100px;">Employee</td>
                                    <td></td>
                                    <td>
                                        <select class="custom-select select2" name="EmployeeId" id="EmployeeId"
                                            style="width: 100%;" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($employees as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ Auth::user()->employee_id == $item->id ? 'selected' : '' }}
                                                    sick="{{ LeaveBalances::toDay($item->Sick) }}"
                                                    vacation="{{ LeaveBalances::toDay($item->Vacation) }}">
                                                    {{ Employees::getMergeNameFormal($item) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #dbdbdb; padding-bottom: 20px;">
                                    <td>Date Filed</td>
                                    <td></td>
                                    <td>
                                        <input type="date" class="form-control" id="DateFiled"
                                            value="{{ date('Y-m-d') }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>Balances</th>
                                    <th># of Days (15 days max)</th>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-umbrella-beach ico-tab-mini"></i>Vacation</td>
                                    <td>
                                        <span class="text-muted">Balance: </span><strong id="vacation-balance"></strong><br>
                                        <span class="text-muted">Available: </span><strong id="vacation-available"></strong>
                                    </td>
                                    <td>
                                        <input id="vacation" disabled type="number" class="form-control" min="0"
                                            max="15">
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-clinic-medical ico-tab-mini"></i>Sick</td>
                                    <td>
                                        <span class="text-muted">Balance: </span><strong id="sick-balance"></strong><br>
                                        <span class="text-muted">Available: </span><strong id="sick-available"></strong>
                                    </td>
                                    <td>
                                        <input id="sick" disabled type="number" class="form-control" min="0"
                                            max="15">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <button onclick="addToQueue()" class="btn btn-primary-skinny float-right"><i
                                    class="fas fa-plus-circle ico-tab-mini"></i>Add To Queue</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-list ico-tab-mini"></i>Queued Request</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-bordered" id="queue-table">
                            <thead>
                                <th>Employee</th>
                                <th>Date Filed</th>
                                <th>Vacation</th>
                                <th>Sick</th>
                                <th style="width: 32px;"></th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button onclick="submitRequest()" class="btn btn-primary float-right">Submit Request <i
                                class="fas fa-sign-in-alt ico-tab-left-mini"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var availableVacation = 0
        var availableSick = 0
        var queuedArray = []

        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html(
                "<span class='text-muted'>Manual Entry - </span> <strong>Leave Credits Conversion</strong>")

            fetchAvailability()

            $('#EmployeeId').on('change', function() {
                fetchAvailability()
            })

            $("input").keydown(function() {
                // Save old value.
                if (!$(this).val() || (parseInt($(this).val()) <= 15 && parseInt($(this).val()) >= 0))
                    $(this).data("old", $(this).val());
            });
            $("input").keyup(function() {
                // Check correct, else revert back to old value.
                if (!$(this).val() || (parseInt($(this).val()) <= 15 && parseInt($(this).val()) >= 0))
                ;
                else
                    $(this).val($(this).data("old"));
            });
        })

        function fetchAvailability() {
            var vacationBalance = isNull($('#EmployeeId option:selected').attr('vacation')) ? 0 : $(
                '#EmployeeId option:selected').attr('vacation')
            var sickBalance = isNull($('#EmployeeId option:selected').attr('sick')) ? 0 : $('#EmployeeId option:selected')
                .attr('sick')

            // deduct balances if employee is already added in the queue
            var employeeId = $('#EmployeeId').val()

            const empAdded = queuedArray.filter(obj => obj.EmployeeId === employeeId)
            var vacationAdded = 0
            var sickAdded = 0
            for (let i = 0; i < empAdded.length; i++) {
                vacationAdded += empAdded[i].Vacation
                sickAdded += empAdded[i].Sick
            }

            // start displaying
            $('#vacation-balance').text(vacationBalance)
            $('#sick-balance').text(sickBalance)

            // VACATION
            if ((vacationBalance - vacationAdded) >= 15) {
                $('#vacation').prop('disabled', false)
                availableVacation = (vacationBalance - vacationAdded)
            } else {
                $('#vacation').prop('disabled', true)
                availableVacation = 0
            }
            $('#vacation-available').text(round(availableVacation))

            // SICK
            if ((sickBalance - sickAdded) >= 150) {
                $('#sick').prop('disabled', false)
                availableSick = (sickBalance - (150 + sickAdded))
            } else {
                $('#sick').prop('disabled', true)
                availableSick = 0
            }
            $('#sick-available').text(round(availableSick))
        }

        function addToQueue() {
            var vacation = $('#vacation').val()
            var sick = $('#sick').val()
            var employeeId = $('#EmployeeId').val()
            var employeeName = $('#EmployeeId option:selected').text()
            var dateFiled = $('#DateFiled').val()

            if (isNull(employeeId) | (isNull(sick) && isNull(vacation))) {
                Toast.fire({
                    icon: 'info',
                    text: 'Please select employee and provide at least one conversion item'
                })
            } else {
                vacation = isNull(vacation) ? 0 : parseFloat(vacation)
                sick = isNull(sick) ? 0 : parseFloat(sick)

                var vacationGo = false
                var sickGo = false

                if (availableSick >= sick) {
                    sickGo = true
                } else {
                    sickGo = false
                }

                if (availableVacation >= vacation) {
                    vacationGo = true
                } else {
                    vacationGo = false
                }

                if (sickGo && vacationGo) {
                    // Remove existing first
                    queuedArray = queuedArray.filter(obj => obj.EmployeeId !== employeeId)

                    // Add item
                    queuedArray.push({
                        EmployeeId: employeeId,
                        EmployeeName: employeeName,
                        Vacation: vacation,
                        Sick: sick,
                        DateFiled: dateFiled,
                    })
                } else {
                    Toast.fire({
                        icon: 'warning',
                        text: 'Number of days to be converted should be less than or equal to the availble days for conversion.'
                    })
                }
            }

            refreshTable()

            $('#vacation').val(null).change()
            $('#sick').val(null).change()
        }

        function refreshTable() {
            $('#queue-table tbody tr').remove()

            $.each(queuedArray, function(index, element) {
                $('#queue-table tbody').append(`
                    <tr>
                        <td>` + queuedArray[index]['EmployeeName'] + `</td>
                        <td>` + queuedArray[index]['DateFiled'] + `</td>
                        <td>` + queuedArray[index]['Vacation'] + `</td>
                        <td>` + queuedArray[index]['Sick'] + `</td>
                        <td class='text-right' style="width: 32px;">
                            <button onclick='attemptRemove("` + queuedArray[index]['EmployeeId'] + `")' class='btn btn-sm btn-link text-danger'><i class='fas fa-trash'></i></button>    
                        </td>
                    </tr>
                `)
            })
        }

        function attemptRemove(id) {
            Swal.fire({
                title: "Confirm Remove",
                text: 'Remove this leave conversion data from queue?',
                showCancelButton: true,
                confirmButtonText: "Proceed Remove",
                confirmButtonColor: '{{ env('DANGER') }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    queuedArray = queuedArray.filter(obj => obj.EmployeeId !== id)
                    refreshTable()

                    Toast.fire({
                        icon: 'success',
                        text: 'Item removed!'
                    })
                }
            });
        }

        function submitRequest() {
            if (isNull(queuedArray)) {
                Toast.fire({
                    icon: 'info',
                    text: 'No data provided!'
                })
            } else {
                $.ajax({
                    url: "{{ route('leaveConversions.request-multiple') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        Requests: queuedArray,
                        Status: 'Approved',
                    },
                    success: function(res) {
                        Toast.fire({
                            icon: 'success',
                            text: 'Leave conversion requested!'
                        })
                        window.location.href = "{{ route('home') }}"
                    },
                    error: function(err) {
                        console.log(err)
                        Swal.fire({
                            icon: 'error',
                            text: 'An error occurred while performing the request!'
                        })
                    }
                })
            }
        }
    </script>
@endpush
