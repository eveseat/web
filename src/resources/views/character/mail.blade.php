@extends('web::layouts.character', ['viewname' => 'mail', 'breadcrumb' => trans('web::seat.mail')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
      <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
              <h3 class="card-title">{{ trans('web::seat.mail') }}</h3>
          </div>
          <div class="col-6">
              @include('web::character.includes.dt-character-selector')
          </div>
          <div class="ms-auto">
              @if($character->refresh_token)
                  @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.mail', 'label' => trans('web::seat.update_mail')])
              @endif
          </div>
      </div>

      {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
    <div class="card-footer">
      <ul class="list-inline d-flex justify-content-around">
        <li class="list-inline-item">
          <span class="badge badge-warning">0</span> Corporation
        </li>
        <li class="list-inline-item">
          <span class="badge badge-primary">0</span> Alliance
        </li>
        <li class="list-inline-item">
          <span class="badge badge-info">0</span> Characters
        </li>
        <li class="list-inline-item">
          <span class="badge badge-success">0</span> Mailing-Lists
        </li>
      </ul>
    </div>
  </div>

  @include('web::common.mails.modals.read.read')

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

  <script>
      $(document).ready(function() {
          $('#dt-character-selector')
              .select2()
              .on('change', function () {
                  window.LaravelDataTables['dataTableBuilder'].ajax.reload();
              });
      });
  </script>

  <script>
    $('#mail-content').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
                ids_to_names();
            });
    });
  </script>

@endpush
