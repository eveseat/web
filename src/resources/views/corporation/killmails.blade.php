@extends('web::layouts.corporation', ['viewname' => 'killmails', 'breadcrumb' => trans('web::seat.killmails')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.killmails') }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.killmails', 'label' => trans('web::seat.update_killmails')])
      </div>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

  @include('web::common.killmails.modals.show.show')

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
