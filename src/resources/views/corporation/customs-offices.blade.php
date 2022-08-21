@extends('web::layouts.corporation', ['viewname' => 'customs-offices', 'breadcrumb' => trans('web::seat.customs-offices')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.customs-offices'))

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.customs-offices') }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.customs_offices', 'label' => trans('web::seat.update_customs_offices')])
      </div>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush
