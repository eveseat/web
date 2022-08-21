@extends('web::layouts.character', ['viewname' => 'mining-ledger', 'breadcrumb' => trans('web::seat.mining')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mining'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')
  <div class="card">
      <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
              <h3 class="card-title">{{ trans('web::seat.mining') }}</h3>
          </div>
          <div class="col-6">
              @include('web::character.includes.dt-character-selector')
          </div>
          <div class="ms-auto">
              @if($character->refresh_token)
                  @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.mining', 'label' => trans('web::seat.update_mining')])
              @endif
          </div>
      </div>

      {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

  @include('web::common.minings.modals.details.details')
@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
      $(document).ready(function() {
          $('#mining-detail').on('show.bs.modal', function (e) {
              var body = $(e.target).find('.modal-body');
              body.html('Loading...');

              $.ajax($(e.relatedTarget).data('url'))
                  .done(function (data) {
                      body.html(data);
                      $("[data-bs-toggle=tooltip]").tooltip();
                  });
          });
      });
  </script>
@endpush
