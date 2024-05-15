<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="messages-table">
            <thead>
            <tr>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Message</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($messages as $messages)
                <tr>
                    <td>{{ $messages->Sender }}</td>
                    <td>{{ $messages->Receiver }}</td>
                    <td>{{ $messages->Message }}</td>
                    <td>{{ $messages->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['messages.destroy', $messages->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('messages.show', [$messages->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('messages.edit', [$messages->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $messages])
        </div>
    </div>
</div>
