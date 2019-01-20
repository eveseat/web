@extends('web::corporation.layouts.view', ['viewname' => 'killmails', 'breadcrumb' => trans('web::seat.killmails')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.killmails'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="box box-default">
    <div class="box-header with-border">
      {{ trans('web::seat.killmails') }}
    </div>
    <div class="box-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporation-killmails" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.ship_type') }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th>{{ trans('web::seat.victim') }}</th>
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
          {data: 'killmail_detail.killmail_time', name: 'killmail_detail.killmail_time', render: human_readable},
          {data: 'ship', name: 'killmail_victim.ship_type.typeName'},
          {data: 'place', name: 'killmail_detail.solar_system.itemName'},
          {data: 'victim', name: 'killmail_victim.victim_character.name'},
          {data: 'zkb', name: 'zkb', searchable: false},
          {data: 'killmail_hash', name: 'killmail_victim.victim_corporation.name', visible: false},
          {data: 'killmail_id', name: 'killmail_victim.victim_alliance.name', visible: false}
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
