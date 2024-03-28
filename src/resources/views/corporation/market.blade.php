@extends('web::corporation.layouts.view', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.market') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.orders', 'label' => trans('web::seat.update_market')])
        </div>
      </div>
    </div>
    <div class="card-body">

      @include('web::common.markets.buttons.filters')

      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

  {!! $dataTable->scripts() !!}
@endpush
