<div class="modal fade" id="modal-fetch-from-bio" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        Fetch Data from Biometric Device
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
                <table class="table table-sm table-bordered table-hover" id="bio-table">
                    <thead>
                        <th>Date</th>
                        <th>Time</th>
                        <th></th>
                    </thead>
                    <tbody></tbody>
                </table>               
            </div>
        </div>
    </div>
 </div>

 @push('page_scripts')
     <script>
        $(document).ready(function() {

        })

        function fetchFromBio(index, type, employeeBiometricsId, date) {
            $('#loader').removeClass('gone')
            $('#modal-fetch-from-bio').modal('show')
            $('#bio-table tbody tr').remove()
            $.ajax({
                url : "{{ route('attendanceDatas.fetch-by-employee-and-date') }}",
                type : 'GET',
                data : {
                    EmployeeBiometricsId : employeeBiometricsId,
                    Date : date,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        $.each(res, function(i, element) {
                            $('#bio-table tbody').append(addTableRow(res[i]['Timestamp'], type, index))
                        })
                    }
                    $('#loader').addClass('gone')
                },  
                error : function(err) {
                    $('#loader').addClass('gone')
                    Toast.fire({
                        icon : 'error',
                        text : 'Error fetching biometric data'
                    })
                }
            })
        }

        function addTableRow(timestamp, type, index) {
            // INSERT TIME IS AT overtimes.create
            return "<tr>" +
                        "<td>" + moment(timestamp).format('MMM DD, YYYY') + "</td>" +
                        "<td>" + moment(timestamp).format('h:m A') + "</td>" +
                        "<td><button onclick='insertTime(`" + timestamp + "`, `" + type + "`, `" + index + "`)' class='btn btn-xs btn-success float-right'><i class='fas fa-check-circle'></i></button></td>" +
                    "</tr>"
        }
     </script>
 @endpush