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
        {data: 'action_buttons', name: 'action_buttons', searchable: false},
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

          return '{{trans('web::seat.main_character')}}: ' + group
              + '{{ trans('web::seat.email') }}: ' + email
              + '<span class="pull-right"> {{ trans_choice('web::seat.role', 2) }}: ' + role_titles.join(', ') + '</span>';
        },
        dataSrc: 'main_character_id'
      },
      drawCallback : function () {
        $("img").unveil(100);
        ids_to_names();
      },
    });
  </script>

@endpush