@extends('web::layouts.character', ['viewname' => 'loyalty-points', 'breadcrumb' => trans('web::seat.loyalty_points')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.loyalty_points'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.loyalty_points') }}</h3>
      </div>
      <div class="col-6">
        @include('web::character.includes.dt-character-selector')
      </div>
      <div class="ms-auto">
        @if($character->refresh_token)
          @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.loyalty_points', 'label' => trans('web::seat.update_loyalty_points')])
        @endif
      </div>
    </div>

    {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}

  </div>

  @include('web::common.contracts.modals.details.details')

@stop

@push('javascript')
    {!! $dataTable->scripts() !!}
@endpush