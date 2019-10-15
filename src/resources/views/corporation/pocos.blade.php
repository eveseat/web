@extends('web::corporation.layouts.view', ['viewname' => 'pocos', 'breadcrumb' => trans('web::seat.pocos')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.pocos'))

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.pocos') }}</h3>
    </div>
    <div class="card-body">

      {{ $dataTable->table() }}

    </div>

  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush
