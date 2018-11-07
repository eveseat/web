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
        {data: 'refresh_token_deleted_at', name: 'refresh_tokens.deleted_at', searchable: false},
        {data: 'name', name: 'name'},
        {data: 'last_login', name: 'last_login'},
        {data: 'last_login_source', name: 'last_login_source'},
        {data: 'action_buttons', name: 'action_buttons', searchable: false, orderable: false},
        {data: 'main_character_id', name: 'main_character_id', visible: false, searchable: false}
      ],
      rowGroup: {
        startRender: function(rows, group) {

          var email = rows.data().pluck('email')[0];

          if (email === "")
            email = "not defined";

          var roles = rows.data().pluck('group').pluck('roles')[0];

          var role_titles = [];

          roles.forEach(function (role) {
            role_titles.push(role.title.toString())
          });

          var wraped_role_titles = wordwrap(role_titles.join(', '), 100, '<br/>n');

          var character_group = rows.data().pluck('main_character_id')[0];

          return '{{trans('web::seat.main_character')}}: ' + character_group
              + '{{ trans('web::seat.email') }}: ' + email
              + '<span class="pull-right"> {{ trans_choice('web::seat.role', 2) }}: ' + wraped_role_titles + '</span>';
        },
        dataSrc: 'group_id'
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