<div class="modal fade" id="modal-configure-incentives-wt" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: 90% !important; margin-top: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Configure Withholding Taxes for Incentives & Bonuses</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="text-muted">Employee:</span>
                <h4 id="employee-name"></h4>
                <table id="wt-table" class="table table-sm table-hover table-bordered">
                    <thead>
                        <th class='text-center'>Incentive</th>
                        <th class='text-center'>Projected Amount</th>
                        <th class='text-center'>Received Actual<br>Amount</th>
                        <th class='text-center'>Is Taxable</th>
                        <th class='text-center'>Max Untaxable<br>Amount</th>
                        <th class='text-center'>Deduct Monthly?</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="save-incentive-config" type="button" class="btn btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle ico-tab-mini"></i>Close</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        
    </script>
@endpush