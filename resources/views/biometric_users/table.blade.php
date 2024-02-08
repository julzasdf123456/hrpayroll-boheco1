<div class="table-responsive">
    <table class="table table-sm table-hover" id="biometricUsers-table">
        <thead>
            <tr>
                <th>UID</th>
                <th>Name</th>
                <th>Biometrics User ID</th>
                {{-- <th colspan="3">Action</th> --}}
            </tr>
        </thead>
        <tbody>
        @foreach($biometricUsers as $biometricUser)
            <tr>
                <td>{{ $biometricUser->UID }}</td>
                <td>{{ $biometricUser->Name }}</td>
                <td>{{ $biometricUser->UserId }}</td>
                {{-- <td width="120">
                    {!! Form::open(['route' => ['biometricUsers.destroy', $biometricUser->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('biometricUsers.show', [$biometricUser->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('biometricUsers.edit', [$biometricUser->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td> --}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
