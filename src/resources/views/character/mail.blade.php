@extends('web::character.layouts.view', ['viewname' => 'mail', 'breadcrumb' => trans('web::seat.mail')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.mail') }}
      </h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.mail']) }}"
             class="btn btn-sm btn-light">
            <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_mail') }}"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control" style="width: 100%;">
          @foreach($characters as $character)
            @if($character->character_id == $request->character_id)
              <option selected="selected" value="{{ $character->character_id }}">{{ $character->name }}</option>
            @else
              <option value="{{ $character->character_id }}">{{ $character->name }}</option>
            @endif
          @endforeach
        </select>
      </div>

      {{ $dataTable->table() }}
    </div>
    <div class="card-footer">
      <ul class="list-inline">
        <li class="list-inline-item col-md-3">
          <span class="badge badge-warning">0</span> Corporation
        </li>
        <li class="list-inline-item col-md-3">
          <span class="badge badge-primary">0</span> Alliance
        </li>
        <li class="list-inline-item col-md-3">
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
