@extends('web::character.layouts.view', ['viewname' => 'contracts', 'breadcrumb' => trans('web::seat.contracts')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.contracts') }}</h3>
      @if($character->refresh_token)
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'character', 'entity' => $character->character_id, 'job' => 'character.contracts', 'label' => trans('web::seat.update_contracts')])
        </div>
      </div>
      @endif
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control" style="width: 100%;">
          @if($character->refresh_token)
            @foreach($character->refresh_token->user->characters as $character_info)
              @if($character_info->character_id == $character->character_id)
                <option selected="selected" value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
              @else
                <option value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
              @endif
            @endforeach
          @else
            <option selected="selected" value="{{ $character->character_id }}">{{ $character->name }}</option>
          @endif
        </select>
      </div>

      @include('web::common.contracts.buttons.filters')

      {{ $dataTable->table() }}
    </div>

  </div>

  @include('web::common.contracts.modals.details.details')

@stop

@push('javascript')
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

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

  @include('web::includes.javascript.id-to-name')

  <script>
      $('#contract-detail').on('show.bs.modal', function (e) {
          var body = $(e.target).find('.modal-body');
          body.html('Loading...');

          $.ajax($(e.relatedTarget).data('url'))
              .done(function (data) {
                  body.html(data);
                  ids_to_names();
                  $("[data-toggle=tooltip]").tooltip();
              });
      });
  </script>

@endpush
