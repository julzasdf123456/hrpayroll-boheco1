<div class="modal fade" id="modal-retire" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Retire/Resign Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="text-muted">Choose Termination Type</label>
                    <br>
                    <select class="form-control" id="end-type">
                        <option value="Resigned">Resignation</option>
                        <option value="Retired">Retirement</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="text-muted">Effective Date</label>
                    <br>
                    <input type="date" class="form-control" id="effective-date" max="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-success" id="submit-retire"><i class="fas fa-check-circle ico-tab-mini"></i>Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times-circle ico-tab-mini"></i>Close</button>
            </div>
        </div>
    </div>
</div>