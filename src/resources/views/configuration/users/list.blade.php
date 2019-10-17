@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="card card-gray card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a href="" data-toggle="pill" data-filter="all" class="nav-link active">{{ trans('web::seat.all') }} {{ trans_choice('web::seat.user',2) }}</a>
        </li>
        <li class="nav-item">
          <a href="" data-toggle="pill" data-filter="valid" class="nav-link">{{ trans_choice('web::seat.valid_token', 2) }}</a>
        </li>
        <li class="nav-item">
          <a href="" data-toggle="pill" data-filter="invalid" class="nav-link">{{ trans_choice('web::seat.invalid_token', 2) }}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade active show">
          <table id="user-configuration-table" class="table compact table-condensed table-hover">
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
    </div>
  </div>

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script type="text/javascript">

    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
      users_list.draw();
    });

    function getSelectedFilter() {
      return $("div.card-header > ul.nav-tabs > li.nav-item > a.active").data('filter');
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
              + '<span class="float-right"> {{ trans_choice('web::seat.role', 2) }}: ' + wraped_role_titles + '</span>';
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