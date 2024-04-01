<div class="table-responsive">
    <table class="table table-hover" id="positions-table">
        <thead>
            <tr>
                <th>Position</th>
                <th>Description</th>
                <th>Level</th>
                <th>Immediate Super</th>
                <th>Department</th>
                {{-- <th>Basic Salary</th> --}}
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($positions as $position)
            <tr>
                <td>{{ $position->Position }}</td>
                <td>{{ $position->Description }}</td>
                <td>{{ $position->Level }}</td>
                <td>
                    <select class="custom-select select2" id="ParentPosition-{{ $position->id }}" onchange="changePosition(`{{ $position->id }}`)">
                        <option value="">-- Select --</option>
                        @foreach ($supers as $item)
                            <option value="{{ $item->id }}" {{ $position->ParentPositionId == $item->id ? 'selected' : '' }}>{{ $item->Position }}</option>
                        @endforeach
                    </select>
                </td>
                <td>{{ $position->Department }}</td>
                {{-- <td>{{ number_format($position->BasicSalary, 2) }}</td> --}}
                <td width="120">
                    {!! Form::open(['route' => ['positions.destroy', $position->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('positions.show', [$position->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('positions.edit', [$position->id]) }}" class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {{-- {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                    </div>
                    {!! Form::close() !!}
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

        function changePosition(id) {
            var selectedId = $('#ParentPosition-' + id).val()

            $.ajax({
                url : "{{ route('positions.update-super') }}",
                type : 'GET',
                data : {
                    PositionId : id,
                    SuperId : selectedId,
                },
                success : function(res) {
                    Toast.fire({
                        icon : 'success',
                        text : 'Immediate parent position updated!'
                    })
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error updating parent position'
                    })
                }
            })
        }
    </script>
@endpush
