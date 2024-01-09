@php
    use App\Models\Employees;
@endphp

@canany('employees update')
<a class="btn btn-link text-info float-right" href="{{ route('employees.update-educational-attainment', [$employees->id]) }}" title="Update educational attainment data for {{ Employees::getMergeName($employees) }}"><i class="fas fa-edit"></i></a>
@endcanany

@if (count($educationalAttainment) > 0)
    <table class="table table-borderless table-hover">
        <thead>
            <th>Level</th>
            <th>Major/Course/Description</th>
            <th>School</th>
            <th>School Year</th>
            <th>Certifications</th>
        </thead>
        <tbody>
            @foreach ($educationalAttainment as $item)
                <tr>
                    <td>{{ $item->Type }}</td>
                    <td>{{ $item->Major }}</td>
                    <td>{{ $item->School }}</td>
                    <td>{{ $item->SchoolYear }}</td>
                    <td>
                        @if ($item->Certification != null)
                            <a href="{{ route('employees.download-file', [trim($employees->id), $item->Type, $item->Certification]) }}" target="_blank">{{ $item->Certification }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@else
    <p class="text-center"><i>No Educational Background Recorded</i></p>
@endif