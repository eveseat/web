@extends('web::character.layouts.view', ['viewname' => 'loyalty-points', 'breadcrumb' => trans('web::seat.loyalty_points')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.calendar'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.loyalty_points') }}
      </h3>
      @if($character->refresh_token)
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.loyalty_points', 'label' => trans('web::seat.update_loyalty_points')])
        </div>
      </div>
      @endif
    </div>
    <div class="card-body">

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush