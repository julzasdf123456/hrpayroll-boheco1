<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead>
            <th>Date Filed</th>
            <th>Destination</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Dates</th>
        </thead>
        <tbody>
            @foreach ($travelOrders as $item)
                <tr>
                    <td>{{ date('M d, Y', strtotime($item->DateFiled)) }}</td>
                    <td>{{ $item->Destination }}</td>
                    <td>{{ $item->Purpose }}</td>
                    <td class="v-align"><span class="badge bg-info">{{ $item->Status }}</span></td>
                    @php
                        $days = explode(",", $item->Days);
                    @endphp
                    <td>
                        <ul>
                            @foreach ($days as $item)
                                @if (strlen($item) > 2)
                                    <li>{{ date('M d, Y (D)', strtotime($item)) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>