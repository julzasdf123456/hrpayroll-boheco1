<div class="table-responsive">
    <table class="table" id="biometricUsers-table">
        <thead>
            <tr>
                <th>Uid</th>
        <th>Name</th>
        <th>Userid</th>
        <th>Role</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($biometricUsers as $biometricUsers)
            <tr>
                <td>{{ $biometricUsers->UID }}</td>
            <td>{{ $biometricUsers->Name }}</td>
            <td>{{ $biometricUsers->UserId }}</td>
            <td>{{ $biometricUsers->Role }}</td>
            <td>{{ $biometricUsers->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['biometricUsers.destroy', $biometricUsers->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('biometricUsers.show', [$biometricUsers->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('biometricUsers.edit', [$biometricUsers->id]) }}" class='btn btn-default btn-xs'>
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
