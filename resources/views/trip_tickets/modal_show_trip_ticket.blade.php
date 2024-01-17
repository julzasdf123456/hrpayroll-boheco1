@php
    use App\Models\Employees;
@endphp

<div class="modal fade" id="modal-trip-ticket-details" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: 90% !important; margin-top: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        <span class="badge" id="tt-status"></span>
                      <div id="loader" class="spinner-border text-success" role="status">
                         <span class="sr-only">Loading...</span>
                      </div>
                    </h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- DETAILS --}}
                    <div class="col-lg-8 col-md-12">
                        <span class="text-muted"><strong>Trip Details</strong></span>
                        <table class="table table-hover table-bordered table-sm" id="table-trip-ticket-modal-details">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Date Filed</td>
                                    <td id="tt-modal-date-filed"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Date of Travel</td>
                                    <td id="tt-modal-date-of-travel"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Requisitioner</td>
                                    <td id="tt-modal-requisitioner"></td>
                                </tr>                        
                                <tr>
                                    <td class="text-muted">Driver</td>
                                    <td id="tt-modal-driver"></td>
                                </tr>                        
                                <tr>
                                    <td class="text-muted">Vehicle</td>
                                    <td id="tt-modal-vehicle"></td>
                                </tr>                        
                                <tr>
                                    <td class="text-muted">Purpose of Travel</td>
                                    <td id="tt-modal-purpose"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Passengers</td>
                                    <td id="tt-modal-passengers"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Destinations</td>
                                    <td id="tt-modal-destinations"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Signatory</td>
                                    <td id="tt-modal-signatory"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- LOGS AND GRS --}}
                    <div class="col-lg-4 col-md-12">
                        {{-- LOGS --}}
                        <span class="text-muted"><strong>Logs</strong></span>
                        <div class="mb-2 p-2 {{ Auth::user()->ColorProfile != null ? 'border-left-dark' : 'border-left-light' }}">
                            <span style="font-size: .85em;" class="badge bg-warning">DEPARTURE TIME</span>
                            <br>
                            <span id="tt-departure" class="ico-tab-mini"></span>
                            <span id="tt-departure-logger" style="font-size: .85em;" class="text-muted"></span>
                        </div>
                        <div class="mb-2 p-2 {{ Auth::user()->ColorProfile != null ? 'border-left-dark' : 'border-left-light' }}">
                            <span style="font-size: .85em;" class="badge bg-success">ARRIVAL TIME</span>
                            <br>
                            <span id="tt-arrival" class="ico-tab-mini"></span>
                            <span id="tt-arrival-logger" style="font-size: .85em;" class="text-muted"></span>
                        </div>

                        <br>
                        {{-- GRS --}}   
                        <div id="grs-section">
                            <span class="text-muted"><strong>GRS</strong></span>
                            <table class="table table-hover table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Fuel Type</td>
                                    <td id="grs-fuel-type"></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. of Liters</td>
                                    <td id="grs-liters"></td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                @if (Route::currentRouteName() == "tripTickets.my-approvals")
                    {{-- approve() is at my_approvals.blade.php --}}
                    <button onclick="approve()" class="btn btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                    {{-- reject() is at my_approvals.blade.php --}}
                    <button onclick="reject()" class="btn btn-danger"><i class="fas fa-times-circle ico-tab-mini"></i>Reject</button>
                @endif

                @if (Route::currentRouteName() == "tripTickets.log-vehicle-arrivals")
                    {{-- arrive() is at log_vehicle_arrivals.blade.php --}}
                    <button onclick="arrive()" class="btn btn-primary"><i class="fas fa-sign-in-alt ico-tab-mini"></i>Mark Arrival</button>
                @endif
            </div>
        </div>
    </div>
 </div>
 
 @push('page_scripts')
    <script>
        var ttId = null
        $(document).ready(function() {
            
        })

        function expand(id) {
            ttId = id
            $('#modal-trip-ticket-details').modal('show')

            getTripTicketDetails(id)
        }

        function getTripTicketDetails(id) {
            $('#loader').removeClass('gone')
            $('#tt-modal-signatory').text('')

            $.ajax({
                url : "{{ route('tripTickets.get-trip-ticket-ajax') }}",
                type : "GET",
                data : {
                    id : id,
                },
                success : function(res) {
                    $('#loader').addClass('gone')

                    if (jQuery.isEmptyObject(res)) {
                        Toast.fire({
                            icon : 'info',
                            text : 'No ticket details found!'
                        })
                    } else {
                        // LOAD TRIP TICKET DETAILS
                        var purpose = jQuery.isEmptyObject(res['PurposeOfTravel']) ? '-' : res['PurposeOfTravel']
                        var purposes = purpose.split(";")
                        var purposeBulleted = ""
                        for (var i=0; i<purposes.length; i++) {
                            purposeBulleted += "<li class='no-pads'>" + purposes[i] + "</li>"
                        }

                        $('#tt-status').text(res['Status'])
                        $('#tt-status').removeClass('bg-primary').removeClass('bg-success').removeClass('bg-danger')
                        if (res['Status'] == 'FILED') {
                            $('#tt-status').addClass('bg-primary')
                        } else if (res['Status'] == 'APPROVED') {
                            $('#tt-status').addClass('bg-success')
                        } else {
                            $('#tt-status').addClass('bg-danger')
                        }

                        $('#tt-modal-date-filed').text(moment(res['DatetimeFiled']).format('MMM DD, YYYY'))
                        $('#tt-modal-date-of-travel').text(moment(res['DateOfTravel']).format('MMM DD, YYYY'))
                        $('#tt-modal-requisitioner').text(serializeEmployeeName(res['FirstName'],  res['LastName'], res['MiddleName'], res['Suffix']))
                        $('#tt-modal-driver').text(serializeEmployeeName(res['DriverFirstName'],  res['DriverLastName'], res['DriverMiddleName'], res['DriverSuffix']))
                        $('#tt-modal-vehicle').text(res['Vehicle'])
                        $('#tt-modal-purpose').html("<ul>" + purposeBulleted + "</ul>")

                        // LOAD PASSENGERS
                        if (jQuery.isEmptyObject(res['Passengers'])) {
                            $('#tt-modal-passengers').text("-")
                        } else {
                            var passengers = res['Passengers']
                            var passengersBulleted = ""
                            $.each(passengers, function(index, element) {
                                passengersBulleted += "<li class='no-pads'>" + serializeEmployeeName(passengers[index]['FirstName'],  passengers[index]['LastName'], passengers[index]['MiddleName'], passengers[index]['Suffix']) + "</li>"
                            })
                            $('#tt-modal-passengers').html("<ul>" + passengersBulleted + "</ul>")
                        }

                        // LOAD DESTINATIONS
                        if (jQuery.isEmptyObject(res['Destinations'])) {
                            $('#tt-modal-destinations').text("-")
                        } else {
                            var desinations = res['Destinations']
                            var desinationsBulleted = ""
                            $.each(desinations, function(index, element) {
                                desinationsBulleted += "<li class='no-pads'>" + desinations[index]['DestinationAddress'] + "</li>"
                            })
                            $('#tt-modal-destinations').html("<ul>" + desinationsBulleted + "</ul>")
                        }

                        // SIGNATORY
                        console.log(res['Signatory'])
                        if (jQuery.isEmptyObject(res['Signatory'])) {
                            $('#tt-modal-signatory').text("-")
                        } else {
                            var signatory = res['Signatory']                            
                            $('#tt-modal-signatory').append(signatory['name'])         
                            $('#tt-modal-signatory').append(validateSignatoryStatus(signatory['Status']))
                        }

                        // LOGS
                        if (jQuery.isEmptyObject(res['DatetimeDeparted'])) {
                            $('#tt-departure').text('-')
                            $('#tt-departure-logger').text('-')
                        } else {
                            $('#tt-departure').text(moment(res['DatetimeDeparted']).format('MMM DD, YYYY hh:mm A'))
                            $('#tt-departure-logger').text('| by ' + res['GuardLoggedDeparture'])
                        }

                        if (jQuery.isEmptyObject(res['DatetimeArrived'])) {
                            $('#tt-arrival').text('-')
                            $('#tt-arrival-logger').text('-')
                        } else {
                            $('#tt-arrival').text(moment(res['DatetimeArrived']).format('MMM DD, YYYY hh:mm A'))
                            $('#tt-arrival-logger').text('| by ' + res['GuardLoggedArrival'])
                        }

                        // GRS
                        if (!jQuery.isEmptyObject(res['GRS'])) {
                            $('#grs-section').removeClass('gone')
                            $('#grs-fuel-type').text(res['GRS']['TypeOfFuel'])
                            $('#grs-liters').text(res['GRS']['TotalLiters'])
                        } else {
                            $('#grs-section').addClass('gone')
                            $('#grs-fuel-type').text('-')
                            $('#grs-liters').text('-')
                        }
                    }
                },
                error : function(err) {
                    $('#loader').addClass('gone')
                    Swal.fire({
                        icon : 'error',
                        text : 'Error fetching trip ticket!'
                    })
                }
            })
        }

        function validateSignatoryStatus(status) {
            if (jQuery.isEmptyObject(status)) {
                return "<span class='badge bg-warning ico-tab-left-mini'>PENDING</span>"
            } else {
                if (status == 'APPROVED') {
                    return "<span class='badge bg-success ico-tab-left-mini'>" + status + "</span>"
                } else if (status == 'DEPARTED') {
                    return "<span class='badge bg-warning ico-tab-left-mini'>" + status + "</span>"
                } else {
                    return "<span class='badge bg-danger ico-tab-left-mini'>" + status + "</span>"
                }
            }
        }
    </script>    
 @endpush