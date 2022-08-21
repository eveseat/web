@extends('web::layouts.character', ['viewname' => 'fittings', 'breadcrumb' => trans('web::seat.fittings')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.fittings'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
      <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
              <h3 class="card-title">{{ trans('web::seat.fittings') }}</h3>
          </div>
          <div class="col-6">
              @include('web::character.includes.dt-character-selector')
          </div>
          <div class="ms-auto">
              @if($character->refresh_token)
                  @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.fittings', 'label' => trans('web::seat.update_fittings')])
              @endif
          </div>
      </div>

      {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
  </div>

  @include('web::common.fittings.modals.fitting.fitting')
  @include('web::common.fittings.modals.insurances.insurances')

@stop

@push('javascript')

  {{ $dataTable->scripts() }}

  <script>
      $('#fitting-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
              });
      });

      $('#insurances-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
              });
      });

      $(document).on('click', '.copy-fitting', function (e) {
          var buffer = $(this).data('export');

          $('body').append('<textarea id="copied-fitting"></textarea>');
          $('#copied-fitting').val(buffer);
          document.getElementById('copied-fitting').select();
          document.execCommand('copy');
          document.getElementById('copied-fitting').remove();

          $(this).attr('data-original-title', 'Copied !')
              .tooltip('show');

          $(this).attr('data-original-title', 'Copy to clipboard');
      });
  </script>

@endpush
