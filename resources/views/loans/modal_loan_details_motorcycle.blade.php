<div class="modal fade" id="modal-loan-details" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Loan Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered table-sm" id="loan-details-table">
                    <thead>
                        <th></th>
                        <th>Term</th>
                        <th>Monthly<br>Amort.</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle ico-tab-mini"></i>Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        
    </script>
@endpush