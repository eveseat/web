@extends('web::character.layouts.view', ['viewname' => 'mail', 'breadcrumb' => trans('web::seat.mail')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.mail'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.mail') }}

        <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.mail']) }}" class="pull-right">
          <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_mail') }}"></i>
        </a>
      </h3>
    </div>
    <div class="panel-body">
      <div class="margin-bottom">
        <select multiple="multiple" id="dt-character-selector" class="form-control">
          @foreach($characters as $character)
            @if($character->id == $request->character_id)
              <option selected="selected" value="{{ $character->id }}">{{ $character->name }}</option>
            @else
              <option value="{{ $character->id }}">{{ $character->name }}</option>
            @endif
          @endforeach
        </select>
      </div>

      {{ $dataTable->table() }}
    </div>
    <div class="panel-footer clearfix">
      <div class="col-md-2 col-md-offset-2">
        <span class="label label-warning">0</span> Corporation
      </div>
      <div class="col-md-2">
        <span class="label label-primary">0</span> Alliance
      </div>
      <div class="col-md-2">
        <span class="label label-info">0</span> Characters
      </div>
      <div class="col-md-2">
        <span class="label label-success">0</span> Mailing-Lists
      </div>
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
