@php
    use App\Models\Positions;
    use Illuminate\Support\Facades\DB;
@endphp

<!-- Designation Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Designation', 'Designation:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                </div>
                <select class="custom-select select2"  name="PositionId" id='PositionId'>
                    @foreach ($departments as $department)
                        <optgroup label="{{ $department->Department }}">
                            @php
                                // $positions = Positions::where('Department', $department->Department)->orderBy('Position')->get();
                                $positions = DB::table('Positions')
                                    ->where('Positions.Department', $department->Department)
                                    ->select(
                                        'Positions.*',
                                        DB::raw("(SELECT TOP 1 e.FirstName + ' ' +  e.LastName  FROM EmployeesDesignations ed LEFT JOIN Employees e ON ed.EmployeeId=e.id WHERE ed.PositionId=Positions.id AND (e.EmploymentStatus IS NULL OR e.EmploymentStatus NOT IN('Retired','Resigned')) ORDER BY ed.created_at DESC) AS EmployeeName")
                                    )
                                    ->orderBy('Position')
                                    ->get();
                            @endphp
                            @foreach ($positions as $item)
                                <option value="{{ $item->id }}" {{ $employeesDesignations != null && $employeesDesignations->PositionId==$item->id ? 'selected' : '' }}><b>{{ $item->Position }} ({{ $item->BasicSalary != null ? number_format($item->BasicSalary, 2) : '-' }})</b> <span style="color: grey !important;">{{ $item->EmployeeName != null ? '- ' . $item->EmployeeName : '' }}</span></option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
    </div> 
</div>

{{-- STATUS --}}
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Status', 'Status:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                </div>
                {!! Form::select('Status', ['Contractual' => 'Contractual', 'Probationary' => 'Probationary', 'Regular' => 'Regular'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Description Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Description', 'Remarks/Notes:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                </div>
                {!! Form::textarea('Description', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'rows' => 2]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Datestarted Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('DateStarted', 'Date Hired:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                </div>
                {!! Form::text('DateStarted', null, ['class' => 'form-control','id'=>'DateStarted']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateStarted').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dateend Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('DateEnd', 'Date End (Contractual):') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                </div>
                {!! Form::text('DateEnd', null, ['class' => 'form-control','id'=>'DateEnd']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateEnd').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Salaryamount Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('SalaryAmount', 'Salary:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                </div>
                {!! Form::number('SalaryAmount', null, ['class' => 'form-control','readonly' => true,'maxlength' => 255, 'step' => 'any']) !!}
            </div>
        </div>
    </div> 
</div>


<!-- Salaryaddons Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('SalaryAddOns', 'Salary Add-ons:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                </div>
                {!! Form::number('SalaryAddOns', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'step' => 'any']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script>
        function getSalary() {
            $('#SalaryAmount').val($('#SalaryPerLoad').val() * $('#SubjectLoad').val());
        }
        $(document).ready(function() {
            $('#SalaryAmount').val($('#PositionId option:selected').attr('salary'))

            $('#SalaryPerLoad').keyup(function() {
                getSalary();
            })

            $('#SubjectLoad').keyup(function() {
                getSalary();
            })

            $('#PositionId').on('change', function() {
                $('#SalaryAmount').val($('#PositionId option:selected').attr('salary'))
            })
        })
    </script>
@endpush