@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
      </h3>
    </div>
    <div class="panel-body">

      <table id="user-configuration-table" class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.token') }}</th>
          <th>{{ trans_choice('web::seat.character', 2) }}</th>
          <th>{{ trans('web::seat.last_login') }}</th>
          <th>{{ trans('web::seat.from') }}</th>
          <th></th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script type="text/javascript">
    $('#user-configuration-table').DataTable({
      "processing": true,
      "serverSide": true,
      "pageLength": 50,
      "ajax": {
        url: "{{url()->current()}}"
      },
      columns: [
        {data: 'refresh_token', name: 'refresh_token', searchable: false, orderable: false},
        {data: 'name', name: 'name'},
        {data: 'last_login', name: 'last_login'},
        {data: 'last_login_source', name: 'last_login_source'},
        {data: 'action_buttons', name: 'action_buttons', searchable: false, orderable: false},
        {data: 'roles', name: 'group.roles.title', visible: false},
        {data: 'email', name: 'email', visible: false},
        {data: 'main_character', name: 'main_character',visible: false}
      ],
      rowGroup: {
        startRender: function(rows, group) {

          var email = rows.data().pluck('email')[0];

          var wraped_role_titles = wordwrap(rows.data().pluck('roles').unique().join(', '), 100, '<br/>n');

          var character_group = rows.data().pluck('main_character_blade')[0];

          return '{{trans('web::seat.main_character')}}: ' + character_group
              + '{{ trans('web::seat.email') }}: ' + email
              + '<span class="pull-right"> {{ trans_choice('web::seat.role', 2) }}: ' + wraped_role_titles + '</span>';
        },
        dataSrc: 'main_character'
      },
      drawCallback : function () {
        $("img").unveil(100);
        ids_to_names();
      },
    });

    function wordwrap( str, width, brk, cut ) {

      brk = brk || 'n';
      width = width || 75;
      cut = cut || false;

      if (!str) { return str; }

      var regex = '.{1,' +width+ '}(\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\S+?(\s|$)');

      return str.match( RegExp(regex, 'g') ).join( brk );

    }
  </script>

@endpush