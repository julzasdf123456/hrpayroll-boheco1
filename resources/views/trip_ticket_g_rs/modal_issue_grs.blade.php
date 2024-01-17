<div class="modal fade" id="modal-trip-ticket-details" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="max-width: 90% !important; margin-top: 20px;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        Issue GRS
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
                    {{-- TT DETAILS --}}
                    <div class="col-lg-7 col-md-6">
                        <span class="text-muted">Trip Ticket Details</span>
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

                    {{-- ISSUE GRS --}}
                    <div class="col-lg-5 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <span class="text-muted"><i class="fas fa-gas-pump ico-tab"></i>Issue GRS Here</span>
                                <div class="divider"></div>
                                <table class="table table-borderless table-hover">
                                    <tr>
                                        <td class="text-muted">Fuel Type</td>
                                        <td>
                                            <div class="radio-group-horizontal-sm" style="border: 0px !important;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="TypeOfFuel" value="Diesel" id="Diesel" checked>
                                                    <label class="form-check-label" for="Diesel">Diesel</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="TypeOfFuel" value="Gasoline" id="Gasoline">
                                                    <label class="form-check-label" for="Gasoline">Gasoline</label>
                                                </div>
                                            </div> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. of Liters</td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" id="TotalLiters">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="card-footer">
                                <button class="btn btn-success float-right" onclick="saveGRS()"><i class="fas fa-check-circle"></i> Save & Print GRS</button>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
 </div>
 
 @push('page_scripts')
    <script>
        var ttId = null
        var purposeGlobal = ""
       $(document).ready(function() {
          
       })

        function expand(id) {
            ttId = id
            $('#modal-trip-ticket-details').modal({backdrop: 'static', keyboard: false}, 'show')

            getTripTicketDetails(id)
        }

        function getTripTicketDetails(id) {
            $('#loader').removeClass('gone')
            $('#tt-modal-signatory').text('')
            purposeGlobal = ""

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

                            if (i == (purposes.length-1)) {
                                purposeGlobal += purposes[i]
                            } else {
                                purposeGlobal += purposes[i] + ", "
                            }
                            
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

        function saveGRS() {
            if (jQuery.isEmptyObject($('#TotalLiters').val())) {
                Toast.fire({
                    icon : 'info',
                    text : 'Please provide number of liters!'
                })
            } else {
                $.ajax({
                    url : "{{ route('tripTicketGRS.save-grs') }}",
                    type : "GET",
                    data : {
                        TypeOfFuel : $('input[name="TypeOfFuel"]:checked').val(),
                        TotalLiters : $('#TotalLiters').val(),
                        TripTicketId : ttId,
                        Purpose : purposeGlobal
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'GRS issued successfully!'
                        })
                        
                        window.location.href = "{{ url('/trip_ticket_g_rs/print-grs') }}/" + ttId + "/" + res['id']
                    },
                    error : function(err) {
                        Swal.fire({
                            icon : 'error',
                            text : 'An error occurred while saving GRS'
                        })
                    }
                })
            }
            
        }
    </script>    
 @endpush