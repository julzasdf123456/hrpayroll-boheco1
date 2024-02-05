<div class="modal fade" id="modal-other-loans" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Loan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-right">Employee</td>
                            <td>
                                <select class="custom-select select2"  name="EmployeeId" id="EmployeeId">
                                    <option value="">-- Select --</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Loan For (Loan Name)</td>
                            <td>
                                <input id="LoanFor" class="form-control form-control-sm text-right" type="text" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Monthly Ammortization</td>
                            <td>
                                <input id="Amount" class="form-control form-control-sm text-right" type="number" step="any" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Terms in Months</td>
                            <td>
                                <input id="Terms" class="form-control form-control-sm text-right" type="number" step="any" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Starting Date</td>
                            <td>
                                <input id="StartingDate" class="form-control form-control-sm" type="date">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Deduction Period</td>
                            <td>
                                <select id="PaymentTerm" class="form-control form-control-sm">
                                    <option value="15/30">Every 15th and 30th</option>
                                    <option value="15">Every 15th</option>
                                    <option value="30">Every 30th</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="save-loan" type="button" class="btn btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle ico-tab-mini"></i>Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $('#save-loan').on('click', function() {
            if (isNull($('#LoanFor').val()) | isNull($('#Amount').val()) | isNull($('#Terms').val()) | isNull($('#StartingDate').val())) {
                Toast.fire({
                    icon : 'info',
                    text : 'Kindly fill in all fields!'
                })
            } else {
                $.ajax({
                    url : "{{ route('loans.save-other-loans') }}",
                    type : 'GET',
                    data : {
                        EmployeeId : $('#EmployeeId').val(),
                        MonthlyAmmortization : $('#Amount').val(),
                        Terms : $('#Terms').val(),
                        StartingDate : $('#StartingDate').val(),
                        LoanFor : $('#LoanFor').val(),
                        PaymentTerm : $('#PaymentTerm').val(),
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Loan created!'
                        })
                        location.reload()
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error saving loan!'
                        })
                    }
                })
            }
            
        })

        $('#modal-other-loans').on('shown.bs.modal', function (e) {
            $('#EmployeeId option').remove()
            $.ajax({
                url : "{{ route('employees.get-employees-ajax') }}",
                type : "GET",
                success : function(res) {
                    if (!isNull(res)) {
                        $.each(res, function(index, element) {
                            $('#EmployeeId').append(`<option value='` + res[index]['id'] + `'>` + serializeEmployeeNameFormalNoMiddle(res[index]['FirstName'], res[index]['LastName'], res[index]['Suffix']) + `</option>`)
                        })
                    }
                },
                error : function(error) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error getting employees!'
                    })
                }
            })
        })

        $('#modal-other-loans').on('hidden.bs.modal', function (e) {
            $('#Amount').val('')
            $('#Terms').val('')
            $('#LoanFor').val('')
            $('#StartingDate').val('')
        })
    </script>
@endpush