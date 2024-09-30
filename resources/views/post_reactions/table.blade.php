<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="post-reactions-table">
            <thead>
            <tr>
                <th>Postid</th>
                <th>Userid</th>
                <th>Reactiontype</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($postReactions as $postReactions)
                <tr>
                    <td>{{ $postReactions->PostId }}</td>
                    <td>{{ $postReactions->UserId }}</td>
                    <td>{{ $postReactions->ReactionType }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['postReactions.destroy', $postReactions->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('postReactions.show', [$postReactions->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('postReactions.edit', [$postReactions->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $postReactions])
        </div>
    </div>
</div>
