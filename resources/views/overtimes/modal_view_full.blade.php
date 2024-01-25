<div class="modal fade" id="modal-ot-view" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        <span><strong id="ot-employee"></strong></span>
                    </h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
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

     </script>
 @endpush