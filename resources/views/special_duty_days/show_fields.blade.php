<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('Date', 'Date:') !!}
    <p>{{ \Carbon\Carbon::parse(str_replace(':AM',' AM', str_replace(':PM',' PM', $specialDutyDays->Date)))->format('M d, Y') }}</p>
</div>

<!-- Duration Field -->
<div class="col-sm-12">
    {!! Form::label('Term', 'Duration:') !!}
    <p>{{ $specialDutyDays->Term }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('Notes', 'Notes:') !!}
    <p>{{ $specialDutyDays->Notes }}</p>
</div>

