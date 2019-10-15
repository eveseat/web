@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'log', 'breadcrumb' => trans_choice('web::seat.log', 1)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.log', 1))

@section('security_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.roles_change_log') }}</h3>
    </div>
    <div class="card-body">
      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
