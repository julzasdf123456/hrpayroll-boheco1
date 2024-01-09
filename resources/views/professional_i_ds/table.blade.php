<div class="table-responsive">
    <table class="table" id="professionalIDs-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Entity</th>
        <th>Entityid</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($professionalIDs as $professionalIDs)
            <tr>
                <td>{{ $professionalIDs->EmployeeId }}</td>
            <td>{{ $professionalIDs->Entity }}</td>
            <td>{{ $professionalIDs->EntityId }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['professionalIDs.destroy', $professionalIDs->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('professionalIDs.show', [$professionalIDs->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('professionalIDs.edit', [$professionalIDs->id]) }}" class='btn btn-default btn-xs'>
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
