@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans_choice('web::seat.group', count($group_counter)) }}
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive" id="users-list">
        <thead>
          <tr>
            <th>{{ trans('web::seat.main_character') }}</th>
            <th>{{ trans_choice('web::seat.role', 2) }}</th>
            <th>{{ trans('web::seat.email') }}</th>
            <th>{{ trans_choice('web::seat.character', 2) }}</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ $group_counter }}</b> {{ trans_choice('web::seat.group', count($group_counter)) }} |
      <b>{{ $user_counter }}</b> {{ trans_choice('web::seat.user', 2) }}
    </div>

  </div>

@stop

@push('javascript')

  <script>

      $(function () {
          $('table#users-list').DataTable({
              processing      : true,
              serverSide      : true,
              ajax            : '{{ route('configuration.users.data') }}',
              columns         : [
                  {data: 'main_character', name: 'main_character', className: 'align-middle text-center'},
                  {data: 'roles', name: 'roles', className: 'align-middle text-center'},
                  {data: 'email', name: 'email', className: 'align-middle text-center'},
                  {data: 'users', name: 'users'}
              ],
              dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
              order           : [[0, "asc"]]
          });
      });

  </script>
@endpush