<div class="modal fade" id="modal-payroll-sundries" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Configure Payroll Deductions/Sundries</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-right">Longevity</td>
                            <td>
                                <input id="sundries-longevity" class="form-control form-control-sm text-right" type="number" style="font-weight: bold;" autofocus>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Rice Allowance</td>
                            <td>
                                <input id="sundries-rice-allowance" class="form-control form-control-sm text-right" type="number" style="font-weight: bold;" value="2500">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Insurance</td>
                            <td>
                                <input id="sundries-insurance" class="form-control form-control-sm text-right" type="number" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">Pag-Ibig Contribution</td>
                            <td>
                                <input id="sundries-pag-ibig-contribution" class="form-control form-control-sm text-right" type="number" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">SSS Contribution</td>
                            <td>
                                <input id="sundries-sss-contribution" class="form-control form-control-sm text-right" type="number" style="font-weight: bold;">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">PhilHealth</td>
                            <td>
                                <input id="sundries-philhealth" class="form-control form-control-sm text-right" type="number" style="font-weight: bold;">
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="text-right">Remarks/Notes</td>
                            <td>
                                <textarea id="sundries-notes" class="form-control form-control-sm text-right" rows="3">
                                </textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="save-sundries" type="button" class="btn btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle ico-tab-mini"></i>Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        $('#save-sundries').on('click', function() {
            $.ajax({
                url : "{{ route('employees.save-payroll-sundries') }}",
                type : "GET",
                data : {
                    EmployeeId : "{{ $employees->id }}",
                    Longevity : $('#sundries-longevity').val(),
                    RiceAllowance :  $('#sundries-rice-allowance').val(),
                    Insurance :  $('#sundries-insurance').val(),
                    PagIbigContribution :  $('#sundries-pag-ibig-contribution').val(),
                    SSSContribution :  $('#sundries-sss-contribution').val(),
                    PhilHealth :  $('#sundries-philhealth').val(),
                    Notes :  $('#sundries-notes').val(),
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Payroll deductions and sundries configured!'
                    })
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error configuring payroll deductions and sundries'
                    })
                }
            })
        })
    </script>
@endpush