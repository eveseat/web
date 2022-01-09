@extends('web::layouts.corporation', ['viewname' => 'contacts', 'breadcrumb' => trans('web::seat.contacts')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contacts'))

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.contacts') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.contacts', 'label' => trans('web::seat.update_contacts')])
        </div>
      </div>
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
