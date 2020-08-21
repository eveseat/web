@extends('web::character.layouts.view', ['viewname' => 'fittings', 'breadcrumb' => trans('web::seat.fittings')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.fittings'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.fittings') }}
      </h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $character->character_id, 'job_name' => 'character.fittings']) }}"
             class="btn btn-sm btn-light">
            <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_fittings') }}"></i>
          </a>
        </div>
      </div>
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

      {{ $dataTable->table() }}
    </div>
  </div>

  @include('web::common.fittings.modals.fitting.fitting')
  @include('web::common.fittings.modals.insurances.insurances')

@stop

@push('javascript')

  {{ $dataTable->scripts() }}

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
