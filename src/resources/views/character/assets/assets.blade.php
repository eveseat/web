@extends('web::layouts.character', ['viewname' => 'assets', 'breadcrumb' => trans('web::seat.assets')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header d-flex align-items-center">
      <div class="col-auto me-5">
        <h3 class="card-title">{{ trans('web::seat.assets') }}</h3>
      </div>
      <div class="col-6">
        @include('web::character.includes.dt-character-selector')
      </div>
      <div class="ms-auto">
        @if($character->refresh_token)
          @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.assets', 'label' => trans('web::seat.update_assets')])
        @endif
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