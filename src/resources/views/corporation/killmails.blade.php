@extends('web::layouts.corporation', ['viewname' => 'killmails', 'breadcrumb' => trans('web::seat.killmails')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.killmails') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.killmails', 'label' => trans('web::seat.update_killmails')])
        </div>
      </div>
    </div>
    <div class="card-body">
      {{ $dataTable->table() }}
    </div>
  </div>

  @include('web::common.killmails.modals.show.show')

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
