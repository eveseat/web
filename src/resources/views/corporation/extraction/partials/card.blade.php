<div class="col-md-4">
  <div class="info-box @if($column->moon->extraction->isReady()) bg-gradient-success @else bg-gradient-info @endif">
    <span class="info-box-icon">
      <i class="@if($column->moon->extraction->isReady()) fas fa-recycle @else far fa-gem @endif"></i>
    </span>
    <div class="info-box-content">
      <span class="info-box-text">
        @if(! is_null($column->moon->extraction->structure))
        {{ $column->moon->extraction->structure->name }}
        @else
        {{ sprintf('%s %s', trans('web::seat.unknown'), trans_choice('web::seat.structure', 1)) }}
        @endif
      </span>
      <span class="info-box-number">
        @if($column->moon->extraction->isReady()) Active @else {{ human_diff($column->moon->extraction->chunk_arrival_time) }} @endif
      </span>
      @if($column->moon->extraction->isReady())
      <div class="progress">
        <div class="progress-bar" style="width: {{ round(carbon($column->moon->extraction->chunk_arrival_time)->diffInSeconds(carbon()) / $column->moon->extraction::THEORETICAL_DEPLETION_COUNTDOWN * 100, 0) }}%"></div>
      </div>
      @else
      <div class="progress">
        <div class="progress-bar" style="width: {{ round(carbon($column->moon->extraction->extraction_start_time)->diffInSeconds(carbon()) / carbon($column->moon->extraction->chunk_arrival_time)->diffInSeconds($column->moon->extraction->extraction_start_time) * 100, 0) }}%"></div>
      </div>
      @endif
      <span class="progress-description">Chunk age: {{number_format(carbon($column->moon->extraction->chunk_arrival_time)->diffInDays($column->moon->extraction->extraction_start_time), 0)}} days</span>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      @include('web::corporation.extraction.partials.card-body')
    </div>
    @can('moon.manage_moon_reports')
      <div class="card-footer">
        <button type="button" data-toggle="modal" data-target="#moon-import" class="btn btn-sm btn-link float-right" aria-label="Settings">
          <i class="fas fa-cogs"></i> Settings
        </button>
      </div>
    @endcan
  </div>
</div>
