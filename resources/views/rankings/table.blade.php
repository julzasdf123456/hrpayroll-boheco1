<div class="table-responsive">
    <table class="table" id="rankings-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Rankingrepositoryid</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rankings as $rankings)
            <tr>
                <td>{{ $rankings->EmployeeId }}</td>
            <td>{{ $rankings->RankingRepositoryId }}</td>
            <td>{{ $rankings->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['rankings.destroy', $rankings->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('rankings.show', [$rankings->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('rankings.edit', [$rankings->id]) }}" class='btn btn-default btn-xs'>
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
