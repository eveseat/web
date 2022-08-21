@extends('web::layouts.character', ['viewname' => 'transactions', 'breadcrumb' => trans('web::seat.wallet_transactions')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
          <div class="card-header d-flex align-items-center">
              <div class="col-auto me-5">
                  <h3 class="card-title">{{ trans('web::seat.wallet_transactions') }}</h3>
              </div>
              <div class="col-6">
                  @include('web::character.includes.dt-character-selector')
              </div>
              <div class="ms-auto">
                  @if($character->refresh_token)
                      @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.transactions', 'label' => trans('web::seat.update_transactions')])
                  @endif
              </div>
          </div>

          {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
      </div>

    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')
@endpush
