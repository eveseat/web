<div class="col-md-4">
  <div class="info-box @if($column->isReady()) bg-gradient-success @else bg-gradient-info @endif">
    <span class="info-box-icon">
      <i class="@if($column->isReady()) far fa-gem @else far fa-hourglass @endif"></i>
    </span>
    <div class="info-box-content">
      <span class="info-box-number">
        @if($column->isReady()) Active @else {{ human_diff($column->chunk_arrival_time) }} @endif
      </span>
      @if($column->isReady())
      <div class="progress">
        <div class="progress-bar" style="width: {{ round(carbon($column->chunk_arrival_time)->diffInSeconds(carbon()) / $column::THEORETICAL_DEPLETION_COUNTDOWN * 100, 0) }}%"></div>
      </div>
      @else
      <div class="progress">
        <div class="progress-bar" style="width: {{ round(carbon($column->extraction_start_time)->diffInSeconds(carbon()) / carbon($column->chunk_arrival_time)->diffInSeconds($column->extraction_start_time) * 100, 0) }}%"></div>
      </div>
      @endif
      <span class="progress-description">{{ trans('web::seat.chunk_age') }}: {{number_format(carbon($column->chunk_arrival_time)->diffInDays($column->extraction_start_time), 0)}} days</span>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      @include('web::corporation.extraction.partials.card-body')
    </div>
  </div>
</div>
