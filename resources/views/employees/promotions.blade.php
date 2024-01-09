@if ($employeeDesignations != null)
<div class="timeline timeline-inverse">
  @foreach ($employeeDesignations as $item)

    <!-- timeline time label -->
    <div class="time-label">
      <span class="bg-success">
        {{ $item->Position }}
      </span>
    </div>
    <!-- /.timeline-label -->
    <!-- timeline item -->
    <div>
      <i class="fas fa-upload bg-primary"></i>

      <div class="timeline-item">
        <span class="time"><i class="far fa-clock"></i> Date Promoted: {{ date('F d, Y', strtotime($item->created_at)) }}</span>

        <h3 class="timeline-header"><a href="#">{{ $item->Status }}</a> </h3>

        <div class="timeline-body">
          {{ $item->Description }}
          <br>
          <span>Date Started: <strong>{{ $item->DateStarted == null ? '' : date('F d, Y', strtotime($item->DateStarted)) }}</strong></span><br>
          <span>Date Ended: <strong>{{ $item->DateEnd == null ? '' : date('F d, Y', strtotime($item->DateEnd)) }}</strong></span><br>
          <span>Basic Salary: <strong>{{ $item->SalaryAmount == null ? '' : number_format($item->SalaryAmount, 2) }}</strong></span><br>
          <span>Add-Ons: <strong>{{ $item->SalaryAddOns == null ? '' : number_format($item->SalaryAddOns, 2) }}</strong></span>
        </div>
        @canany('employees update')
        <div class="timeline-footer">
          {!! Form::open(['route' => ['employeesDesignations.destroy', $item->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
              <a href="{{ route('employeesDesignations.edit', [$item->id]) }}" class="btn btn-primary btn-xs"><i class="fas fa-pen"></i></a>
              {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
        @endcanany
      </div>
    </div>
  @endforeach
  <!-- END timeline item -->
</div>
@else
    
@endif
