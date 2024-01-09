<div class="table-responsive">
    <table class="table" id="educationalAttainments-table">
        <thead>
            <tr>
                <th>Employeeid</th>
        <th>Type</th>
        <th>Major</th>
        <th>School</th>
        <th>Schoolyear</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($educationalAttainments as $educationalAttainment)
            <tr>
                <td>{{ $educationalAttainment->EmployeeId }}</td>
            <td>{{ $educationalAttainment->Type }}</td>
            <td>{{ $educationalAttainment->Major }}</td>
            <td>{{ $educationalAttainment->School }}</td>
            <td>{{ $educationalAttainment->SchoolYear }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['educationalAttainments.destroy', $educationalAttainment->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('educationalAttainments.show', [$educationalAttainment->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('educationalAttainments.edit', [$educationalAttainment->id]) }}" class='btn btn-default btn-xs'>
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
