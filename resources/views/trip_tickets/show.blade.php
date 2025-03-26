@php
    use App\Models\IDGenerator;
    use App\Models\Employees;
    use App\Models\Towns;
    use App\Models\Barangays;
    use Illuminate\Support\Facades\DB;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4>
                        Your Trip Ticket Details
                    </h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        {!! Form::model($tripTickets, ['route' => ['tripTickets.update', $tripTickets->id], 'method' => 'patch']) !!}

        <div class="row">
            {{-- DETAILS --}}
            <div class="col-lg-7 col-md-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        {{-- <span class="card-title badge" style="padding: 10px;"><i class="fas fa-info-circle ico-tab"></i>{{ $tripTickets->Status }}</span> --}}
                        @if ($tripTickets->Status == 'APPROVED')
                            <span class="card-title badge bg-success" style="padding: 10px;"><i class="fas fa-check ico-tab"></i>{{ $tripTickets->Status }}</span>
                        @elseif ($tripTickets->Status == 'REJECTED' || $tripTickets->Status == 'Trash')
                        <span class="card-title badge bg-danger" style="padding: 10px;"><i class="fas fa-info-circle ico-tab"></i>{{ $tripTickets->Status }}</span>
                        @else
                        <span class="card-title badge bg-info" style="padding: 10px;"><i class="fas fa-info-circle ico-tab"></i>{{ $tripTickets->Status }}</span>
                        @endif
                    </div>
    
                    <div class="card-body p-0">
                        {{-- FIELDS --}}
                        <table class="table table-hover table-sm table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Date Of Travel</td>
                                    <td>
                                        <input disabled type="text" name="DateOfTravel" id="DateOfTravel" class="form-control" autofocus value="{{ $tripTickets->DateOfTravel }}">
                                        @push('page_scripts')
                                            <script type="text/javascript">
                                                $('#DateOfTravel').datetimepicker({
                                                    format: 'YYYY-MM-DD',
                                                    useCurrent: true,
                                                    sideBySide: true,
                                                    icons : {
                                                        previous : 'fas fa-caret-left',
                                                        next : 'fas fa-caret-right',
                                                    }
                                                })
                                            </script>
                                        @endpush
                                    </td>
                                </tr>
                                <tr title="Filed By:">
                                    <td class="text-muted">Requisitioner</td>
                                    <td>
                                        <select disabled class="custom-select select2"  name="EmployeeId" id="EmployeeId" style="width: 100%;" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($employees as $item)
                                                <option value="{{ $item->id }}" {{ $tripTickets->EmployeeId != null && $tripTickets->EmployeeId==$item->id ? 'selected' : '' }}>{{ Employees::getMergeNameFormal($item) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr title="Filed By:">
                                    <td class="text-muted">Purpose Of Travel</td>
                                    <td>
                                        <textarea disabled  class="form-control form-control-sm" name="PurposeOfTravel" id="PurposeOfTravel" rows="4" placeholder="Seprate with semicolon (;) if more than one" required>{{ $tripTickets->PurposeOfTravel }}</textarea>
                                    </td>
                                </tr>
                                <tr title="Filed By:">
                                    <td class="text-muted">Vehicle</td>
                                    <td>
                                        <select disabled class="custom-select select2"  name="Vehicle" id="Vehicle" style="width: 100%;" required>
                                            <option value="">-- Select --</option>
                                            <option value="Personal" {{ $tripTickets->Vehicle=='Personal' ? 'selected' : '' }}>PERSONAL CAR/MOTORCYCLE</option>
                                            @foreach ($vehicles as $item)
                                                <option value="{{ $item->VehicleName }}" driver="{{ $item->DesignatedDriver }}" {{ $tripTickets->Vehicle==$item->VehicleName ? 'selected' : '' }}>{{ $item->VehicleName }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr title="Filed By:">
                                    <td class="text-muted">Driver</td>
                                    <td>
                                        <select disabled class="custom-select select2"  name="Driver" id="Driver" style="width: 100%;" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($drivers as $item)
                                                <option value="{{ $item->id }}" {{ $tripTickets->Driver==$item->id ? 'selected' : '' }}>{{ Employees::getMergeNameFormal($item) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="{{ Auth::user()->ColorProfile != null ? 'divider-dark' : 'divider' }}"></div>

                        {{-- PASSENGERS --}}
                        <p class="text-muted mx-2">Passengers</p>
                        <table class="table table-hover table-sm table-borderless" id="passengers-table">
                            <thead>
                                <th>Employee ID</th>
                                <th>Employee/Passenger</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($passengers as $item)
                                    <tr id="added-passenger-{{ $item->id }}">
                                        <td>{{ $item->id }}</td>
                                        <td>{{ Employees::getMergeName($item) }}</td>
                                        <td>
                                            {{-- <button onclick='return removePassenger(`{{ $item->id }}`)' class='btn btn-sm btn-link text-danger float-right'><i class='fas fa-trash'></i></button> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="" style="margin: 20px;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- DESTINATION --}}
            <div class="col-lg-5 col-md-12">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title"><i class="fas fa-map ico-tab"></i>Destinations & Signatories</span>
                    </div>
                    <div class="card-body">
                        {{-- DESTINATION --}}
                        <table class="table table-hover table-sm table-borderless" id="destinations-table">
                            <thead>
                                <th>Destinations</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @php
                                    $typedDestinations = "";
                                @endphp
                                @foreach ($destinations as $item)
                                    @if ($item->TownId != null)
                                        <tr id='added-destination-{{ $item->id }}'>
                                            <td><i class='fas fa-map-marker-alt ico-tab text-primary'></i>{{ $item->DestinationAddress }}</td>
                                            <td>
                                                {{-- <button onclick='return removeDestination(`{{ $item->id }}`, `{{ $item->DestinationAddress }}`)' class='btn btn-sm btn-link text-danger float-right'><i class='fas fa-trash'></i></button> --}}
                                            </td>
                                        </td>
                                    @else
                                        @php
                                            $typedDestinations .= $item->DestinationAddress . ";";
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        {{-- ADD DESTINATION OPTION --}}
                        <div class="card" style="margin: 10px;">
                            <div class="card-body py-1">
                                <table class="table table-hover table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-map-marker-alt ico-tab-mini"></i>Other Manually Inputted Destinations</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <textarea disabled  class="form-control" name="DestinationTyped" id="DestinationTyped" rows="4">{{ $typedDestinations }}</textarea>
                                <br>
                            </div>
                        </div>

                        {{-- ADD SIGNATORY --}}
                        <div class="card" style="margin: 10px;">
                            <div class="card-body py-1">
                                <table class="table table-hover table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-user-edit ico-tab-mini"></i>Default Signatory</td>
                                            <td>
                                                <select disabled style="width: 100%;" class="custom-select select2" id="Signatory" name="Signatory">
                                                    <option value="">-- Select --</option>             
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{-- <button class="btn btn-success float-right" type="submit"><i class="fas fa-check-circle ico-tab-mini"></i> Update Trip Ticket</button> --}}
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // VALIDATE EMPLOYEE
            if (!jQuery.isEmptyObject($('#EmployeeId').val())) {
                getDefaultSignatories($('#EmployeeId').val())
            }

            // VALIDATE VEHICLE
            if (!jQuery.isEmptyObject($('#Vehicle').val()) && $('#Vehicle').val()=='Personal') {
                var requisitionerId = $('#EmployeeId').val()
                var requisitionerName = $("#EmployeeId option:selected").text();
                $('#Driver').append("<option value='" + requisitionerId + "'>" + requisitionerName + "</option>")
                $('#Driver').val(requisitionerId).change() 
            }

            $('#EmployeeId').on('change', function() {
                $('#Vehicle').val('').change()

                if (!jQuery.isEmptyObject(this.value)) {
                    getDefaultSignatories(this.value)
                }
            })

            $('#PassengerSelection').on('change', function() {
                var name = $("#PassengerSelection option:selected").text();
                
                $('#passengers-table tbody').append(addPassengerRow(this.value, name))

                $.ajax({
                    url : "{{ route('tripTicketPassengers.store') }}",
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        TripTicketId : "{{ $tripTickets->id }}",
                        EmployeeId : this.value,
                    },
                    success : function(res) {

                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error adding passenger'
                        })
                        removePassenger(this.value)
                    }
                })
            })

            $('#Destinations').on('change', function() {
                var destination = $("#Destinations option:selected").text();
                var brgyId = $("#Destinations option:selected").attr("barangayId");
                var townId = $("#Destinations option:selected").attr("townId");
                
                $('#destinations-table tbody').append(addDestinationRow(this.value, destination))

                $.ajax({
                    url : "{{ route('tripTicketDestinations.store') }}",
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        TripTicketId : "{{ $tripTickets->id }}",
                        DestinationAddress : $("#Destinations option:selected").text(),
                        BarangayId : brgyId,
                        TownId : townId
                    },
                    success : function(res) {

                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error adding destination'
                        })
                        // removePassenger(this.value)
                    }
                })
            })

            $('#Vehicle').on('change', function() {
                if (this.value == 'Personal') {
                    var requisitionerId = $('#EmployeeId').val()
                    var requisitionerName = $("#EmployeeId option:selected").text();
                    if (jQuery.isEmptyObject(requisitionerId)) {
                        $('#Vehicle').val('').change()
                        Swal.fire({
                            icon : 'info',
                            title : 'Select REQUISITIONER First',
                            text : 'In order to use personal car/motorcycle, kindly provide requisitioner first.'
                        })
                    } else {
                        $('#Driver').append("<option value='" + requisitionerId + "'>" + requisitionerName + "</option>")
                        $('#Driver').val(requisitionerId).change()
                    }                    
                } else {
                    $('#Driver').val($("#Vehicle option:selected").attr('driver')).change()
                }
            })
        })

        function addPassengerRow(id, name) {
            return "<tr id='added-passenger-" + id + "'>" +
                        "<td>" + id + "</td>" +
                        "<td>" + name + "</td>" +
                        "<td>" + 
                            "<button onclick='return removePassenger(`" + id + "`)' class='btn btn-sm btn-link text-danger float-right'><i class='fas fa-trash'></i></button>" +
                        "</td>" +
                    "</td>"
        }

        function removePassenger(id) {
            $('#added-passenger-' + id).remove()

            $.ajax({
                url : "{{ route('tripTicketPassengers.remove-passenger-ajax') }}",
                type : "GET",
                data : {
                    EmployeeId : id,
                    TripTicketId : "{{ $tripTickets->id }}",
                },
                success : function (res) {
                    
                },
                error : function(err) {
                    Toast.fire({
                            icon : 'error',
                            text : 'Error removing passenger'
                        })
                }
            })

            return false
        }

        function addDestinationRow(id, destination) {
            return "<tr id='added-destination-" + id + "'>" +
                        "<td><i class='fas fa-map-marker-alt ico-tab text-primary'></i>" + destination + "</td>" +
                        "<td>" + 
                            "<button onclick='return removeDestination(`" + id + "`, `" + destination + "`)' class='btn btn-sm btn-link text-danger float-right'><i class='fas fa-trash'></i></button>" +
                        "</td>" +
                    "</td>"
        }

        function removeDestination(id, destinationAddress) {
            $('#added-destination-' + id).remove()

            $.ajax({
                url : "{{ route('tripTicketDestinations.remove-destination') }}",
                type : "GET",
                data : {
                    DestinationAddress : destinationAddress,
                    TripTicketId : "{{ $tripTickets->id }}",
                },
                success : function (res) {
                    
                },
                error : function(err) {
                    Toast.fire({
                            icon : 'error',
                            text : 'Error removing destination'
                        })
                }
            })

            return false
        }

        function getDefaultSignatories(employeeId) {
            $('#Signatory option').remove()
            $('#Signatory optgroup').remove()
            $('#Signatory').append('<option value="">-- Select --</option>')

            $.ajax({
                url : "{{ route('tripTickets.get-signatories') }}",
                type : "GET",
                data : {
                    EmployeeId : employeeId,
                },
                success : function(res) {
                    var defaultSignatory = res.Signatories
                    var otherSignatory = res.OtherSignatories

                    // default sginatories
                    if (!jQuery.isEmptyObject(defaultSignatory)) {
                        $('#Signatory').append(`<optgroup label="Default Signatories">`)
                        $.each(defaultSignatory, function(index, element) {
                            $('#Signatory').append(addSignatoryOptions(
                                    defaultSignatory[index]['id'], 
                                    serializeEmployeeName(defaultSignatory[index]['FirstName'], defaultSignatory[index]['MiddleName'], defaultSignatory[index]['LastName'], defaultSignatory[index]['Suffix']), 
                                    (defaultSignatory[index]['Level'] == "Manager" ? true : (index == 0 ? true : false)) // set manager first, if no manager is detected, switch to first array
                                )
                            )
                        })
                        $('#Signatory').append(`</optgroup>`)
                    }

                    // other signatories
                    if (!jQuery.isEmptyObject(otherSignatory)) {
                        $('#Signatory').append(`<optgroup label="Other Signatories">`)
                        $.each(otherSignatory, function(index, element) {
                            var checkDefault = defaultSignatory.find(obj => obj.id === otherSignatory[index]['id'])

                            if (isNull(checkDefault)) {
                                $('#Signatory').append(addSignatoryOptions(
                                        otherSignatory[index]['id'], 
                                        serializeEmployeeName(otherSignatory[index]['FirstName'], otherSignatory[index]['MiddleName'], otherSignatory[index]['LastName'], otherSignatory[index]['Suffix']), 
                                        false // set manager first, if no manager is detected, switch to first array
                                    )
                                )
                            }
                        })
                        $('#Signatory').append(`</optgroup>`)
                    }
                },
                error : function(xhr, status, error) {
                    Swal.fire({
                        icon : 'error',
                        title : 'Oops!',
                        text : xhr.responseText
                    })
                }
            })
        }

        function addSignatoryOptions(employeeId, employeeName, isSelected) {
            if (isSelected) {
                return "<option value='" + employeeId + "' selected>" + employeeName + "</option>"
            } else {
                return "<option value='" + employeeId + "'>" + employeeName + "</option>"
            }
            
        }
    </script>
@endpush
