@extends('web::layouts.corporation', ['viewname' => 'blueprint', 'breadcrumb' => trans('web::seat.blueprint')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.blueprint'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.blueprint') }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.blueprints', 'label' => trans('web::seat.update_blueprints')])
      </div>
    </div>

    @include('web::common.blueprints.buttons.filters')

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
