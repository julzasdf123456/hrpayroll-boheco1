<div class="table-responsive">
    <table class="table" id="rankingRepositories-table">
        <thead>
            <tr>
                <th>Type</th>
        <th>Rankingname</th>
        <th>Description</th>
        <th>Points</th>
        <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rankingRepositories as $rankingRepository)
            <tr>
                <td>{{ $rankingRepository->Type }}</td>
            <td>{{ $rankingRepository->RankingName }}</td>
            <td>{{ $rankingRepository->Description }}</td>
            <td>{{ $rankingRepository->Points }}</td>
            <td>{{ $rankingRepository->Notes }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['rankingRepositories.destroy', $rankingRepository->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('rankingRepositories.show', [$rankingRepository->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('rankingRepositories.edit', [$rankingRepository->id]) }}" class='btn btn-default btn-xs'>
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
