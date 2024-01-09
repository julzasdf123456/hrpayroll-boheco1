<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <span class="card-title">Contributions/Deductions</span>

                @canany('employees update')
                <div class="card-tools">
                    <a href="{{ route('employees.update-third-party-ids', [$employees->id]) }}" class="btn btn-tool" title="Update 3rd Party IDs"><i class="fas fa-pen"></i></a>
                </div>
                @endcanany
            </div>
            <div class="card-body table-responsive px-0"></div>
            <table class="table table-hover table-borderless">
                <thead>
                    <th>Entity</th>
                    <th class="text-right">Entity ID</th>
                    <th class="text-right">Contribution</th>
                </thead>
                <tbody>
                    @if ($ids != null)
                        @foreach ($ids as $item)
                            <tr>
                                <th>{{ $item->Entity }}</th>
                                <td class="text-right">{{ $item->EntityId }}</td>
                                <td class="text-right">{{ $item->ContributionAmount }}</td>
                            </tr>                                
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>