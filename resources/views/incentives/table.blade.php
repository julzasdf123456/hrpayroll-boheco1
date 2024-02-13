<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="incentives-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Incentivename</th>
                <th>Notes</th>
                <th>Userid</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($incentives as $incentives)
                <tr>
                    <td>{{ $incentives->id }}</td>
                    <td>{{ $incentives->IncentiveName }}</td>
                    <td>{{ $incentives->Notes }}</td>
                    <td>{{ $incentives->UserId }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['incentives.destroy', $incentives->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('incentives.show', [$incentives->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('incentives.edit', [$incentives->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $incentives])
        </div>
    </div>
</div>
