@extends('web::corporation.layouts.view', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('web::seat.tracking') }}</h3>
    </div>
    <div class="box-body">

      <div>
        <b>{{ trans('web::seat.status') }}</b>
        <div class="input-group">
          <label class="checkbox-inline">
            <input onchange="filterme()" type="checkbox" name="token_status" value="valid_token" checked>{{ trans('web::seat.valid_token') }}
          </label>
          <label class="checkbox-inline">
            <input onchange="filterme()" type="checkbox" name="token_status" value="invalid_token" checked>{{ trans('web::seat.invalid_token') }}
          </label>
        </div>
      </div>

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

    function refreshTokenCheckboxes() {
      return $('input:checkbox[name="token_status"]:checked').map(function() {
        return  this.value;
      }).get();
    }

    function filterme() {

      corporation_member_tracking_table.draw()
    }

    var corporation_member_tracking_table = $('table#corporation-member-tracking').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : {
        url : '{{ route('corporation.view.tracking.data', ['corporation_id' => $request->corporation_id]) }}',
        data: function ( d ) {
          d.selected_refresh_token_status = refreshTokenCheckboxes();
        }
      },
      columns         : [
        {data: 'refresh_token', name: 'user.refresh_token', orderable: false, searchable: false},
        {data: 'character_id', name: 'user.name'},
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
