<div class="modal fade" id="modal-create-deduction" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Deduction Profile</h4>
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
                            <td class="text-right">Deduction Amount</td>
                            <td>
                                <input id="Amount" class="form-control text-right" type="number" step="any" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Deduction For</td>
                            <td>
                                <input id="DeductionFor" class="form-control text-right" type="text">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Deduct On</td>
                            <td>
                                <select class="custom-select select2"  name="ScheduleDate" id="ScheduleDate">
                                    
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="save-deduction" type="button" class="btn btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle ico-tab-mini"></i>Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $('#save-deduction').on('click', function() {
            $.ajax({
                url : "{{ route('otherPayrollDeductions.store') }}",
                type : 'POST',
                data : {
                    _token : "{{ csrf_token() }}",
                    EmployeeId : $('#EmployeeId').val(),
                    Amount : $('#Amount').val(),
                    DeductionName : $('#DeductionFor').val(),
                    ScheduleDate : $('#ScheduleDate').val(),
                    Type : 'Others',
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Other deduction saved!'
                    })
                    location.reload()
                },
                error : function(err) {
                    Toast.fire({
                        icon : 'error',
                        text : 'Error saving deduction!'
                    })
                }
            })
        })

        $('#modal-create-deduction').on('shown.bs.modal', function (e) {
            getFuturePayrolls()
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

        $('#modal-create-deduction').on('hidden.bs.modal', function (e) {
            $('#Amount').val('')
            $('#DeductionFor').val('')
            $('#ScheduleDate option').remove()
        })

        function getFuturePayrolls() {
            var date = moment().format("YYYY-MM-DD")

            var fifteenth = moment().format("YYYY-MM-15")

            var arrDates = []

            var startDate = null
            if (moment(date).isAfter(fifteenth)) {
                startDate = moment().endOf("month").format("YYYY-MM-DD")
                arrDates.push(moment().endOf("month").format("YYYY-MM-DD"))
            } else {
                startDate = fifteenth
                arrDates.push(moment().format("YYYY-MM-15"))
                arrDates.push(moment().endOf("month").format("YYYY-MM-DD"))
            }

            for(let i=1; i<11; i++) {
                arrDates.push(moment(startDate).add(i, 'month').format("YYYY-MM-15"))
                arrDates.push(moment(startDate).add(i, 'month').endOf("month").format("YYYY-MM-DD"))
            } 

            for(let i=0; i<arrDates.length; i++) {
                $('#ScheduleDate').append(`<option value='` + arrDates[i] + `'>` + moment(arrDates[i]).format('MMMM DD, YYYY') + `</option>`)
            }
        }
    </script>
@endpush