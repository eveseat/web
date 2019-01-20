@extends('web::corporation.layouts.view', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="" data-toggle="tab" data-filter="all">{{ trans('web::seat.all') }} {{ trans('web::seat.tracking') }}</a></li>
      <li><a href="" data-toggle="tab" data-filter="valid_token">{{ trans_choice('web::seat.valid_token', 2) }}</a></li>
      <li><a href="" data-toggle="tab" data-filter="invalid_token">{{ trans_choice('web::seat.invalid_token', 2) }}</a></li>
      <li><a href="" data-toggle="tab" data-filter="missing_users">{{ trans('web::seat.none') }} {{ trans('web::seat.seat_user') }}</a></li>
    </ul>
    <div class="tab-content">

      <table id="corporation-member-tracking" class="table compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th data-orderable="false">{{ trans('web::seat.token') }}</th>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans('web::seat.last_location') }}</th>
            <th>{{ trans('web::seat.joined') }}</th>
            <th>{{ trans('web::seat.last_login') }}</th>
          </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  <script>

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      corporation_member_tracking_table.draw();
    });

    function getSelectedFilter() {
      return $("div.nav-tabs-custom > ul > li.active > a").data('filter');
    }

    var corporation_member_tracking_table = $('table#corporation-member-tracking').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : {
        url : '{{ route('corporation.view.tracking.data', ['corporation_id' => $request->corporation_id]) }}',
        data: function ( d ) {
          d.selected_refresh_token_status = getSelectedFilter();
        }
      },
      columns         : [
        {data: 'refresh_token', name: 'user.refresh_token', orderable: false, searchable: false},
        {data: 'character_id', name: 'name_filter'},
        {data: 'location', name: 'location', searchable: false},
        {data: 'start_date', name: 'start_date', render: human_readable, searchable: false},
        {data: 'logon_date', name: 'logon_date', render: human_readable, searchable: false}
      ],
      rowGroup: {
        startRender: function(rows, group) {

          var character_group = rows.data().pluck('main_character')[0];

          return '{{trans('web::seat.main_character')}}: ' + character_group;
        },
        dataSrc: 'main_character'
      },
      drawCallback: function () {
        $("img").unveil(100);
        ids_to_names();
        $('[data-toggle="tooltip"]').tooltip();
      },
    });

  </script>

@endpush
