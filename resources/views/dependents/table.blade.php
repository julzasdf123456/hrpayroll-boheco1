<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="dependents-table">
            <thead>
            <tr>
                <th>Employeeid</th>
                <th>Dependentname</th>
                <th>Address</th>
                <th>Relationship</th>
                <th>Birthdate</th>
                <th>Isbeneficiary</th>
                <th>Occupation</th>
                <th>Disability</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dependents as $dependents)
                <tr>
                    <td>{{ $dependents->EmployeeId }}</td>
                    <td>{{ $dependents->DependentName }}</td>
                    <td>{{ $dependents->Address }}</td>
                    <td>{{ $dependents->Relationship }}</td>
                    <td>{{ $dependents->Birthdate }}</td>
                    <td>{{ $dependents->IsBeneficiary }}</td>
                    <td>{{ $dependents->Occupation }}</td>
                    <td>{{ $dependents->Disability }}</td>
                    <td>{{ $dependents->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['dependents.destroy', $dependents->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('dependents.show', [$dependents->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('dependents.edit', [$dependents->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $dependents])
        </div>
    </div>
</div>
