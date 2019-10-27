@extends('web::corporation.layouts.view', ['viewname' => 'contacts', 'breadcrumb' => trans('web::seat.contacts')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contacts'))

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.contacts') }}</h3>
    </div>
    <div class="card-body">

      @include('web::common.contacts.buttons.filters')

      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
