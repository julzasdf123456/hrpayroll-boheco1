@php
    use App\Models\Employees;
@endphp
@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Update {{ Employees::getMergeName($employee) }}'s Ranking</h4>
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
                <span class="card-title">Add Ranking</span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="ranking-select">Select Ranking</label>
                    <select class="form-control" id="ranking-select">
                        @if ($rankingsRep != null)
                            @foreach ($rankingsRep as $item)
                                <option value="{{ $item->id }}" points="{{ $item->Points }}">{{ $item->Type }} - {{ $item->RankingName }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="points">Points</label>
                    <input type="number" class="form-control" step="any" value="" id="points">
                </div>

                <button class="btn btn-info float-right" id="add-rank">Add Rank</button>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Current Rankings</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-hover table-borderless">
                    <thead>
                        <th>Rank</th>
                        <th width="10%">Points</th>
                        <th width="30px"></th>
                    </thead>
                    <tbody>
                        @if ($rankings != null)
                            @php
                                 $total = 0.0;
                            @endphp
                            @foreach ($rankings as $item)
                                @php
                                    $total += floatval($item->Points);
                                @endphp
                                <tr>
                                    <td>{{ $item->Type }} - {{ $item->RankingName }}</td>
                                    <td class="text-right">{{ $item->Points }}</td>
                                    <td>
                                        <button onclick="deleteRanking({{ $item->id }})" class="btn btn-sm btn-link text-danger"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>Total</th>
                                <th class="text-right">{{ number_format($total, 0) }}</th>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>

        function deleteRanking(id) {
            if (confirm("Are you sure you want to delete this ranking?")) {
                $.ajax({
                    url : '/rankings/' + id,
                    type : 'DELETE',
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id,
                    },
                    success : function(response) {
                        location.reload()
                    },
                    error : function(error) {
                        alert("Error deleting rank")
                    }
                })
            }
        }

        $(document).ready(function() {
            $('#points').val($("#ranking-select option:selected").attr("points"))

            $('#ranking-select').on('change', function() {
                $('#points').val($("#ranking-select option:selected").attr("points"))
            })

            $('#add-rank').on('click', function() {
                $.ajax({
                    url : '/employees/add-ranking',
                    type : 'POST',
                    data : {
                        _token : "{{ csrf_token() }}",
                        EmployeeId : "{{ $employee->id }}",
                        RankingRepositoryId : $('#ranking-select').val(),
                        Points : $('#points').val()
                    },
                    success : function(response) {
                        location.reload()
                    },
                    error : function(error) {
                        alert('An error occured while adding the ranking. Contact support for more.')
                    }
                })
            })
        })
    </script>
@endpush