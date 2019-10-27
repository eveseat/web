@extends('web::corporation.layouts.view', ['viewname' => 'industry', 'breadcrumb' => trans('web::seat.industry')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.industry'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.industry') }}</h3>
    </div>
    <div class="card-body">

      @include('web::common.industries.buttons.filters')

      {{ $dataTable->table() }}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
