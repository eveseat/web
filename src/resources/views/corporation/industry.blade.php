@extends('web::corporation.layouts.view', ['viewname' => 'industry', 'breadcrumb' => trans('web::seat.industry')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.industry'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.industry') }}</h3>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <div class="btn-group d-flex">
          <button type="button" id="dt-filters-running" data-filter="active" class="btn btn-primary dt-filters-status active">
            <i class="fas fa-play"></i>
            Running
          </button>
          <button type="button" id="dt-filters-paused" data-filter="paused" class="btn btn-warning dt-filters-status">
            <i class="fas fa-pause"></i>
            Paused
          </button>
          <button type="button" id="dt-filters-completed" data-filter="ready" class="btn btn-success dt-filters-status">
            <i class="fas fa-check"></i>
              Completed
          </button>
          <button type="button" id="dt-filters-cancelled" data-filter="cancelled" class="btn btn-danger dt-filters-status">
            <i class="fas fa-ban"></i>
            Cancelled
          </button>
          <button type="button" id="dt-filters-delivered" data-filter="delivered" class="btn btn-secondary dt-filters-status">
            <i class="fas fa-history"></i>
            Delivered
          </button>
        </div>
      </div>
      <div class="mb-3">
        <div class="btn-group d-flex">
          <button type="button" id="dt-filters-manufacturing" data-filter="1" class="btn btn-light dt-filters-activity active">
            <i class="fas fa-industry"></i>
            Manufacturing
          </button>
          <button type="button" id="dt-filters-te" data-filter="3" class="btn btn-light dt-filters-activity active">
            <i class="fas fa-hourglass-half"></i>
            Research TE
          </button>
          <button type="button" id="dt-filters-me" data-filter="4" class="btn btn-light dt-filters-activity active">
            <i class="fas fa-gem"></i>
            Research ME
          </button>
          <button type="button" id="dt-filters-copying" data-filter="5" class="btn btn-light dt-filters-activity active">
            <i class="fas fa-flask"></i>
            Copying
          </button>
          <button type="button" id="dt-filters-invention" data-filter="8" class="btn btn-light dt-filters-activity active">
            <i class="fas fa-microscope"></i>
            Invention
          </button>
          <button type="button" id="dt-filters-reaction" data-filter="11" class="btn btn-light dt-filters-activity active">
            <i class="fas fa-atom"></i>
            Reaction
          </button>
        </div>
      </div>
      {{ $dataTable->table() }}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
    $(document).ready(function() {
      $('#dt-filters-running, #dt-filters-paused, #dt-filters-completed, #dt-filters-cancelled, #dt-filters-delivered, #dt-filters-manufacturing, #dt-filters-te, #dt-filters-me, #dt-filters-copying, #dt-filters-invention, #dt-filters-reaction')
          .on('click', function () {
              $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
              window.LaravelDataTables['dataTableBuilder'].ajax.reload();
          });
    });
  </script>
@endpush
