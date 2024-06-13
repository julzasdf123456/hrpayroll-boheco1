<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead>
            <th>Date Filed</th>
            <th>Destination</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Dates</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($travelOrders as $item)
                <tr id="to-employee-{{ $item->TOEmployeeId }}">
                    <td class="v-align">{{ date('M d, Y', strtotime($item->DateFiled)) }}</td>
                    <td class="v-align">{{ $item->Destination }}</td>
                    <td class="v-align">{{ $item->Purpose }}</td>
                    <td class="v-align" class="v-align"><span class="badge bg-info">{{ $item->Status != null ? $item->Status : 'PENDING' }}</span></td>
                    @php
                        $days = explode(",", $item->Days);
                    @endphp
                    <td class="v-align">
                        <ul>
                            @foreach ($days as $itemx)
                                @if (strlen($itemx) > 2)
                                    <li>{{ date('M d, Y (D)', strtotime($itemx)) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </td>
                    <td class="v-align text-right">
                        <button onclick="deleteTo(`{{ $item->TOEmployeeId }}`)" class="btn btn-link text-danger"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function deleteTo(id) {
            Swal.fire({
                title: "Remove Employee from TO?",
                text: "Remove this employee from this travel order? NOTE that your are not deleting the travel order itself.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3273a8",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/travelOrderEmployees') }}/" + id,
                        type : "DELETE",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Employee removed from TO'
                            })
                            $('#to-employee-' + id).remove()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting employee from TO.'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush