@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Update {{ Employees::getMergeName($employee) }}' Contributions</h4>
            </div>
            <div class="col-sm-6">
                <a class="btn btn-default float-right"
                   href="{{ route('employees.show', [$employee->id]) }}">
                    Back
                </a>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-4 col-md-5">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <span class="card-title">Add ID and Contributions</span>
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'professionalIDs.store']) !!}
                    <input type="hidden" value="{{ $employee->id }}" name="EmployeeId">
                    <div class="form-group">
                        <label for="ranking-select">Entity</label>
                        <select class="form-control" id="ranking-select" name="Entity">
                            <option value="Pag-Ibig">Pag-Ibig</option>
                            <option value="SSS">SSS</option>
                            <option value="GSIS">GSIS</option>
                            <option value="PhilHealth">PhilHealth</option>
                            <option value="TAX">TIN/Tax Deduction</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id">ID Number</label>
                        <input type="text" class="form-control" name="EntityId" id="id">
                    </div>

                    <div class="form-group">
                        <label for="contribution">Contribution/Deductions</label>
                        <input type="number" class="form-control" step="any" name="ContributionAmount" id="contribution">
                    </div>

                    {!! Form::submit('Add', ['class' => 'btn btn-info float-right']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Current Contributions</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-borderless">
                    <thead>
                        <th>Entity</th>
                        <th>ID</th>
                        <th>Contribution/Deductions</th>
                        <th width="30px"></th>
                    </thead>
                    <tbody>
                        @if ($ids != null)
                            @foreach ($ids as $item)
                                <tr>
                                    <td>{{ $item->Entity }}</td>
                                    <td>{{ $item->EntityId }}</td>
                                    <td>{{ $item->ContributionAmount }}</td>
                                    <td>
                                        {!! Form::open(['route' => ['professionalIDs.destroy', $item->id], 'method' => 'delete']) !!}
                                            {!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>                                
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection