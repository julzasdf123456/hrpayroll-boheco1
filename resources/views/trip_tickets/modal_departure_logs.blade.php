@php
    use App\Models\Employees;
@endphp

<div class="modal fade" id="modal-departure-logs" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
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
                            <td id="tt-modal-passengers"><ul></ul></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Destinations</td>
                            <td id="tt-modal-destinations"></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Signatory</td>
                            <td id="tt-modal-signatory"></td>
                        </tr>
                        @if (Route::currentRouteName() == "tripTickets.log-vehicle-trips")
                            <tr>
                                <td class="text-muted">Add Passenger</td>
                                <td>
                                    <select class="custom-select select2" id="PassengerSelection" style="width: 100%;">
                                        <option value="">-- Select --</option>
                                        @foreach ($employees as $item)
                                            <option id="passenger-{{ $item->id }}" value="{{ $item->id }}">{{ Employees::getMergeNameFormal($item) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button onclick="logDeparture()" class="btn btn-primary"><i class="fas fa-sign-out-alt ico-tab-mini"></i>Log Departure</button>
            </div>
        </div>
    </div>
 </div>
 
 @push('page_scripts')
    <script>
        var ttId = null
        $(document).ready(function() {
            $('#PassengerSelection').on('change', function() {
                var name = $("#PassengerSelection option:selected").text();
                
                $('#tt-modal-passengers ul').append(appendLiPassengers(this.value, name))

                $.ajax({
                    url : "{{ route('tripTicketPassengers.store') }}",
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        TripTicketId : ttId,
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
        })

        function expand(id) {
            ttId = id
            $('#modal-departure-logs').modal('show')

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
                            $('#tt-modal-passengers ul li').remove()
                        } else {
                            var passengers = res['Passengers']
                            var passengersBulleted = ""
                            $.each(passengers, function(index, element) {
                                passengersBulleted += appendLiPassengers(passengers[index]['id'], serializeEmployeeName(passengers[index]['FirstName'],  passengers[index]['LastName'], passengers[index]['MiddleName'], passengers[index]['Suffix']))  
                            })  

                            $('#tt-modal-passengers ul').html(passengersBulleted)
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
                        if (jQuery.isEmptyObject(res['Signatory'])) {
                            $('#tt-modal-signatory').text("-")
                        } else {
                            var signatory = res['Signatory']                            
                            $('#tt-modal-signatory').append(signatory['name'])         
                            $('#tt-modal-signatory').append(validateSignatoryStatus(signatory['Status']))
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

        function appendLiPassengers(id, name) {
            return "<li id='passenger-guard-added-" + id + "'>" + name + 
                    "<button class='btn btn-sm btn-link' onclick='removePassenger(`" + id + "`)'><i class='fas fa-trash text-danger'></i></button>"
                "</li>"
        }

        function removePassenger(id) {
            $('#passenger-guard-added-' + id).remove()

            $.ajax({
                url : "{{ route('tripTicketPassengers.remove-passenger-ajax') }}",
                type : "GET",
                data : {
                    EmployeeId : id,
                    TripTicketId : ttId,
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
        }

        function logDeparture() {
            Swal.fire({
                title: "Confirm Departure",
                icon: "info",
                showCancelButton: true,
                cancelButtonColor: "#3273a8",
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('tripTickets.log-departure') }}",
                        type : "GET",
                        data : {
                            id : ttId, // ttId is found in `modal_show_trip_ticket.blade.php`
                        },
                        success : function(res) {
                            $('#modal-departure-logs').modal('hide')
                            Toast.fire({
                                icon : 'success',
                                text : 'Trip marked as departed'
                            })
                            $('#' + ttId).remove()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error marking departure on this trip ticket!'
                            })
                        }
                    })
                }
            })
        }
    </script>    
 @endpush