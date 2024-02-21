<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="incentives-year-end-details-table">
            <thead>
            <tr>
                <th>Incentivesid</th>
                <th>Employeeid</th>
                <th>Fourteenthmonthpay</th>
                <th>Thirteenthmonthdifferential</th>
                <th>Cashgift</th>
                <th>Vacationleave</th>
                <th>Sickleave</th>
                <th>Loyaltyaward</th>
                <th>Subtotal</th>
                <th>Arothers</th>
                <th>Bempc</th>
                <th>Withholdingtaxes</th>
                <th>Netpay</th>
                <th>Userid</th>
                <th>Employeetype</th>
                <th>Department</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($incentivesYearEndDetails as $incentivesYearEndDetails)
                <tr>
                    <td>{{ $incentivesYearEndDetails->IncentivesId }}</td>
                    <td>{{ $incentivesYearEndDetails->EmployeeId }}</td>
                    <td>{{ $incentivesYearEndDetails->FourteenthMonthPay }}</td>
                    <td>{{ $incentivesYearEndDetails->ThirteenthMonthDifferential }}</td>
                    <td>{{ $incentivesYearEndDetails->CashGift }}</td>
                    <td>{{ $incentivesYearEndDetails->VacationLeave }}</td>
                    <td>{{ $incentivesYearEndDetails->SickLeave }}</td>
                    <td>{{ $incentivesYearEndDetails->LoyaltyAward }}</td>
                    <td>{{ $incentivesYearEndDetails->SubTotal }}</td>
                    <td>{{ $incentivesYearEndDetails->AROthers }}</td>
                    <td>{{ $incentivesYearEndDetails->BEMPC }}</td>
                    <td>{{ $incentivesYearEndDetails->WithholdingTaxes }}</td>
                    <td>{{ $incentivesYearEndDetails->NetPay }}</td>
                    <td>{{ $incentivesYearEndDetails->UserId }}</td>
                    <td>{{ $incentivesYearEndDetails->EmployeeType }}</td>
                    <td>{{ $incentivesYearEndDetails->Department }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['incentivesYearEndDetails.destroy', $incentivesYearEndDetails->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('incentivesYearEndDetails.show', [$incentivesYearEndDetails->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('incentivesYearEndDetails.edit', [$incentivesYearEndDetails->id]) }}"
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
            @include('adminlte-templates::common.paginate', ['records' => $incentivesYearEndDetails])
        </div>
    </div>
</div>
