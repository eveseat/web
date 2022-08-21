@extends('web::layouts.corporation', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.tracking') }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.members_tracking', 'label' => trans('web::seat.update_members_tracking')])
      </div>
    </div>

    <div class="btn-group d-flex">
      <button type="button"  data-filter-field="type" data-filter-value="valid" class="btn btn-square btn-outline-success d-sm-inline-block dt-filters active">
        <i class="fas fa-check-circle"></i>
        {{ trans_choice('web::seat.valid_token', 2) }}
      </button>
      <button type="button" data-filter-field="type" data-filter-value="invalid" class="btn btn-square btn-outline-danger d-sm-inline-block dt-filters active">
        <i class="fas fa-times-circle"></i>
        {{ trans_choice('web::seat.invalid_token', 2) }}
      </button>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
