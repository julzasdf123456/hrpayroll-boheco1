@php
    use App\Models\Employees;
@endphp

@extends('layouts.app')

@section('content')
<div class="row" style="margin: 10px 10px 15px 10px;">
    <div class="col-lg-8">
        <h4>Employees' Incentive/Bonuses Withholding Taxes Configuration</h4>
    </div>

    <div class="col-lg-4">
        <form action="{{ route('employeeIncentiveAnnualProjections.incentive-withholding-taxes') }}" method="GET">
            <button class="btn btn-primary float-right">Filter</button>
            <select name="Department" class="form-control form-sm float-right" style="width: 200px; margin-right: 10px;">
                <option value="All" {{ isset($_GET['Department']) && $_GET['Department']=='All' ? 'selected' : '' }}>All</option>
                @foreach ($departments as $item)
                    <option value="{{ $item->Department }}" {{ isset($_GET['Department']) && $item->Department==$_GET['Department'] ? 'selected' : '' }}>{{ $item->Department }}</option>
                @endforeach
                <option value="SUB-OFFICE" {{ isset($_GET['Department']) && $_GET['Department']=='SUB-OFFICE' ? 'selected' : '' }}>SUB-OFFICE</option>
            </select>
        </form>
    </div>

    <div class="col-lg-10 offset-lg-1 col-md-12" style="margin-top: 15px;">
        <div class="card shadow-none">
            <div class="card-header">
                <div class="card-title">WT Tax Configuration for Year {{ date('Y') }}</div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <th style="width: 20px;">#</th>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Basic Salary</th>
                        <th>Projected Incentives</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($employees as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a target="_blank" href="{{ route('employees.show', [$item->id]) }}">{{ $item->id }}</a></td>
                                <td><strong>{{ Employees::getMergeNameFormal($item) }}</strong></td>
                                <td>{{ $item->Position }}</td>
                                <td class="text-right">{{ number_format($item->BasicSalary, 2) }}</td>
                                <td class="text-right">{{ isset($item->ProjectedIncentives) ? number_format($item->ProjectedIncentives, 2) : 0 }}</td>
                                <td class="text-right">
                                    <button class="btn btn-default btn-sm" onclick="showConfig(`{{ $item->id }}`, `{{ date('Y') }}`, `{{ Employees::getMergeNameFormal($item) }}`)">Configure WTs</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@include('employee_incentive_annual_projections.modal_configure_incentives_wt')

@push('page_scripts')
    <script>
        var deductionArray = []
        $(document).ready(function() {
            $('#save-incentive-config').on('click', function() {
                Swal.fire({
                    title: "Confirm Change",
                    text : 'By confirming, you are aware of the changes in Withholding Taxes computation on all future payrolls of this employee.',
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('employeeIncentiveAnnualProjections.update-all-deduct-monthly') }}",
                            type : "GET",
                            data : {
                                Data : deductionArray,
                            },
                            success : function(res) {
                                $('#modal-configure-incentives-wt').modal('hide')
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Configuration updated!'
                                })
                            },
                            error : function(err) {
                                Toast.fire({
                                    icon : 'error',
                                    text : 'Error updating WT configuration!'
                                })
                            }
                        })
                    }
                });
            })
        })

        function showConfig(employeeId, year, employeeName) {
            $('#wt-table tbody tr').remove()
            $('#modal-configure-incentives-wt').modal('show')
            $('#employee-name').text(employeeName)
            deductionArray = []

            $.ajax({
                url : "{{ route('employeeIncentiveAnnualProjections.get-employee-projection') }}",
                type : "GET",
                data : {
                    Year : year,
                    EmployeeId : employeeId,
                },
                success : function(res) {
                    if (!isNull(res)) {
                        $.each(res, function(index, element) {
                            deductionArray.push({
                                id : res[index]['id'],
                                DeductMonthly : res[index]['DeductMonthly']
                            })

                            $('#wt-table tbody').append(addTableRow(res[index]))
                        })
                    }
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error getting employee projection'
                    })
                }
            })
        }

        function addTableRow(resObjectItem) {
            return `<tr>
                        <td>` + resObjectItem['Incentive'] + `</td>
                        <td class='text-right'>` + toMoney(resObjectItem['Amount']) + `</td>
                        <td>` + (resObjectItem['IsTaxable']=='true' ? 'Yes' : 'No') + `</td>
                        <td class='text-right'>` + toMoney(resObjectItem['MaxUntaxableAmount']) + `</td>
                        <td class='text-center'>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" onchange='changeSelection("` + resObjectItem['id'] + `")' class="custom-control-input" ` + (resObjectItem['DeductMonthly']=='Yes' ? 'checked' : '') + ` id="switch-` + resObjectItem['id'] + `">
                                <label class="custom-control-label" for="switch-` + resObjectItem['id'] + `" style="font-weight: normal"></label>
                            </div>
                        </td>
                    </tr>`
        }

        function changeSelection(id) {
            var valueOfSelection = 'No'
            if ($('#switch-' + id).prop('checked')) {
                valueOfSelection = 'Yes'
            } else {
                valueOfSelection = 'No'
            }

            const indexOfId = deductionArray.findIndex(obj => obj.id === id)

            if (indexOfId !== -1) {
                deductionArray[indexOfId].DeductMonthly = valueOfSelection
            }
        }
    </script>
@endpush