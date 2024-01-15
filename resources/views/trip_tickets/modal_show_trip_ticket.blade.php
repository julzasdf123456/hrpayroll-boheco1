<div class="modal fade" id="modal-trip-ticket-details" aria-hidden="true" style="display: none;">
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
            <div class="modal-footer">
                @if (Route::currentRouteName() == "tripTickets.my-approvals")
                    {{-- approve() is at my_approvals.blade.php --}}
                    <button onclick="approve()" class="btn btn-success"><i class="fas fa-check-circle ico-tab-mini"></i>Approve</button>
                    {{-- reject() is at my_approvals.blade.php --}}
                    <button onclick="reject()" class="btn btn-danger"><i class="fas fa-times-circle ico-tab-mini"></i>Reject</button>
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
                } else {
                    return "<span class='badge bg-danger ico-tab-left-mini'>" + status + "</span>"
                }
            }
        }

        
    </script>    
 @endpush