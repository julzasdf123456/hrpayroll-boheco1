@php
    use App\Models\Employees;
@endphp

<div class="modal fade" id="modal-leave-individual-logs" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4>
                        <span id="logs-title">Leave History</span>
                        <div id="leave-loader" class="spinner-border text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h4>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered table-sm" id="all-leave-table">
                    <thead>
                        <th style="width: 32px;"></th>
                        <th>Date Filed</th>
                        <th>Reason</th>
                        <th class="text-right"># of Days</th>
                        <th class="text-center">Status</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
 </div>
 
 @push('page_scripts')
    <script>
        $(document).ready(function() {
            
        })
    </script>    
 @endpush