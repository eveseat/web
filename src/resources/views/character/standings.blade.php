@extends('web::layouts.character', ['viewname' => 'standings', 'breadcrumb' => trans_choice('web::seat.standings', 0)])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.standings', 0))

@section('character_content')

  <div class="card">
      <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
              <h3 class="card-title">{{ trans_choice('web::seat.standings', 2) }}</h3>
          </div>
          <div class="col-6">
              @include('web::character.includes.dt-character-selector')
          </div>
          <div class="ms-auto">
              @if($character->refresh_token)
                  @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.standings', 'label' => trans('web::seat.update_standings')])
              @endif
          </div>
      </div>

      {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush