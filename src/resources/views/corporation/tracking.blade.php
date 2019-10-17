@extends('web::corporation.layouts.view', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card card-gray card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a href="#" class="nav-link active" data-toggle="pill" data-filter="all">{{ trans('web::seat.all') }} {{ trans('web::seat.tracking') }}</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="pill" data-filter="valid_token">{{ trans_choice('web::seat.valid_token', 2) }}</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="pill" data-filter="invalid_token">{{ trans_choice('web::seat.invalid_token', 2) }}</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="pill" data-filter="missing_users">{{ trans('web::seat.none') }} {{ trans('web::seat.seat_user') }}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">

        <div class="tab-pane fade show active" role="tabpanel">
          <table id="corporation-member-tracking" class="table compact table-condensed table-hover">
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
    </div>
  </div>

@stop

@push('javascript')

  <script>

    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
      corporation_member_tracking_table.draw();
    });

    function getSelectedFilter() {
      return $("div.card-header > ul.nav-tabs > li.nav-item > a.active").data('filter');
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
        {data: 'character_id', name: 'character_id'},
        {data: 'location', name: 'location'},
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
      }
    });

  </script>

@endpush
