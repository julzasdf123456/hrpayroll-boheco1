@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>BEMPC Upload Deductions History</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('bempcs.upload') }}">
                        Upload <i class="fas fa-upload ico-tab-left-mini"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="row">
            @foreach ($bempcs as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-none">
                        <div class="card-body" style="padding: 32px 24px 24px 24px;">
                            <span class="text-muted">Deduction For</span>
                            <h4 style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis; padding-top: 6px;"><strong>{{ $item->DeductionFor }}</strong></h4>
                            <span class="text-muted"><i class="fas fa-user ico-tab"></i>{{ number_format($item->Count) }} employees deducted</span><br>
                            <span class="text-muted"><i class="fas fa-dollar-sign ico-tab"></i>{{ number_format($item->Total, 2) }} total amount</span>

                            <br>
                            <br>
                            <div>
                                <a href="{{ route('bempcs.view-upload', [urlencode($item->DeductionFor), $item->DateCreated]) }}" class="btn btn-primary-skinny"><i class="fas fa-eye ico-tab-mini"></i>View</a>
                                <button onclick="remove(`{{ $item->DeductionFor }}`, `{{ $item->DateCreated }}`)" class="btn btn-link text-danger float-right"><i class="fa fa-trash"></i></button>
                            </div> 
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection

@push('page_scripts')
    <script>
        function remove(deductionfor, date) {
            Swal.fire({
                title: "Confirm Delete?",
                text : 'Deleting this will remove the deductions. This cannot be undone.',
                showCancelButton: true,
                confirmButtonText: "Proceed Delete",
                confirmButtonColor : "#e0321b",
                denyButtonText: `Cancel`
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bempcs.delete') }}",
                        type : "GET",
                        data : {
                            For : deductionfor,
                            Date : date
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Data deleted!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Swal.fire({
                                icon : 'error',
                                text : 'Error deleting data!'
                            })
                        }
                    })
                } 
            });
        }
    </script>
@endpush
