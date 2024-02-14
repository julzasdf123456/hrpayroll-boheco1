<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="bempcs-table">
            <thead>
            <tr>
                <th>Deduction</th>
                <th>Schedule</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bempcs as $bempc)
                <tr>
                    <td>{{ $bempc->DeductionFor }}</td>
                    <td>{{ $bempc->DeductionSchedule }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
