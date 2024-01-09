<div class="table-responsive">
    <table class="table" id="leaveSignatoriesRepositories-table">
        <thead>
            <tr>
                <th>Name</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($leaveSignatoriesRepositories as $leaveSignatoriesRepository)
            <tr>
                <td>{{ $leaveSignatoriesRepository->name }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['leaveSignatoriesRepositories.destroy', $leaveSignatoriesRepository->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('leaveSignatoriesRepositories.show', [$leaveSignatoriesRepository->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('leaveSignatoriesRepositories.edit', [$leaveSignatoriesRepository->id]) }}" class='btn btn-default btn-xs'>
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
