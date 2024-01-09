<div class="modal fade" id="modal-leave-logs" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Leave Credit Logs</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Method</th>
                        <th># of Days</th>
                        <th>Log Date</th>
                        <th>Details</th>
                    </thead>
                    <tbody>
                        @foreach ($leaveBalanceDetails as $item)
                            <tr>
                                <td class="{{ $item->Method=='ADD' ? 'text-success' : 'text-danger' }}">{{ $item->Method }}</td>
                                <td>{{ $item->Days }}</td>
                                <td>{{ date('M d, Y h:i:s A', strtotime($item->created_at)) }}</td>
                                <td>{{ $item->Details }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>