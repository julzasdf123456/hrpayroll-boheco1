@php
    use App\Models\LeaveBalances;
@endphp

<!-- Datefrom Field -->
{{-- <div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('DateFrom', 'From:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                </div>
                {!! Form::text('DateFrom', null, ['class' => 'form-control','id'=>'DateFrom']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateFrom').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Dateto Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('DateTo', 'To:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>
                </div>
                {!! Form::text('DateTo', null, ['class' => 'form-control','id'=>'DateTo']) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#DateTo').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush --}}

<!-- LeaveType Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('LeaveType', 'Leave Type:') !!} <span class="text-danger"><strong> *</strong></span>
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div>
                    <div class="form-check">
                        <input class="custom-radio" type="radio" name="LeaveType" id="Vacation" value="Vacation" required>
                        <label class="custom-radio-label" for="Vacation">Vacation <strong class="text-muted">({{ $leaveBalance != null ? LeaveBalances::toExpanded($leaveBalance->Vacation) : 0 }} available)</strong></label>
                    </div>
                    <div class="form-check">
                        <input class="custom-radio" type="radio" name="LeaveType" id="Sick" value="Sick" required>
                        <label class="custom-radio-label" for="Sick">Sick <strong class="text-muted">({{ $leaveBalance != null ? LeaveBalances::toExpanded($leaveBalance->Sick) : 0 }} available)</strong></label>
                    </div>
                    <div class="form-check">
                        <input class="custom-radio" type="radio" name="LeaveType" id="Special" value="Special" required>
                        <label class="custom-radio-label" for="Special">Special <strong class="text-muted">({{ $leaveBalance != null ? $leaveBalance->Special : 0 }} days available)</strong></label>
                    </div>
                    @if ($employee->Gender == 'Male')
                        <div class="form-check">
                            <input class="custom-radio" type="radio" name="LeaveType" id="Paternity" value="Paternity" required>
                            <label class="custom-radio-label" for="Paternity">Paternity <strong class="text-muted">({{ $leaveBalance != null ? $leaveBalance->Paternity : 0 }} days available)</strong></label>
                        </div>
                    @else
                        <div class="form-check">
                            <input class="custom-radio" type="radio" name="LeaveType" id="Maternity" value="Maternity" required>
                            <label class="custom-radio-label" for="Maternity">Maternity <strong class="text-muted">({{ $leaveBalance != null ? $leaveBalance->Maternity : 0 }} days available)</strong></label>
                        </div>
                        <div class="form-check">
                            <input class="custom-radio" type="radio" name="LeaveType" id="MaternityForSoloMother" value="MaternityForSoloMother" required>
                            <label class="custom-radio-label" for="MaternityForSoloMother">Maternity For Solo Mother <strong class="text-muted">({{ $leaveBalance != null ? $leaveBalance->MaternityForSoloMother : 0 }} days available)</strong></label>
                        </div>
                    @endif
                    <div class="form-check">
                        <input class="custom-radio" type="radio" name="LeaveType" id="SoloParent" value="SoloParent" required>
                        <label class="custom-radio-label" for="SoloParent">Solo Parent <strong class="text-muted">({{ $leaveBalance != null ? $leaveBalance->SoloParent : 0 }} days available)</strong></label>
                    </div>
                </div>   
            </div>
        </div>
    </div>  
</div>

<!-- FOR SICK LEAVE Field -->
<div class="form-group col-sm-12" id="special-dropdown">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            <label for="">Select Reason</label>
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <select name="SpecialReason" id="SpecialReason" class="form-control">
                    <option value="Enrollment">Enrollment</option>
                    <option value="Graduation">Graduation</option>
                    <option value="Birthday">Birthday</option>
                    <option value="Medical Examination">Medical Examination</option>
                    <option value="Wedding Anniversary">Wedding Anniversary</option>
                    <option value="Fiesta">Fiesta</option>
                </select>
            </div>
        </div>
    </div> 
</div>

<!-- Content Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Content', 'Reason/Purpose:') !!} <span class="text-danger"><strong> *</strong></span>
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                {!! Form::textarea('Content', null, ['class' => 'form-control','maxlength' => 2000,'maxlength' => 2000, 'rows' => 4, 'required' => true, 'readonly' => true]) !!}
            </div>
        </div>
    </div> 
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#special-dropdown').hide()

            $('input[type=radio][name=LeaveType]').change(function() {
                var value = this.value

                if (value == 'Special') {
                    $('#special-dropdown').show()
                    $('#Content').attr('readonly', true)
                    $('#Content').val($('#SpecialReason').val())
                } else if (value == 'Sick') {
                    $('#special-dropdown').hide()
                    $('#Content').removeAttr('readonly')
                    $('#Content').val(null)
                } else if (value == 'Paternity' | value == 'Maternity') {
                    $('#special-dropdown').hide()   
                    $('#Content').val(null)   
                    $('#Content').attr('readonly', true)
                } else {           
                    $('#special-dropdown').hide()   
                    $('#Content').val(null)  
                    $('#Content').removeAttr('readonly')
                }
            })

            $('#SpecialReason').on('change', function() {
                $('#Content').val($(this).val())
            })
        })
    </script>
@endpush
