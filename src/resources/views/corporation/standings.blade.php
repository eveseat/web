@extends('web::layouts.corporation', ['viewname' => 'standings', 'breadcrumb' => trans_choice('web::seat.standings', 0)])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.standings', 0))

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans_choice('web::seat.standings', 2) }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.standings', 'label' => trans('web::seat.update_standings')])
      </div>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush