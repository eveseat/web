@extends('web::alliance.layouts.view', ['viewname' => 'contacts', 'breadcrumb' => trans('web::seat.contacts')])

@section('page_header', trans_choice('web::seat.alliance', 1) . ' ' . trans('web::seat.contacts'))

@section('alliance_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.contacts') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'alliance', 'entity' => $alliance->alliance_id, 'job' => 'alliance.contacts', 'label' => trans('web::seat.update_contacts')])
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
