<!-- Morningtimeinstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningTimeInStart', 'Morningtimeinstart:') !!}
    {!! Form::text('MorningTimeInStart', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#MorningTimeInStart').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Morningtimeinend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningTimeInEnd', 'Morningtimeinend:') !!}
    {!! Form::text('MorningTimeInEnd', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#MorningTimeInEnd').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Morningtimeoutstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningTimeOutStart', 'Morningtimeoutstart:') !!}
    {!! Form::text('MorningTimeOutStart', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#MorningTimeOutStart').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Morningtimeoutend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningTimeOutEnd', 'Morningtimeoutend:') !!}
    {!! Form::text('MorningTimeOutEnd', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#MorningTimeOutEnd').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- MorningAbsentThreshold Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningAbsentThreshold', 'Morning Absent Threshold:') !!}
    {!! Form::text('MorningAbsentThreshold', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#MorningAbsentThreshold').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- MorningUndertimeThreshold Field -->
<div class="form-group col-sm-6">
    {!! Form::label('MorningUndertimeThreshold', 'Morning Undertime Threshold:') !!}
    {!! Form::text('MorningUndertimeThreshold', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#MorningUndertimeThreshold').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Afternoontimeinstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonTimeInStart', 'Afternoontimeinstart:') !!}
    {!! Form::text('AfternoonTimeInStart', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonTimeInStart').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush


<!-- Afternoontimeinend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonTimeInEnd', 'Afternoontimeinend:') !!}
    {!! Form::text('AfternoonTimeInEnd', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonTimeInEnd').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Afternoontimeoutstart Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonTimeOutStart', 'Afternoontimeoutstart:') !!}
    {!! Form::text('AfternoonTimeOutStart', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonTimeOutStart').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- Afternoontimeoutend Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonTimeOutEnd', 'Afternoontimeoutend:') !!}
    {!! Form::text('AfternoonTimeOutEnd', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonTimeOutEnd').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- AfternoonAbsentThreshold Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonAbsentThreshold', 'Afternoon Absent Threshold:') !!}
    {!! Form::text('AfternoonAbsentThreshold', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonAbsentThreshold').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush

<!-- AfternoonUndertimeThreshold Field -->
<div class="form-group col-sm-6">
    {!! Form::label('AfternoonUndertimeThreshold', 'Afternoon Undertime Threshold:') !!}
    {!! Form::text('AfternoonUndertimeThreshold', null, ['class' => 'form-control']) !!}
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#AfternoonUndertimeThreshold').datetimepicker({
            format: 'LT',
            useCurrent: false,
            sideBySide: true
        })
    </script>
@endpush