<!-- Position Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Position', 'Position:') !!}
    {!! Form::text('Position', null, ['class' => 'form-control','maxlength' => 450,'maxlength' => 450]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Description', 'Description:') !!}
    {!! Form::text('Description', null, ['class' => 'form-control','maxlength' => 600,'maxlength' => 600]) !!}
</div>

<!-- Level Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Level', 'Level:') !!}
    {!! Form::select('Level', ['Managerial' => 'Managerial', 'Chief' => 'Chief', 'Supervisor' => 'Supervisor', 'Officer' => 'Officer', 'Clerk' => 'Clerk', 'Employee' => 'Employee'], null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Parentpositionid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ParentPositionId', 'Parent Position:') !!}
    <select name="ParentPositionId" id="ParentPositionId" class="form-control">
        <option value="">-</option>
        @foreach ($position as $item)
            <option value="{{ $item->id }}">{{ $item->Position }}</option>
        @endforeach
    </select>
</div>

<!-- Department Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Department', 'Department:') !!}
    {!! Form::select('Department', ['ESD' => 'ESD', 'ISD' => 'ISD', 'OGM' => 'OGM', 'OSD' => 'OSD', 'PGD' => 'PGD', 'SEEAD' => 'SEEAD'], null, ['class' => 'form-control', 'step' => 'any']) !!}
</div>

<!-- BasicSalary Field -->
<div class="form-group col-sm-6">
    {!! Form::label('BasicSalary', 'Basic Salary:') !!}
    {!! Form::number('BasicSalary', null, ['class' => 'form-control', 'step' => 'any']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('Notes', 'Notes:') !!}
    {!! Form::text('Notes', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>