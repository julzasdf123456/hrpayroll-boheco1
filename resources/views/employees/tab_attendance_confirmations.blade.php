<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <th>Reason</th>
                    <th>AM In</th>
                    <th>AM Out</th>
                    <th>PM In</th>
                    <th>PM Out</th>
                    <th>OT In</th>
                    <th>OT Out</th>
                    <th>Entry Date</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($attendanceConfirmations as $item)
                        <tr>
                            <td>{{ $item->Reason }}</td>
                            <td>{{ $item->AMIn != null ? date('D, M d, Y h:i A', strtotime($item->AMIn)) : '-' }}</td>
                            <td>{{ $item->AMOut != null ? date('D, M d, Y h:i A', strtotime($item->AMOut)) : '-' }}</td>
                            <td>{{ $item->PMIn != null ? date('D, M d, Y h:i A', strtotime($item->PMIn)) : '-' }}</td>
                            <td>{{ $item->PMOut != null ? date('D, M d, Y h:i A', strtotime($item->PMOut)) : '-' }}</td>
                            <td>{{ $item->OTIn != null ? date('D, M d, Y h:i A', strtotime($item->OTIn)) : '-' }}</td>
                            <td>{{ $item->OTOut != null ? date('D, M d, Y h:i A', strtotime($item->OTOut)) : '-' }}</td>
                            <td>{{ $item->created_at != null ? date('M d, Y', strtotime($item->created_at)) : '' }}</td>
                            <td class="v-align text-right">
                                <button onclick="deleteATConf(`{{ $item->id }}`)" class="btn btn-sm text-danger btn-link"><i class="fas fa-trash"></i></button>
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
        function deleteATConf(id) {
            Swal.fire({
                title: "Remove Attendance Confirmation?",
                text : 'This cannot be undone but you can always re-add this. Proceed with caution.',
                showCancelButton: true,
                confirmButtonText: "Yes",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/attendanceConfirmations') }}/" + id,
                        type : "DELETE",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Attendance confirmation deleted!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error deleting attendance confirmation!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush