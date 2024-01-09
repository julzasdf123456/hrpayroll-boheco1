<div class="table-responsive">
    <table class="table" id="employees-table">
        <thead>
            <tr>
                <th>Firstname</th>
        <th>Middlename</th>
        <th>Lastname</th>
        <th>Suffix</th>
        <th>Gender</th>
        <th>Birthdate</th>
        <th>Streetcurrent</th>
        <th>Barangaycurrent</th>
        <th>Towncurrent</th>
        <th>Provincecurrent</th>
        <th>Streetpermanent</th>
        <th>Barangaypermanent</th>
        <th>Townpermanent</th>
        <th>Provincepermanent</th>
        <th>Contactnumbers</th>
        <th>Emailaddress</th>
        <th>Bloodtype</th>
        <th>Civilstatus</th>
        <th>Religion</th>
        <th>Citizenship</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($employees as $employees)
            <tr>
                <td>{{ $employees->FirstName }}</td>
            <td>{{ $employees->MiddleName }}</td>
            <td>{{ $employees->LastName }}</td>
            <td>{{ $employees->Suffix }}</td>
            <td>{{ $employees->Gender }}</td>
            <td>{{ $employees->Birthdate }}</td>
            <td>{{ $employees->StreetCurrent }}</td>
            <td>{{ $employees->BarangayCurrent }}</td>
            <td>{{ $employees->TownCurrent }}</td>
            <td>{{ $employees->ProvinceCurrent }}</td>
            <td>{{ $employees->StreetPermanent }}</td>
            <td>{{ $employees->BarangayPermanent }}</td>
            <td>{{ $employees->TownPermanent }}</td>
            <td>{{ $employees->ProvincePermanent }}</td>
            <td>{{ $employees->ContactNumbers }}</td>
            <td>{{ $employees->EmailAddress }}</td>
            <td>{{ $employees->BloodType }}</td>
            <td>{{ $employees->CivilStatus }}</td>
            <td>{{ $employees->Religion }}</td>
            <td>{{ $employees->Citizenship }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['employees.destroy', $employees->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('employees.show', [$employees->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('employees.edit', [$employees->id]) }}" class='btn btn-default btn-xs'>
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
