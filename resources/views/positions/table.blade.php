@php
    use App\Models\Positions;
    use Illuminate\Support\Facades\DB;
@endphp

<div class="table-responsive">
    <table class="table table-hover table-sm" id="positions-table">
        <thead>
            <tr>
                <th>Position</th>
                <th>Level</th>
                <th>PSA Category</th>
                <th>Immediate Super</th>
                <th>Basic Salary</th>
                <th>Department</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($positions as $position)
            @php
                // GET EMPLOYEE FOR POSITION
                $employee = DB::table('EmployeesDesignations')
                    ->leftJoin('Employees', 'EmployeesDesignations.EmployeeId', '=', 'Employees.id')
                    ->where('EmployeesDesignations.PositionId', $position->id)
                    ->whereRaw("(Employees.EmploymentStatus IS NULL OR Employees.EmploymentStatus NOT IN ('Retired', 'Resigned'))")
                    ->select(
                        'Employees.FirstName',
                        'Employees.LastName',
                    )
                    ->orderByDesc('EmployeesDesignations.created_at')
                    ->first();
            @endphp
            <tr>
                <td class="v-align">
                    <strong>{{ $position->Position }}</strong>
                    @if ($employee != null)
                        <br>
                        <span class="text-muted">{{ $employee->FirstName . ' ' . $employee->LastName }}</span>
                    @endif
                </td>
                <td class="v-align">
                    <select class="custom-select select2" id="Level-{{ $position->id }}" onchange="changeLevel(`{{ $position->id }}`)">
                        <option value="">-- Select --</option>
                        <option value='General Manager' {{ $position->Level=='General Manager' ? 'selected' : '' }}>General Manager</option> 
                        <option value='Manager' {{ $position->Level=='Manager' ? 'selected' : '' }}>Manager</option> 
                        <option value='Chief' {{ $position->Level=='Chief' ? 'selected' : '' }}>Chief</option> 
                        <option value='Supervisor' {{ $position->Level=='Supervisor' ? 'selected' : '' }}>Supervisor</option> 
                        <option value='Officer' {{ $position->Level=='Officer' ? 'selected' : '' }}>Officer</option> 
                        <option value='Clerk' {{ $position->Level=='Clerk' ? 'selected' : '' }}>Clerk</option> 
                        <option value='Rank and File' {{ $position->Level=='Rank and File' ? 'selected' : '' }}>Rank and File</option> 
                        <option value='Driver' {{ $position->Level=='Driver' ? 'selected' : '' }}>Driver</option>
                    </select>
                </td>
                <td class="v-align">
                    <select class="custom-select select2" id="PSA-{{ $position->id }}" onchange="changePSA(`{{ $position->id }}`)">
                        <option value="">-- Select --</option>
                        <option value='Managers and Executives' {{ $position->PSACategory=='Managers and Executives' ? 'selected' : '' }}>Managers and Executives</option> 
                        <option value='Production Workers' {{ $position->PSACategory=='Production Workers' ? 'selected' : '' }}>Production Workers</option> 
                        <option value='Other Employees' {{ $position->PSACategory=='Other Employees' ? 'selected' : '' }}>Other Employees</option> 
                    </select>
                </td>
                <td class="v-align">
                    <select class="custom-select select2" id="ParentPosition-{{ $position->id }}" onchange="changePosition(`{{ $position->id }}`)">
                        <option value="">-- Select --</option>
                        @foreach ($supers as $item)
                            <option value="{{ $item->id }}" {{ $position->ParentPositionId == $item->id ? 'selected' : '' }}>{{ $item->Position }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="v-align">
                    <input onkeyup="changeSalary(event, `{{ $position->id }}`)" id="Salary-{{ $position->id }}" type="number" step="any" class="form-control text-right" value="{{ number_format($position->BasicSalary, 2, '.', '') }}">
                </td>
                <td class="v-align">{{ $position->Department }}</td>
                <td class="v-align" width="120">
                    {!! Form::open(['route' => ['positions.destroy', $position->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('positions.show', [$position->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('positions.edit', [$position->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {{-- {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            
        })

        function changePosition(id) {
            var selectedId = $('#ParentPosition-' + id).val()

            $.ajax({
                url : "{{ route('positions.update-super') }}",
                type : 'GET',
                data : {
                    PositionId : id,
                    SuperId : selectedId,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Immediate parent position updated!'
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error updating parent position'
                    })
                }
            })
        }

        function changeLevel(id) {
            var selectedLevel = $('#Level-' + id).val()

            $.ajax({
                url : "{{ route('positions.update-level') }}",
                type : 'GET',
                data : {
                    PositionId : id,
                    Level : selectedLevel,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Level updated!'
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error updating level!'
                    })
                }
            })
        }

        function changeSalary(event, id) {
            if (event.key === 'Enter' || event.keyCode === 13) {
                var salary = $('#Salary-' + id).val()

                $.ajax({
                    url : "{{ route('positions.update-salary') }}",
                    type : 'GET',
                    data : {
                        PositionId : id,
                        BasicSalary : salary,
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Salary updated!'
                        })
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'Error updating Salary!'
                        })
                    }
                })
            }
        }

        function changePSA(id) {
            var selectedPSA = $('#PSA-' + id).val()

            $.ajax({
                url : "{{ route('positions.update-psa-category') }}",
                type : 'GET',
                data : {
                    PositionId : id,
                    PSACategory : selectedPSA,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'PSA Category updated!'
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error updating PSA Category!'
                    })
                }
            })
        }
    </script>
@endpush
