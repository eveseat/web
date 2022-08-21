@extends('web::layouts.alliance', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_description', trans_choice('web::seat.alliance', 1) . ' ' . trans('web::seat.tracking'))

@section('alliance_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.tracking') }}</h3>
      </div>
      <div class="ms-auto">
        @if ($alliance->members->count() > $alliance->corporations->count())
          <div class="input-group input-group-sm">
            <span class="text-warning">
              <i class="fas fa-exclamation-triangle" data-bs-toggle="tooltip" title="SeAT is missing {{ $alliance->members->count() - $alliance->corporations->count() }} member corporations. These will not appear in the table below."></i>
            </span>
          </div>
        @endif
      </div>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
