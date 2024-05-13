@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                    Create GRS
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3 row">

        @include('adminlte-templates::common.errors')

        <div class="col-lg-6 offset-lg-3 col-md-12">
            <div class="card shadow-none">

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="">Vehicle</label>
                            <select class="custom-select select2"  name="Vehicle" id="Vehicle" style="width: 100%;" required>
                                <option value="">-- Select --</option>
                                <option value="Personal">PERSONAL CAR/MOTORCYCLE</option>
                                @foreach ($vehicles as $item)
                                    <option value="{{ $item->VehicleName }}" driver="{{ $item->DesignatedDriver }}">{{ $item->VehicleName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-lg-12 mt-2">
                            <label for="">Type of Fuel</label>
                            <div class="input-group-radio">
                                <input type="radio" id="Diesel" name="TypOfFuel" value="Diesel" class="custom-radio" checked>
                                <label for="Diesel" class="custom-radio-label">Diesel</label>
            
                                <input type="radio" id="Gasoline" name="TypOfFuel" value="Gasoline" class="custom-radio">
                                <label for="Gasoline" class="custom-radio-label">Gasoline</label>
                            </div>
                        </div>

                        <div class="form-group col-lg-12 mt-2">
                            <label for="">Number of Liters</label>
                            <input class="form-control" name="TotalLiters" id="TotalLiters" type="text" placeholder="Liters"/>
                        </div>
                        
                        <div class="form-group col-lg-12 mt-2">
                            <label for="">Purpose</label>
                            <textarea class="form-control" name="Purpose" id="Purpose" rows="3" placeholder="Separate with semicolon (;) if more than one"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button id="save" class="btn btn-primary float-right"><i class="fas fa-print ico-tab-mini"></i>Save and Print</button>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#save').on('click', function() {
                const vehicle = $('#Vehicle').val()
                const typeOfFuel = $('input[name="TypOfFuel"]:checked').val()
                const totalLiters = $('#TotalLiters').val()
                const purpose = $('#Purpose').val()

                if (!isNull(vehicle) && !isNull(totalLiters) && !isNull(purpose)) {
                    $.ajax({
                        url : "{{ route('tripTicketGRS.save-grs-no-trip-ticket') }}",
                        type : 'POST',
                        data : {
                            _token : "{{ csrf_token() }}",
                            Vehicle : vehicle,
                            TypeOfFuel : typeOfFuel,
                            TotalLiters : totalLiters,
                            Purpose : purpose,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'GRS saved!'
                            })
                            window.location.href = "{{ url('/trip_ticket_g_rs/print-grs-no-tt') }}/" + res
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error creating GRS',
                            })
                        }
                    })
                } else {
                    Toast.fire({
                        icon : 'warning',
                        text : 'Please fill in all fields'
                    })
                }
            })
        })
    </script>
@endpush
