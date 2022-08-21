@extends('web::layouts.character', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
      <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
              <h3 class="card-title">{{ trans('web::seat.market') }}</h3>
          </div>
          <div class="col-6">
              @include('web::character.includes.dt-character-selector')
          </div>
          <div class="ms-auto">
              @if($character->refresh_token)
                  @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.orders', 'label' => trans('web::seat.update_market')])
              @endif
          </div>
      </div>

      @include('web::common.markets.buttons.filters')

      {!! $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) !!}
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
    $(document).ready(function() {
        $('#dt-character-selector')
            .select2()
            .on('change', function () {
                window.LaravelDataTables['dataTableBuilder'].ajax.reload();
            });
    });
  </script>
@endpush
