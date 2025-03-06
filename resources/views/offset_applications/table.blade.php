@php
    use Carbon\Carbon;
    use App\Models\Users;
@endphp
<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="offset-applications-table">
            <thead>
            <tr>
                <th>Prepared By</th>
                <th>Date Prepared</th>
                <th>Employee Id</th>
                <th>Date Of Duty</th>
                <th>Time Start</th>
                <th>Time End</th>
                <th>Purpose of Duty</th>
                <th>Date Of Offset</th>
                <th>Offset Reason</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offsetApplications as $offsetApplication)
                <tr>
                    <td>{{ Users::find($offsetApplication->PreparedBy)->name }}</td>
                    <td>{{  Carbon::parse(str_replace(':AM',' AM', str_replace(':PM',' PM', $offsetApplication->DatePrepared)))->format('M d, Y') }}</td>
                    <td>{{ $offsetApplication->EmployeeId }}</td>
                    <td>{{ Carbon::parse(str_replace(':AM',' AM', str_replace(':PM',' PM', $offsetApplication->DateOfDuty)))->format('M d, Y') }}</td>
                    <td>{{ $offsetApplication->TimeStart }}</td>
                    <td>{{ $offsetApplication->TimeEnd }}</td>
                    <td>{{ $offsetApplication->PurposeOfDuty }}</td>
                    <td>{{ Carbon::parse(str_replace(':AM',' AM', str_replace(':PM',' PM', $offsetApplication->DateOfOffset)))->format('M d, Y') }}</td>
                    <td>{{ $offsetApplication->OffsetReason }}</td>
                    <td>{{ $offsetApplication->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['offsetApplications.destroy', $offsetApplication->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('offsetApplications.show', [$offsetApplication->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            @if ($offsetApplication->Status != 'APPROVED')
                                <a href="{{ route('offsetApplications.edit', [$offsetApplication->id]) }}"
                                    class='btn btn-default btn-xs'>
                                    <i class="far fa-edit"></i>
                                </a>
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            @endif
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
            @include('adminlte-templates::common.paginate', ['records' => $offsetApplications])
        </div>
    </div>
</div>
