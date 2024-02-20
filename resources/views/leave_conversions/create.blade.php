@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4>
                <span class="text-muted">Request for Cash Conversion of </span>Leave Credits
                </h4>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card shadow-none">
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <tbody>
                    <tr>
                        <td class="text-center">Employee</td>
                        <td class="text-center">Vacation</td>
                        <td class="text-center">Sick</td>
                    </tr>
                    <tr>
                        <td>
                            <select class="custom-select select2"  name="EmployeeId" id="EmployeeId" style="width: 100%;" required>
                                <option value="">-- Select --</option>
                                @foreach ($employees as $item)
                                    <option value="{{ $item->id }}" {{ Auth::user()->employee_id==$item->id ? 'selected' : '' }} bio-id="{{ $item->BiometricsUserId }}">{{ Employees::getMergeNameFormal($item) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input id="vacation" type="number" class="form-control text-right">
                        </td>
                        <td>
                            <input id="sick" type="number" class="form-control text-right">
                        </td>
                    </tr>
                </tbody>
            </table>

            <button onclick="addToQueue()" class="btn btn-primary btn-sm float-right" style="margin-top: 15px;"><i class="fas fa-plus-circle ico-tab-mini"></i>Add To Queue</button>
            </div>
        </div>
    </div>    
</div>
@endsection
