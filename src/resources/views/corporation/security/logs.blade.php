@extends('web::layouts.corporation', ['viewname' => 'log', 'breadcrumb' => trans_choice('web::seat.log', 1)])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.log', 1))

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.roles_change_log') }}</h3>
      </div>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
