@extends('web::layouts.corporation', ['viewname' => 'assets', 'breadcrumb' => trans('web::seat.assets')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.assets') }}</h3>
      </div>
      <div class="ms-auto">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.assets', 'label' => trans('web::seat.update_assets')])
      </div>
    </div>
    {!! $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) !!}
  </div>

  @include('web::common.assets.modals.fitting.fitting')
  @include('web::common.assets.modals.container.container')

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush
