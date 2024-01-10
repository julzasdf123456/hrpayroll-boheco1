<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="biometric-devices-table">
            <thead>
            <tr>
                <th>Ipaddress</th>
                <th>Brand</th>
                <th>Office</th>
                <th>Status</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($biometricDevices as $biometricDevices)
                <tr>
                    <td>{{ $biometricDevices->IPAddress }}</td>
                    <td>{{ $biometricDevices->Brand }}</td>
                    <td>{{ $biometricDevices->Office }}</td>
                    <td>{{ $biometricDevices->Status }}</td>
                    <td>{{ $biometricDevices->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['biometricDevices.destroy', $biometricDevices->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('biometricDevices.show', [$biometricDevices->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('biometricDevices.edit', [$biometricDevices->id]) }}"
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

</div>
