@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Incentives & Bonuses</h4>
            </div>
        </div>
    </div>
</section>

<div class="row px-3">
    @foreach ($data as $item)
        <div class="col-lg-4">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title"><strong>{{ $item->Year }}</strong></span>
                </div>
                <div class="card-body">
                    @php
                        $incentives = $item->Data;
                    @endphp
                    <table class="table table-hover table-borderless">
                        <tbody>
                            @foreach ($incentives as $incentive)
                                <tr style="cursor: pointer;" onclick="view(`{{ $incentive->id }}`)">
                                    <td>{{ $incentive->IncentiveName }}</td>
                                    <td class="text-right">
                                        <span class="badge bg-info">{{ $incentive->Status==null ? 'Pending' : $incentive->Status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection

@push('page_scripts')
    <script>
        function view(id) {
            window.location.href = "{{ url('/incentives/view-incentives') }}/" + id
        }
    </script>
@endpush
