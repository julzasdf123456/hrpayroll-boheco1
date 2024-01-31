<div class="modal fade" id="modal-ot-view" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: 90% !important; margin-top: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        <span><strong id="ot-employee"></strong></span>
                    </h4>
                    <span class="badge" id="ot-status"></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8">
                        <p class="text-muted">Overtime Details</p>
                        <table class="table table-sm table-bordered table-hover" id="bio-table">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Purpose</td>
                                    <td id="ot-purpose"></td>                            
                                </tr>
                                <tr>    
                                    <td class="text-muted">Leave Type</td>
                                    <td id="ot-leave-type"></td>
                                </tr> 
                                <tr>
                                    <td class="text-muted">Start Date</td>
                                    <td id="ot-start-date"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Start Time</td>
                                    <td id="ot-start-time"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">End Date</td>
                                    <td id="ot-end-date"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">End Time</td>
                                    <td id="ot-end-time"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Max Hours</td>
                                    <td id="ot-max-hours"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total Hours</td>
                                    <td id="ot-total-hours"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Prepared By</td>
                                    <td id="ot-prepared-by"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date Prepared</td>
                                    <td id="ot-date-prepared"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Remarks/Notes</td>
                                    <td id="ot-notes"></td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>

                    <div class="col-lg-4">
                        <p class="text-muted">Overtime Signatory Logs</p>

                        <div id="ot-logs"></div>
                    </div>
                </div>              
            </div>
        </div>
    </div>
 </div>

 @push('page_scripts')
     <script>
        $(document).ready(function() {
            
        })

        function addLogsRow(data) {
            var response = ""
            $.each(data, function(index, element) {
                response += '<div class="ot-log mb-2 p-2 {{ Auth::user()->ColorProfile != null ? "border-left-dark" : "border-left-light" }}">' +
                                '<span style="font-size: .85em;" class="badge ' + getStatusColor(data[index]['Status']) + '">' + (isNull(data[index]['Status']) ? 'PENDING APPROVAL' : data[index]['Status']) + '</span>' +
                                '<br>' +
                                '<span class="ico-tab-mini">' + data[index]['name'] + '</span>' +
                                '<span style="font-size: .85em;" class="text-muted">' + (isNull(data[index]['Status']) ? '-' : moment(data[index]['updated_at']).format('MMM DD, YYYY hh:mm A')) + '</span>' +
                            '</div>'
            })

            return response
        }

        function getStatusColor(status) {
            if (isNull(status)) {
                return 'bg-warning'
            } else {
                if (status === 'APPROVED') {
                    return 'bg-success'
                } else {
                    return 'bg-danger'
                }
            }
        }
     </script>
 @endpush