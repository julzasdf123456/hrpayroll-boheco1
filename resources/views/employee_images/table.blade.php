<div class="table-responsive">
    <table class="table" id="employeeImages-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Image</th>
        <th>Description</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($employeeImages as $employeeImages)
            <tr>
                <td>{{ $employeeImages->EmployeeId }}</td>
            <td>{{ $employeeImages->Image }}</td>
            <td>{{ $employeeImages->Description }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['employeeImages.destroy', $employeeImages->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('employeeImages.show', [$employeeImages->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('employeeImages.edit', [$employeeImages->id]) }}" class='btn btn-default btn-xs'>
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
