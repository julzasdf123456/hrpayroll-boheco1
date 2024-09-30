<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="post-comments-table">
            <thead>
            <tr>
                <th>Commenteruserid</th>
                <th>Postid</th>
                <th>Comment</th>
                <th>Type</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($postComments as $postComments)
                <tr>
                    <td>{{ $postComments->CommenterUserId }}</td>
                    <td>{{ $postComments->PostId }}</td>
                    <td>{{ $postComments->Comment }}</td>
                    <td>{{ $postComments->Type }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['postComments.destroy', $postComments->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('postComments.show', [$postComments->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('postComments.edit', [$postComments->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $postComments])
        </div>
    </div>
</div>
