@extends('web::corporation.layouts.view', ['viewname' => 'killmails'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.killmails'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.killmails') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporation-killmails" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.victim') }}</th>
          <th>{{ trans('web::seat.ship_type') }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th></th>
        </tr>
        </thead>
      </table>

    </div>

  </div>

@stop

@push('javascript')

  <script>

    $(function () {
      $('table#corporation-killmails').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('corporation.view.killmails.data', ['corporation_id' => $request->corporation_id]) }}',
        columns         : [
          {data: 'killmail_time', name: 'killmail_time', render: human_readable},
          {data: 'character_name', name: 'character_name'},
          {data: 'type_name', name: 'type_name'},
          {data: 'item_name', name: 'item_name'},
          {data: 'zkb', name: 'zkb'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("[data-toggle=tooltip]").tooltip();
            $("img").unveil(100);
            ids_to_names();
          });
        }
      });
    });

  </script>

@endpush
