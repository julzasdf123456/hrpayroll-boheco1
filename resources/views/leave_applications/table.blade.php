@php
    use App\Models\LeaveDays;
    use App\Models\Employees;
    use Carbon\Carbon;

@endphp

<div class="table-responsive">
    <table class="table" id="leaveApplications-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Date Filed</th>
                <th>Leave Dates</th>
                {{-- <th>Leave End</th> --}}
                {{-- <th>Number of Days</th> --}}
                <th>Content</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveApplications as $leaveApplication)
            @php
                $leaveDays = LeaveDays::where('LeaveId', $leaveApplication->id)->get();
            @endphp
            <tr>
                <td>{{ $leaveApplication->LastName . ", " .$leaveApplication->FirstName . " " . $leaveApplication->Suffix . " " .$leaveApplication->MiddleName}}</td>
                {{-- <td>{{ $leaveApplication->EmployeeId }}</td> --}}
                <td>{{ Carbon::parse(str_replace(":AM"," AM",$leaveApplication->created_at))->format("Y-M-d") }}</td>
                <td>
                    @if (count($leaveDays) <= 1)
                        {{ Carbon::parse(str_replace(":AM"," AM",$leaveDays[0]->LeaveDate))->format("Y-M-d") }}
                    @else
                        {{ Carbon::parse(str_replace(":AM"," AM",$leaveDays[0]->LeaveDate))->format("Y-M-d") }}<br/>To<br/>{{ Carbon::parse(str_replace(":AM"," AM",$leaveDays[count($leaveDays)-1]->LeaveDate))->format("Y-M-d") }}                    
                    @endif
                </td>
                {{-- <td>{{ Carbon::parse(str_replace(":AM"," AM",$leaveDays[0]->LeaveDate))->format("Y-m-d") }}</td> --}}
                {{-- <td>{{ Carbon::parse(str_replace(":AM"," AM",$leaveDays[count($leaveDays)-1]->LeaveDate))->format("Y-m-d") }}</td> --}}
                {{-- <td>{{ count($leaveDays) }}</td> --}}
                <td>{{ $leaveApplication->Content }}</td>
                <td>{{ $leaveApplication->Status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveApplications.destroy', $leaveApplication->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveApplications.show', [$leaveApplication->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        {{-- @if ($leaveApplications->Status != "APPROVED") --}}
                            <a href="{{ route('leaveApplications.edit', [$leaveApplication->id]) }}" class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        {{-- @endif --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
