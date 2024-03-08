<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="s-m-s-notifications-table">
            <thead>
            <tr>
                <th>Contactnumber</th>
                <th>Message</th>
                <th>Status</th>
                <th>Source</th>
                <th>Sourceid</th>
                <th>Notes</th>
                <th>Aifacilitator</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sMSNotifications as $sMSNotifications)
                <tr>
                    <td>{{ $sMSNotifications->ContactNumber }}</td>
                    <td>{{ $sMSNotifications->Message }}</td>
                    <td>{{ $sMSNotifications->Status }}</td>
                    <td>{{ $sMSNotifications->Source }}</td>
                    <td>{{ $sMSNotifications->SourceId }}</td>
                    <td>{{ $sMSNotifications->Notes }}</td>
                    <td>{{ $sMSNotifications->AIFacilitator }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['sMSNotifications.destroy', $sMSNotifications->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('sMSNotifications.show', [$sMSNotifications->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('sMSNotifications.edit', [$sMSNotifications->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $sMSNotifications])
        </div>
    </div>
</div>
