@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Update {{ Employees::getMergeName($employee) }}'s Educational Attainment</h4>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-default float-right"
                   href="{{ route('employees.show', [$employee->id]) }}">
                    Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <span class="card-title">Add Educational Attainment</span>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="form-educ" action="javascript:void(0)" >
                    <input type="hidden" name="employeeid" value="{{ $employee->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="foldername" value="{{ Employees::getMergeName($employee) }}">

                    <div class="form-group">
                        <label for="ranking-select">Select Level</label>
                        <select class="form-control" id="level-select" name="type">
                            <option value="Kindergarten">Kindergarten</option>
                            <option value="Elementary or Grade School">Elementary or Grade School</option>
                            <option value="High School">High School</option>
                            <option value="Junior High">Junior High</option>
                            <option value="Senior High">Senior High</option>
                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                            <option value="Associate Degree">Associate Degree</option>
                            <option value="Master's Degree">Master's Degree</option>
                            <option value="Doctorate Degree">Doctorate Degree</option>
                            <option value="Vocational Course">Vocational Course</option>
                            <option value="TESDA">TESDA</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="major">Major/Course/Description</label>
                        <textarea type="text" class="form-control" value="" id="major" cols="2" name="major"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="school">School</label>
                        <textarea type="text" class="form-control" value="" id="school" cols="2" name="school"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="schoolyear">School Year</label>
                        <input type="text" class="form-control" value="" id="schoolyear" name="schoolyear">
                    </div>

                    <div class="form-group">
                        <label for="certification">Upload Diploma/Certification</label>
                        <br>
                        <input type="file" name="file" placeholder="Upload Diploma/Certification" id="certification">
                        <span class="text-danger">{{ $errors->first('file') }}</span>
                    </div>

                    <button type="submit" class="btn btn-info float-right" id="add-education">Add</button>
                </form>                
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Recorded Educational Attainment</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-borderless">
                    <thead>
                        <th>Level</th>
                        <th>Major/Course Taken</th>
                        <th>School</th>
                        <th>School Year</th>
                        <th>Certifications</th>
                        <th width="30px"></th>
                    </thead>
                    <tbody>
                        @if ($educationalAtainment != null)
                            @foreach ($educationalAtainment as $item)
                                <tr>
                                    <td>{{ $item->Type }}</td>
                                    <td>{{ $item->Major }}</td>
                                    <td>{{ $item->School }}</td>
                                    <td>{{ $item->SchoolYear }}</td>
                                    <td>
                                        @if ($item->Certification != null)
                                        <a href="{{ route('employees.download-file', [trim($employee->id), $item->Type, $item->Certification]) }}" target="_blank">{{ $item->Certification }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <button onclick="deleteEducationalAttainment({{ $item->id }})" class="btn btn-sm text-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        function deleteEducationalAttainment(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    url : '/educationalAttainments/' + id,
                    type : 'DELETE',
                    data : {
                        _token : "{{ csrf_token() }}",
                    },
                    success : function(res) {
                        location.reload()
                    },
                    error : function(err) {
                        alert('An error occurred while attempting to delete an item. Contact support for more')
                    }
                })
            }
        }

        $(document).ready(function() {
            $('#add-education').on('click', function() {
                var formData = new FormData(document.getElementById('form-educ'))
        
                $.ajax({
                    url : '/employees/save-educatonal-attainment',
                    type : 'POST',
                    data : formData,
                    cache : false,
                    contentType : false, 
                    processData : false,
                    success : function(response) {
                        // alert('Data uploaded!')
                        // console.log(response)
                        location.reload();
                    },
                    error : function(error) {
                        alert(error)
                    }
                })
            })
        })
    </script>
@endpush