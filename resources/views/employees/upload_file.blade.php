@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6 offset-lg-3 col-md-12">
        <a href="{{ route('employees.show', [$employeeId]) }}" class="btn btn-link-muted ico-tab" title="Go back"><i class="fas fa-arrow-left"></i></a>
        <h4 style="display: inline; margin-left: 10px;">Upload Files and Documents</h4>
        <br>
        <div style="margin-left: 80px;">
            <span class="text-muted">Upload documents and files supporting {{ Employees::getMergeName($employee) }}'s employment to {{ env('APP_COMPANY_ABRV') }}. You can also upload multiple files at once and rename it in the "Files" tab in employee management console.</span>
        </div>

        <div class="mt-5">
            <form action="{{ route('employees.save-uploaded-files') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <input type="hidden" value="{{ $employeeId }}" name="EmployeeId">
                <div class="upload-jumbotron" style="cursor: pointer;">
                    <input id="select-file" type="file" name="files[]" accept=".jpeg,.jpg,.png,.bmp,.webp,.pdf" required multiple>
                    <h4 id="selected-file-name" class="text-muted">Click Here to Select File</h4>
                </div>
                <br>
                <button type="submit" class="btn btn-primary mt-2 float-right"><i class="fas fa-upload ico-tab-mini"></i>Upload</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('.upload-jumbotron').on('click', function() {
                $('#select-file')[0].click()
            })

            $('#select-file').on('change', function() {
                if ($(this)[0].files.length > 1) {
                    $('#selected-file-name').html('<i class="fas fa-file ico-tab-mini"></i> ' + $(this)[0].files.length + ' files to be uploaded')
                } else {
                    $('#selected-file-name').html('<i class="fas fa-file ico-tab-mini"></i>' + $(this)[0].files[0].name)
                }
            });
        })
    </script>
@endpush