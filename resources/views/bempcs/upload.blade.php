@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Upload BEMPC Deductions Excel File (.xlsx, .xls)</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-lg-6 offset-lg-3 col-md-12">
        <div class="card shadow-soft">
            <form action="{{ route('bempcs.process-upload') }}" method="post" enctype="multipart/form-data">
            <div class="card-body">
                @csrf
                <span class="text-muted">What's this for?</span>
                <div class="input-group-radio">
                    <input type="radio" id="Bonus" name="For" value="Bonus" class="custom-radio" required>
                    <label for="Bonus" class="custom-radio-label">Bonus</label>

                    <input type="radio" id="Payroll" name="For" value="Payroll" class="custom-radio" required>
                    <label for="Payroll" class="custom-radio-label">Payroll</label>
                </div>

                <div id="bonus" class="gone" style="margin-top: 16px;">
                    <span class="text-muted">Select Bonus</span>
                    <select name="Incentives" id="Incentives" class="form-control" style="margin-top: 10px;">
                        <option value="13th Month Pay - 1st Half">13th Month Pay - 1st Half</option>
                        <option value="13th Month Pay - 2nd Half">13th Month Pay - 2nd Half</option>
                        @foreach ($bonuses as $item)
                            <option value="{{ $item->Incentive }}">{{ $item->Incentive }}</option>
                        @endforeach
                        <option value="Year-end Incentives">Year-end Incentives</option>
                    </select>
                    <br>
                    <span class="text-muted">In what releasing mode?</span>
                    <div class="input-group-radio">
                        <input type="radio" id="Partial" name="ReleasingType" value="Partial" class="custom-radio" required>
                        <label for="Partial" class="custom-radio-label">Partial</label>

                        <input type="radio" id="Full" name="ReleasingType" value="Full" class="custom-radio" required>
                        <label for="Full" class="custom-radio-label">Full</label>
                    </div>
                </div>

                <div id="payroll" class="gone" style="margin-top: 16px;">
                    <span class="text-muted">Select Payroll Sched</span>
                    <select name="PayrollSchedule" id="PayrollSchedule" class="form-control" style="margin-top: 10px;">
                        <option value="{{ date('Y-m-15') }}">{{ date('F 15, Y') }}</option>
                        <option value="{{ date('Y-m-d', strtotime('last day of this month')) }}">{{ date('F d, Y', strtotime('last day of this month')) }}</option>
                    </select>
                </div>

                <div class="upload-jumbotron">
                    <input id="select-file" type="file" name="file" accept=".xls,.xlsx" required>
                    <h4 id="selected-file-name" class="text-muted">Click Here to Select File</h4>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right" style="margin-bottom: 10px;">Submit Upload <i class="fas fa-upload ico-tab-mini-left"></i></button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('input[name=For]').on('change', function() {
                var value = this.value
                if (value === 'Bonus') {
                    $('#bonus').removeClass('gone')
                    $('#payroll').addClass('gone')
                    $('#Partial').attr('checked', true)
                    $('#Full').removeAttr('checked')
                } else {
                    const radioButtons = document.querySelectorAll('input[name="ReleasingType"]');
                    radioButtons.forEach(function(radioButton) {
                        radioButton.checked = false;
                    });

                    $('#bonus').addClass('gone')
                    $('#payroll').removeClass('gone')
                    $('#Partial').removeAttr('checked')
                    $('#Full').removeAttr('checked')
                }
            })

            $('#Incentives').on('change', function() {
                $('#Partial').removeAttr('checked')
                $('#Full').removeAttr('checked')
                if(this.value === '13th Month Pay - 1st Half') {
                    $('#Partial').attr('checked', true)
                    $('#Full').removeAttr('checked')
                } else if (this.value === '13th Month Pay - 2nd Half' | this.value === 'Year-end Incentives') {
                    $('#Full').attr('checked', true)
                    $('#Partial').removeAttr('checked')
                }
            })

            $('.upload-jumbotron').on('click', function() {
                $('#select-file')[0].click()
            })

            $('#select-file').on('change', function() {
                $('#selected-file-name').html('<i class="fas fa-file-excel ico-tab-mini"></i>' + $(this)[0].files[0].name)
            });
        })
    </script>
@endpush
