@php
    use App\Models\Employees;
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                    Offset Application Form
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>File Here</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered" id="offset-table">
                    <thead>
                        <td>Employee</td>
                        <td>Date Of Duty</td>
                        <td>Duty Purpose/Reason</td>
                        <td>Date of Offset</td>
                        <td>Reason</td>
                        <td></td>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="custom-select select2" name="EmployeeId" id="EmployeeId" style="width: 100%;" required>
                                    <option value={{ $self->id }} selected>{{ Employees::getMergeNameFormal($self) }}</option>
                                    {{-- @foreach ($employees as $item)
                                        @if ($item->id == Auth::user()->employee_id)
                                            @continue
                                        @endif
                                        <option value="{{ $item->id }}">{{ Employees::getMergeNameFormal($item) }}</option>
                                    @endforeach --}}
                                </select>
                            </td>
                            <td>
                                <input type="date" name="DateOfDuty" id="DateOfDuty" class="form-control" value="{{ date('Y-m-d') }}" required></td>
                            </td>                            
                            <td>
                                <input type="text" name="PurposeOfDuty" id="PurposeOfDuty" class="form-control" required></td>
                            </td>
                            <td>
                                <input type="date" name="OffsetDate" id="OffsetDate" class="form-control" value="{{ date('Y-m-d') }}" required></td>
                            </td>                           
                            <td>
                                <input type="text" name="Reason" id="Reason" class="form-control" required></td>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary float-right" onclick="addItem()"><i class="fas fa-plus-circle ico-tab-mini"></i>Add</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" onclick="save()"><i class="fas fa-check-circle ico-tab"></i>Save</button>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var items = []
        var index = 0
        $(document).ready(function() {

        })

        function addItem() {
            var employeeId = $('#EmployeeId').val()
            var employeeName = $("#EmployeeId option:selected").text()
            var dateOfDuty = $('#DateOfDuty').val()
            var purpose = $('#PurposeOfDuty').val()
            var dateOfOffset = $('#OffsetDate').val()
            var reason = $('#Reason').val()
            
            if (isNull(employeeId) | isNull(dateOfDuty) | isNull(purpose) | isNull(dateOfOffset) | isNull(reason)) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Please comply all fields to add!'
                })
            } else {                
                items.push({
                    Index : index,
                    EmployeeId : employeeId,
                    DateOfDuty : dateOfDuty,
                    Purpose : purpose,
                    DateOfOffset : dateOfOffset,
                    Reason : reason,
                })
                $('#offset-table tbody').prepend(addTableRow(index, employeeName, moment(dateOfDuty).format('MMM DD, YYYY'), purpose, moment(dateOfOffset).format('MMM DD, YYYY'), reason))
            }
            // RESET VALUES
            $('#EmployeeId').val('').change()
            $('#Reason').val('')
            index++
        }

        function addTableRow(index, name, dateOfDuty, purpose, dateOfOffset, reason) {
            return "<tr id='" + index + "'>" +
                        "<td>" + name + "</td>" +  
                        "<td>" + dateOfDuty + "</td>" + 
                        "<td>" + purpose + "</td>" +
                        "<td>" + dateOfOffset + "</td>" +
                        "<td>" + reason + "</td>" +
                        "<td><button onclick='remove(`" + index + "`)' class='btn btn-sm btn-link float-right'><i class='fas fa-trash text-danger'></i></button></td>" +
                    "</tr>"
        }

        function save() {
            if (isNull(items)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Add employees first'
                })
            } else {
                Swal.fire({
                    title: "Confirm Offset Application?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3273a8",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : "{{ route('offsetApplications.save-offset-applications') }}",
                            type : "GET",
                            data : {
                                Data : items,
                            },
                            success : function(res) {
                                Toast.fire({
                                    icon : 'success',
                                    text : 'Offset filing success!'
                                })
                                window.location.href = "{{ route('home') }}"
                            },
                            error : function(err) {
                                Swal.fire({
                                    icon : 'error',
                                    text : 'An error occurred during filing of offset'
                                })
                            }
                        })
                    }
                })
            }
        }

        function remove(id) {
            $('#' + id).remove()

            // remove from items
            items = items.filter(function (obj) { 
                return obj.Index != id
            })

            console.log(items)
        }
    </script>
@endpush
