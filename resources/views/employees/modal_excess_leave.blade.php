<div class="modal fade" id="modal-excess-leave" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Excess Leave Deductions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Leave Date</th>
                        <th>Log</th>
                        <th class="text-right">Hours Deductible</th>
                    </thead>
                    <tbody>
                        @foreach ($leaveBalanceExcess as $item)
                            <tr>
                                <td>{{ date('F d, Y (D)', strtotime($item->LeaveDate)) }}</td>
                                <td>{{ $item->Notes }}</td>
                                <td class="text-right">{{ $item->HoursAbsent != null && $item->HoursAbsent > 0 ? round($item->HoursAbsent / 60, 2) : 0 }}</td>
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