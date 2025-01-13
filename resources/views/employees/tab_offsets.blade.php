<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-bordered">
                <thead>
                    <th>Date of Duty</th>
                    <th>Duty Purpose</th>
                    <th>Date of Offset</th>
                    <th>Offset Reason</th>
                    <th>Status</th>
                    <th>Entry Date</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($offsets as $item)
                        <tr>
                            <td>{{ date('D, M d, Y', strtotime($item->DateOfDuty)) }}</td>
                            <td>{{ $item->PurposeOfDuty }}</td>
                            <td>{{ date('D, M d, Y', strtotime($item->DateOfOffset)) }}</td>
                            <td>{{ $item->OffsetReason }}</td>
                            <td>
                                @if ($item->Status === 'APPROVED')
                                    <span class="badge bg-success">{{ $item->Status }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $item->Status }}</span>
                                @endif
                            </td>
                            <td>{{ date('D, M d, Y', strtotime($item->created_at)) }}</td>
                            <td class="text-right v-align">
                                <button onclick="deleteOffset(`{{ $item->id }}`)" class="btn btn-sm text-danger btn-link"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('page_scripts')
    <script>
        function deleteOffset(id) {
            Swal.fire({
                title: "Remove this Offset?",
                text : 'This cannot be undone but you can always re-add this. Proceed with caution.',
                showCancelButton: true,
                confirmButtonText: "Yes",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/offsetApplications') }}/" + id,
                        type : "DELETE",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Offset deleted!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting offset!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush