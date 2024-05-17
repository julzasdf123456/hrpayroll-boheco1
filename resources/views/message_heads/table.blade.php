<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="message-heads-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Latestmessage</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($messageHeads as $messageHeads)
                <tr>
                    <td>{{ $messageHeads->id }}</td>
                    <td>{{ $messageHeads->Sender }}</td>
                    <td>{{ $messageHeads->Receiver }}</td>
                    <td>{{ $messageHeads->LatestMessage }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['messageHeads.destroy', $messageHeads->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('messageHeads.show', [$messageHeads->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('messageHeads.edit', [$messageHeads->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $messageHeads])
        </div>
    </div>
</div>
