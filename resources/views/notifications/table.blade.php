<div class="table-responsive">
    <table class="table" id="notifications-table">
        <thead>
            <tr>
                <th>Content</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($notifications as $notifications)
            <tr>
                <td>{{ $notifications->Content }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['notifications.destroy', $notifications->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('notifications.show', [$notifications->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('notifications.edit', [$notifications->id]) }}" class='btn btn-default btn-xs'>
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
