<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="incentives-annual-projections-table">
            <thead>
            <tr>
                <th>Year</th>
                <th>Incentive</th>
                <th>Incentivedescription</th>
                <th>Amount</th>
                <th>Istaxable</th>
                <th>Maxuntaxableamount</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($incentivesAnnualProjections as $incentivesAnnualProjection)
                <tr>
                    <td>{{ $incentivesAnnualProjection->Year }}</td>
                    <td>{{ $incentivesAnnualProjection->Incentive }}</td>
                    <td>{{ $incentivesAnnualProjection->IncentiveDescription }}</td>
                    <td>{{ $incentivesAnnualProjection->Amount }}</td>
                    <td>{{ $incentivesAnnualProjection->IsTaxable }}</td>
                    <td>{{ $incentivesAnnualProjection->MaxUntaxableAmount }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['incentivesAnnualProjections.destroy', $incentivesAnnualProjection->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('incentivesAnnualProjections.show', [$incentivesAnnualProjection->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('incentivesAnnualProjections.edit', [$incentivesAnnualProjection->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $incentivesAnnualProjections])
        </div>
    </div>
</div>
