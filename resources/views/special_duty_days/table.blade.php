<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="special-duty-days-table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Notes</th>
                <th>Duration</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($specialDutyDays as $specialDutyDay)
                <tr>
                    <td>{{ \Carbon\Carbon::parse(str_replace(':AM',' AM', str_replace(':PM',' PM', $specialDutyDay->Date)))->format('M d, Y') }}</td>
                    <td>{{ $specialDutyDay->Notes }}</td>
                    <td>{{ $specialDutyDay->Term }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['specialDutyDays.destroy', $specialDutyDay->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('specialDutyDays.show', [$specialDutyDay->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('specialDutyDays.edit', [$specialDutyDay->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $specialDutyDays])
        </div>
    </div>
</div>
