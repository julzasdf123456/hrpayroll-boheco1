<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="memorandums-table">
            <thead>
            <tr>
                <th>Memonumber</th>
                <th>Memotitle</th>
                <th>Memocontent</th>
                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($memorandums as $memorandums)
                <tr>
                    <td>{{ $memorandums->MemoNumber }}</td>
                    <td>{{ $memorandums->MemoTitle }}</td>
                    <td>{{ $memorandums->MemoContent }}</td>
                    <td>{{ $memorandums->Status }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['memorandums.destroy', $memorandums->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('memorandums.show', [$memorandums->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('memorandums.edit', [$memorandums->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $memorandums])
        </div>
    </div>
</div>
