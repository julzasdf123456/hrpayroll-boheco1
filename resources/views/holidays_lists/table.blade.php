<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="holidays-lists-table">
            <thead>
            <tr>
                <th>Holidaydate</th>
                <th>Holiday</th>
                <th>Memonumber</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($holidaysLists as $holidaysList)
                <tr>
                    <td>{{ date('F d, Y', strtotime($holidaysList->HolidayDate)) }}</td>
                    <td>{{ $holidaysList->Holiday }}</td>
                    <td>{{ $holidaysList->MemoNumber }}</td>
                    <td>{{ $holidaysList->Notes }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['holidaysLists.destroy', $holidaysList->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('holidaysLists.show', [$holidaysList->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('holidaysLists.edit', [$holidaysList->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $holidaysLists])
        </div>
    </div>
</div>
