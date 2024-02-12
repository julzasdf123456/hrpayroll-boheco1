<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="user-footprints-table">
            <thead>
            <tr>
                <th>Userid</th>
                <th>Logname</th>
                <th>Logdetails</th>
                <th>Computername</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userFootprints as $userFootprints)
                <tr>
                    <td>{{ $userFootprints->UserId }}</td>
                    <td>{{ $userFootprints->LogName }}</td>
                    <td>{{ $userFootprints->LogDetails }}</td>
                    <td>{{ $userFootprints->ComputerName }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['userFootprints.destroy', $userFootprints->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('userFootprints.show', [$userFootprints->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('userFootprints.edit', [$userFootprints->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $userFootprints])
        </div>
    </div>
</div>
