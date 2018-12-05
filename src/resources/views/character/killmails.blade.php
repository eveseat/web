@extends('web::character.layouts.view', ['viewname' => 'killmails'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.killmails'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.killmails') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.killmails']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_killmails') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-killmails" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.victim') }}</th>
          <th>{{ trans('web::seat.ship_type') }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th data-orderable="false"></th>
        </tr>
        </thead>
      </table>

    </div>

  </div>

@stop

@push('javascript')

  <script>

    $(function () {
      $('table#character-killmails').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('character.view.killmails.data', ['character_id' => $request->character_id]) }}',
        columns         : [
          {data: 'killmail_time', name: 'killmail_time', render: human_readable},
          {data: 'character_name', name: 'character_name'},
          {data: 'type_name', name: 'type_name'},
          {data: 'item_name', name: 'item_name'},
          {data: 'zkb', name: 'zkb'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        'fnDrawCallback': function () {
          $(document).ready(function () {
            $('img').unveil(100);
            ids_to_names();
          });
        }
      });
    });

  </script>

@endpush
