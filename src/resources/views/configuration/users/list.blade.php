@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="" data-toggle="tab" data-filter="all">{{ trans('web::seat.all') }} {{ trans_choice('web::seat.user',2) }}</a></li>
      <li><a href="" data-toggle="tab" data-filter="valid">{{ trans_choice('web::seat.valid_token', 2) }}</a></li>
      <li><a href="" data-toggle="tab" data-filter="invalid">{{ trans_choice('web::seat.invalid_token', 2) }}</a></li>
    </ul>
    <div class="tab-content">

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

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      users_list.draw();
    });

    function getSelectedFilter() {
      return $("div.nav-tabs-custom > ul > li.active > a").data('filter');
    }

    var users_list = $('#user-configuration-table').DataTable({
      processing: true,
      serverSide: true,
      searchDelay: 600,
      pageLength: 50,
      ajax: {
        url: '{{url()->current()}}',
        data: function (d) {
          d.filter = getSelectedFilter();
        }
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
      createdRow: function (row, data) {
        if (data.active === false){
          $(row).addClass('warning')
        }
      }
    });

  </script>

@endpush