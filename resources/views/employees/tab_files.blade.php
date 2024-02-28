<div class="row p-2">
    <div class="col-lg-12">
        <a href="{{ route('employees.upload-file', [$employees->id]) }}" class="btn btn-link-muted float-right"><i class="fas fa-upload ico-tab-mini"></i>Upload Files</a>

        <div id="app">
            <files-list></files-list>
        </div>
        @vite('resources/js/app.js')
    </div>
</div>