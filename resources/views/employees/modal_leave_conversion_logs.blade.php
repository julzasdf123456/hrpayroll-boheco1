@php
    use App\Models\Employees;
@endphp

<div class="modal fade" id="modal-leave-conversion-logs" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: 90% !important; margin-top: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        Leave Conversion History
                        <div id="loader" class="spinner-border text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-hover table-bordered table-sm" id="leave-conversions-table">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2">Date Filed</th>
                            <th class="text-center" rowspan="2">Status</th>
                            <th class="text-center" colspan="2">Vacation</th>
                            <th class="text-center" colspan="2">Sick</th>
                            <th class="text-center" rowspan="2">Total Amount</th>
                            <th class="text-center" rowspan="2"></th>
                        </tr>
                        <tr>
                            <th class="text-center"># of Days</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center"># of Days</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        $('#modal-leave-conversion-logs').on('shown.bs.modal', function(e) {
            $('#loader').removeClass('gone')
            $('#leave-conversions-table tbody tr').remove()
            $.ajax({
                url: "{{ route('leaveConversions.get-leave-conversions-data') }}",
                type: "GET",
                data: {
                    EmployeeId: "{{ $employees->id }}"
                },
                success: function(res) {
                    $('#loader').addClass('gone')

                    $.each(res, function(index, element) {
                        var vacation = isNull(res[index]['VacationAmount']) ? 0 : parseFloat(
                            res[index]['VacationAmount'])
                        var sick = isNull(res[index]['SickAmount']) ? 0 : parseFloat(res[index][
                            'SickAmount'
                        ])
                        $('#leave-conversions-table tbody').append(`
                            <tr id='${ res[index]['id'] }'>
                                <td>` + (moment(res[index]['created_at']).format('MMM DD, YYYY h:m A')) + `</td>
                                <td class='text-center'><span class='badge bg-info'>` + res[index]['Status'] + `</span></td>
                                <td class='text-right'>` + res[index]['VacationDays'] + `</td>
                                <td class='text-right'>` + toMoney(vacation) + `</td>
                                <td class='text-right'>` + res[index]['SickDays'] + `</td>
                                <td class='text-right'>` + toMoney(sick) + `</td>
                                <td class='text-right'><strong>` + toMoney(sick + vacation) +
                            `</strong></td>
                                <td class='text-right'>
                                    <button class='btn btn-sm btn-link text-danger' title='Delete Log' onclick="deleteConversionLog('` +
                            res[index]['id'] + `')"><i class='fas fa-trash'></i></button>    
                                </td>
                            </tr>
                        `)
                    })
                },
                error: function(err) {
                    $('#loader').addClass('gone')
                    Toast.fire({
                        icon: 'error',
                        text: 'Error getting leave conversions history'
                    })
                }
            })
        })

        function deleteConversionLog(id) {
            Swal.fire({
                title: "Delete Log?",
                text: "Deleting this log will not affect the leave balances of the employee. Proceed with caution.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3273a8",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/leaveConversions') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(res) {
                            Toast.fire({
                                icon: 'success',
                                text: 'Conversion log removed!'
                            })
                            $('#' + id).remove()
                        },
                        error: function(err) {
                            Toast.fire({
                                icon: 'error',
                                text: 'Error deleting conversion log!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush
