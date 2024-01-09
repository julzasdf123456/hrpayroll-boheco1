<!-- Type Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Type', 'Type:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                </div>
                {!! Form::select('Type', [
                    '1 - A. Educational Attainment/Professional Qualification - Academic Degrees' => '1 - A. Educational Attainment/Professional Qualification - Academic Degrees', 
                    '1 - B. Educational Attainment/Professional Qualification - Additional Graduate Degrees' => '1 - B. Educational Attainment/Professional Qualification - Additional Graduate Degrees', 
                    '1 - C. Educational Attainment/Professional Qualification - Professional Government Exam Passed' => '1 - C. Educational Attainment/Professional Qualification - Professional Government Exam Passed',
                    '1 - D. Educational Attainment/Professional Qualification - Civil Service Eligibility' => '1 - D. Educational Attainment/Professional Qualification - Civil Service Eligibility',
                    '2. Rating Scale for Teaching/Work Performance' => '2. Rating Scale for Teaching/Work Performance',
                    '3 - A. Rating Scale for Professional Growth - Research and Publications' => '3 - A. Rating Scale for Professional Growth - Research and Publications',
                    '3 - B. Rating Scale for Professional Growth - Special Training Courses in Related Fields' => '3 - B. Rating Scale for Professional Growth - Special Training Courses in Related Fields',
                    '3 - C. Rating Scale for Professional Growth - Academic Awards and Honors' => '3 - C. Rating Scale for Professional Growth - Academic Awards and Honors',
                    '4 - A. Rating Scale for School and Community Service - Co-Curricular Activities and School Assignmets' => '4 - A. Rating Scale for School and Community Service - Co-Curricular Activities and School Assignmets',
                    '4 - B. Rating Scale for School and Community Service - Participation in Community Outreach and Civic Involvement' => '4 - B. Rating Scale for School and Community Service - Participation in Community Outreach and Civic Involvement',
                    '4 - C. Rating Scale for School and Community Service - Administrative Positions Held' => '4 - C. Rating Scale for School and Community Service - Administrative Positions Held',
                    '4 - D. Rating Scale for School and Community Service - Membership in Learned and Professional Societies, Associations, and Orgs' => '4 - D. Rating Scale for School and Community Service - Membership in Learned and Professional Societies, Associations, and Orgs',
                    '5. Rating Scale for Teaching/Work Experience' => '5. Rating Scale for Teaching/Work Experience',
                ], null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>  
</div>

<!-- Rankingname Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('RankingName', 'Ranking:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                </div>
                {!! Form::text('RankingName', null, ['class' => 'form-control','maxlength' => 1000,'maxlength' => 1000]) !!}
            </div>
        </div>
    </div> 
</div>

<!-- Points Field -->
<div class="form-group col-sm-12">
    <div class="row">
        <div class="col-lg-3 col-md-5">
            {!! Form::label('Points', 'Points:') !!}
        </div>

        <div class="col-lg-9 col-md-7">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-sort"></i></span>
                </div>
                {!! Form::number('Points', null, ['class' => 'form-control', 'step' => 'any']) !!}
            </div>
        </div>
    </div> 
</div>
