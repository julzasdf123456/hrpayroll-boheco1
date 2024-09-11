<p><i>Basic Information</i></p>
<!-- Firstname Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('FirstName', 'Firstname:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                </div>
                {!! Form::text('FirstName', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Middlename Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('MiddleName', 'Middlename:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                </div>
                {!! Form::text('MiddleName', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Lastname Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('LastName', 'Lastname:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                </div>
                {!! Form::text('LastName', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Suffix Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Suffix', 'Suffix:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                </div>
                {!! Form::select('Suffix', ['' => '', 'Jr.' => 'Jr.', 'Sr.' => 'Sr.', 'III' => 'III', 'IV' => 'IV', 'V' => 'V'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Gender Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Gender', 'Gender:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                </div>
                {!! Form::select('Gender', ['' => 'Prefer not to state', 'Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Birthdate Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Birthdate', 'Birthdate:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                </div>
                {!! Form::text('Birthdate', null, ['class' => 'form-control', 'id' => 'Birthdate']) !!}
            </div>
        </div>
    </div> 
</div>
@push('page_scripts')
    <script type="text/javascript">
        $('#Birthdate').datetimepicker({
            format: 'YYYY-MM-DD',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<div class="divider"></div>

<p><i>Present Address</i></p>
<!-- Provincecurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ProvinceCurrent', 'Province:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::text('ProvinceCurrent', null, ['class' => 'form-control','maxlength' => 400,'maxlength' => 400]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Towncurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TownCurrent', 'Town:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('TownCurrent', $towns, null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Barangaycurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('BarangayCurrent', 'Barangay:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('BarangayCurrent', [], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Streetcurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('StreetCurrent', 'Street/Purok:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::text('StreetCurrent', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>

<p><i>Permanent Address</i></p>
<!-- Provincecurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ProvincePermanent', 'Province:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::text('ProvincePermanent', null, ['class' => 'form-control','maxlength' => 400,'maxlength' => 400]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Townpermanent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TownPermanent', 'Town:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('TownPermanent', $towns, null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Barangaycurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('BarangayPermanent', 'Barangay:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::select('BarangayPermanent', [], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Streetcurrent Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('StreetPermanent', 'Street/Purok:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                </div>
                {!! Form::text('StreetPermanent', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>

<p><i>Others</i></p>

<!-- Contactnumbers Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('ContactNumbers', 'Contact Numbers:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                {!! Form::text('ContactNumbers', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Emailaddress Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('EmailAddress', 'Email Address:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                {!! Form::text('EmailAddress', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Bloodtype Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('BloodType', 'Bloodtype:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-pump-medical"></i></span>
                </div>
                {!! Form::select('BloodType', ['A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Civilstatus Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('CivilStatus', 'Civil Status:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-ring"></i></span>
                </div>
                {!! Form::select('CivilStatus', ['Single' => 'Single', 'Married' => 'Married', 'Divorced' => 'Divorced', 'Widowed' => 'Widowed', 'Its Complicated' => 'Its Complicated'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Religion Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Religion', 'Religion:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-cross"></i></span>
                </div>
                {!! Form::text('Religion', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Citizenship Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Citizenship', 'Citizenship:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
                </div>
                {!! Form::text('Citizenship', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>

<p><i>ID and Bank Numbers</i></p>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('TIN', 'TIN:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                {!! Form::text('TIN', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('SSSNumber', 'SSS:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                {!! Form::text('SSSNumber', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('PagIbigNumber', 'Pag-Ibig:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                {!! Form::text('PagIbigNumber', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('PhilHealthNumber', 'PhilHealth:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                {!! Form::text('PhilHealthNumber', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('PrimaryBankNumber', 'Pitakard Number:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                {!! Form::text('PrimaryBankNumber', null, ['class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
            </div>
        </div>
    </div> 
</div>

<div class="divider"></div>

<p><i>Leave Profile</i></p>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Mother', 'Is Employee a Mother?') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group-radio">
                <input type="radio" id="MotherYes" name="Mother" value="Yes" class="custom-radio" {{ isset($employees) && $employees->Mother==='Yes' ? 'checked' : '' }}>
                <label for="MotherYes" class="custom-radio-label">Yes</label>

                <input type="radio" id="MotherNo" name="Mother" value="" class="custom-radio" {{ isset($employees) && $employees->Mother==='Yes' ? '' : 'checked' }}>
                <label for="MotherNo" class="custom-radio-label">No</label>
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('SoloMother', 'Is Employee a Solo Mother?') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group-radio">
                <input type="radio" id="SoloMotherYes" name="SoloMother" value="Yes" class="custom-radio" {{ isset($employees) && $employees->SoloMother==='Yes' ? 'checked' : '' }}>
                <label for="SoloMotherYes" class="custom-radio-label">Yes</label>

                <input type="radio" id="SoloMotherNo" name="SoloMother" value="" class="custom-radio" {{ isset($employees) && $employees->SoloMother==='Yes' ? '' : 'checked' }}>
                <label for="SoloMotherNo" class="custom-radio-label">No</label>
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('SoloParent', 'Is Employee also a Solo Parent?') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group-radio">
                <input type="radio" id="SoloParentYes" name="SoloParent" value="Yes" class="custom-radio" {{ isset($employees) && $employees->SoloParent==='Yes' ? 'checked' : '' }}>
                <label for="SoloParentYes" class="custom-radio-label">Yes</label>

                <input type="radio" id="SoloParentNo" name="SoloParent" value="" class="custom-radio" {{ isset($employees) && $employees->SoloParent==='Yes' ? '' : 'checked' }}>
                <label for="SoloParentNo" class="custom-radio-label">No</label>
            </div>
        </div>
    </div> 
</div>

<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Father', 'Is Employee a Father?') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group-radio">
                <input type="radio" id="FatherYes" name="Father" value="Yes" class="custom-radio" {{ isset($employees) && $employees->Father==='Yes' ? 'checked' : '' }}>
                <label for="FatherYes" class="custom-radio-label">Yes</label>

                <input type="radio" id="FatherNo" name="Father" value="" class="custom-radio" {{ isset($employees) && $employees->Father==='Yes' ? '' : 'checked' }}>
                <label for="FatherNo" class="custom-radio-label">No</label>
            </div>
        </div>
    </div> 
</div>