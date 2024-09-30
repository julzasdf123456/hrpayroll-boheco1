<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="posts-table">
            <thead>
            <tr>
                <th>Postcontent</th>
                <th>Postuserid</th>
                <th>Priority</th>
                <th>Repostoriginaluserid</th>
                <th>Posttype</th>
                <th>Repostoriginalpostid</th>
                <th>Privacy</th>
                <th>Postrawtext</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->PostContent }}</td>
                    <td>{{ $post->PostUserId }}</td>
                    <td>{{ $post->Priority }}</td>
                    <td>{{ $post->RepostOriginalUserId }}</td>
                    <td>{{ $post->PostType }}</td>
                    <td>{{ $post->RepostOriginalPostId }}</td>
                    <td>{{ $post->Privacy }}</td>
                    <td>{{ $post->PostRawText }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['posts.destroy', $post->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('posts.show', [$post->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('posts.edit', [$post->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $posts])
        </div>
    </div>
</div>
