@extends('web::layouts.corporation', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.market') }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.orders', 'label' => trans('web::seat.update_market')])
      </div>
    </div>

    @include('web::common.markets.buttons.filters')

    {!! $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) !!}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
