@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                        File a Travel Order
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                {{-- DETAILS --}}
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Travel Order Details</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover table-borderless">
                            <tbody>
                                <tr>
                                    <td class="v-align text-muted">Date Filed <strong class="text-danger">*</strong></td>
                                    <td>
                                        <input id="DateFiled" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="v-align text-muted">Destination <strong class="text-danger">*</strong></td>
                                    <td>
                                        <input id="Destination" type="text" class="form-control" placeholder="Venue or Place...">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted pt-2">Purpose <strong class="text-danger">*</strong></td>
                                    <td>
                                        <textarea id="Purpose" type="text" class="form-control" rows="3" placeholder="Seminar, training, official travel, etc..."></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
                {{-- Employees --}}
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-users ico-tab"></i>Employees in this Travel Order</span>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="form-group">
                            <span class="text-muted">Select Employee <strong class="text-danger">*</strong></span>
                            <select class="custom-select select2"  name="EmployeeId" id="EmployeeId" style="width: 100%;">
                                <option value="">-- Select --</option>
                                @foreach ($employees as $item)
                                    <option value="{{ $item->id }}">{{ Employees::getMergeNameFormal($item) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <table class="table table-hover table-sm table-borderless" id="EmployeesTable">
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
                
                {{-- Dates --}}
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-calendar ico-tab"></i>Date(s) of Travel</span>
                    </div>
                    <div class="card-body table-responsive">
                        <span class="text-muted">Select Dates <strong class="text-danger">*</strong></span>
                        <input type="text" id="Days" class="form-control" placeholder="Select Date Range"/>

                        @push('page_scripts')
                            <script>
                                
                                $('#Days').daterangepicker({
                                    showDropdowns: true,
                                    alwaysShowCalendars: true,
                                    // isInvalidDate: function(date) {
                                    //     if (date.day() == 0 | holidays.includes(date.format('YYYY-MM-DD'))) {
                                    //         return true
                                    //     } else {
                                    //         return false
                                    //     }
                                    // },
                                    minYear: 1901,
                                    maxYear: parseInt(moment().format('YYYY'),10)
                                }, function(start, end, label) {
                                    addDates(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'))
                                });
                            </script>
                        @endpush
                    
                        <table class="table table-borderless table-hover table-sm" id="DatesTable">
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button onclick="saveTravelOrder()" class="btn btn-primary"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var dates = []
        var employees = []

        function deleteDate(date) {
            Swal.fire({
                title: 'Delete Confirmation',
                text : 'Are you sure you want to remove this date?',
                showDenyButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `No`,
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    dates = dates.filter((value, index) => value !== date)

                    refreshDates()
                }
            })            
        }

        function addDates(start, end) {
            var currentDate =  moment(start)

            while (currentDate.isSameOrBefore(end, 'day')) {
                // Add the current date to the array
                if (dates.includes(currentDate.format('YYYY-MM-DD'))) {
                    
                } else {
                    dates.push(currentDate.format('YYYY-MM-DD'))
                }
                
                // Move to the next day
                currentDate.add(1, 'days')
            }

            refreshDates()
        }

        function refreshDates() {
            $('#DatesTable tbody tr').remove()

            var len = dates.length
            for(let i=0; i<len; i++) {
                $('#DatesTable tbody').append(`
                    <tr>
                        <td>` + moment(dates[i]).format('MMM DD, YYYY (ddd)') + `</td>
                        <td class='text-right'>
                            <button class='btn btn-link text-danger btn-sm' onclick='deleteDate("` + dates[i] + `")'><i class='fas fa-trash'></i></button>    
                        </td>
                    </tr>
                `)
            }
        }

        function refreshEmployees() {
            $('#EmployeesTable tbody tr').remove()

            var len = employees.length
            for(let i=0; i<len; i++) {
                $('#EmployeesTable tbody').append(`
                    <tr>
                        <td>` + employees[i].name + `</td>
                        <td class='text-right'>
                            <button class='btn btn-link text-danger btn-sm' onclick='deleteEmployee("` + employees[i].id + `")'><i class='fas fa-trash'></i></button>    
                        </td>
                    </tr>
                `)
            }
        }

        function deleteEmployee(id) {
            employees = employees.filter(obj => obj.id !== id)

            refreshEmployees()
        }

        function saveTravelOrder() {
            var dateFiled = $('#DateFiled').val()
            var destination = $('#Destination').val()
            var purpose = $('#Purpose').val()

            if (isNull(dateFiled) | isNull(destination) | isNull(purpose) | isNull(dates) | isNull(employees)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Please fill in all required fields!'
                })
            } else {
                $.ajax({
                    url : "{{ route('travelOrders.create-order') }}",
                    type : "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        DateFiled : dateFiled,
                        Destination : destination,
                        Purpose : purpose,
                        Dates : dates,
                        Employees : employees,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Travel order filed!'
                        })

                        window.location.href = "{{ route('home') }}"
                    },
                    error : function(xhr, status, error) {
                        Swal.fire({
                            icon : 'error',
                            title : xhr.status,
                            text : xhr.statusText
                        })
                        console.log(error)
                        console.log(xhr)
                    }
                })
            }
        }

        $(document).ready(function() {            
            $('#EmployeeId').on('change', function() {
                var id = this.value
                var name = $('#EmployeeId option:selected').text()

                if (!employees.some(obj => obj.id === id)) {
                    employees.push({
                        id : id,
                        name : name,
                    })
                }

                refreshEmployees()
            })
        })
    </script>
@endpush
