<div class="col-md-4">
  <div class="info-box @if($extraction->isReady()) bg-green @else bg-blue-gradient @endif">
    <span class="info-box-icon">
      <i class="fa @if($extraction->isReady()) fa-recycle @else fa-diamond @endif"></i>
    </span>
    <div class="info-box-content">
      <span class="info-box-text">
        @if(! is_null($extraction->structure) && ! is_null($extraction->structure->info))
        {{ $extraction->structure->info->name }}
        @else
        {{ sprintf('%s %s', trans('web::seat.unknown'), trans_choice('web::seat.structure', 1)) }}
        @endif
      </span>
      <span class="info-box-number">
        @if($extraction->isReady()) Active @else {{ human_diff($extraction->chunk_arrival_time) }} @endif
      </span>
      @if($extraction->isReady())
      <div class="progress">
        <div class="progress-bar" style="width: {{ round(carbon($extraction->chunk_arrival_time)->diffInSeconds(carbon()) / $extraction::THEORETICAL_DEPLETION_COUNTDOWN * 100, 0) }}%"></div>
      </div>
      @else
      <div class="progress">
        <div class="progress-bar" style="width: {{ round(carbon($extraction->extraction_start_time)->diffInSeconds(carbon()) / carbon($extraction->chunk_arrival_time)->diffInSeconds($extraction->extraction_start_time) * 100, 0) }}%"></div>
      </div>
      @endif
      <span class="progress-description">Chunk age: {{number(carbon($extraction->chunk_arrival_time)->diffInDays($extraction->extraction_start_time), 0)}} days</span>
    </div>
  </div>
  <div class="box no-border">
    <div class="box-body">
      @include('web::corporation.extraction.partials.card-body')
    </div>
    <div class="box-footer">
      <button type="button" data-toggle="modal" data-target="#moon-modal" class="btn btn-xs btn-link pull-right" aria-label="Settings">
        <i class="fa fa-cogs"></i> Settings
      </button>
    </div>
  </div>
</div>
