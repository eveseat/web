@extends('web::corporation.layouts.view', ['viewname' => 'customs-offices', 'breadcrumb' => trans('web::seat.customs-offices')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.customs-offices'))

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.customs-offices') }}</h3>
    </div>
    <div class="card-body">

      {{ $dataTable->table() }}

    </div>

  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush
