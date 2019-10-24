@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.edit_user'))
@section('page_header', trans('web::seat.edit_user'))
@section('page_description', $user->name)

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.edit_user') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('configuration.access.users.update') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="box-body">

          <div class="form-group row">
            <label for="username" class="col-form-label col-md-4">{{ trans_choice('web::seat.username', 1) }}</label>
            <div class="col-md-8">
              <input type="text" name="username" class="form-control" id="username" value="{{ $user->name }}" disabled>
            </div>
          </div>

          <div class="form-group row">
            <label for="email" class="col-form-label col-md-4">{{ trans_choice('web::seat.email', 1) }}</label>
            <div class="col-md-8">
              <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}">
            </div>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">

          @if(auth()->user()->id != $user->id)
            <a href="{{ route('configuration.users.edit.account_status', ['user_id' => $user->id]) }}"
               class="btn btn-{{ $user->active ? 'danger' : 'success' }} float-left">
              @if($user->active)
                <i class="fas fa-user-slash"></i>
                {{ trans('web::seat.deactivate_user') }}
              @else
                <i class="fas fa-user-check"></i>
                {{ trans('web::seat.activate_user') }}
              @endif
            </a>
          @endif
          <button type="submit" class="btn btn-warning float-right">
            <i class="fas fa-pencil-alt"></i>
            {{ trans('web::seat.edit') }}
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- account re-assignment -->
  @if($user->name != 'admin')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.reassign_user') }}</h3>
    </div>
    <div class="card-body">

      <div>
        <dl class="row">
          <dt class="col-md-4">Current User Group</dt>
          <dd class="col-md-8">
            <ul class="list-group list-group-flush">
              @if($user->group)
                @foreach($user->group->users as $group_user)
                  <li class="list-group-item">
                    @include('web::partials.character', ['character' => $group_user->character])
                  </li>
                @endforeach
              @endif
            </ul>
          </dd>
        </dl>
      </div>

      <form role="form" action="{{ route('configuration.access.users.reassign') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="form-group row">
          <label for="available_users" class="col-form-label col-md-4">{{ trans_choice('web::seat.available_groups', 2) }}</label>
          <div class="col-md-8">
            <select name="group_id" id="available_users" style="width: 100%">

              @foreach($groups as $group)

                <option value="{{ $group->id }}">
                  {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                </option>

              @endforeach

            </select>
          </div>
        </div>

        <div class="box-footer">

          <button type="submit" class="btn btn-primary float-right">
            <i class="fas fa-user-friends"></i>
            {{ trans('web::seat.reassign') }}
          </button>

        </div>
      </form>

    </div>
  </div>
  @endif

@stop
@section('right')

  <div class="row">

    <div class="col-md-12">

      <!-- role summary -->
      @if($user->name != 'admin')
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.role_summary') }}</h3>
        </div>
        <div class="card-body">

          <table class="table table-hover table-condensed" id="roles">
            <thead>
              <tr>
                <th>{{ trans_choice('web::seat.role_name', 1) }}</th>
                <th></th>
                <th>{{ trans_choice('web::seat.permission', 2) }}</th>
                <th>{{ trans_choice('web::seat.filter', 2) }}</th>
              </tr>
            </thead>
            <tbody>

            @if($user->group)
              @foreach($user->group->roles as $role)
                @foreach($role->permissions as $permission)
                  <tr>
                    <td>{{ $role->title }}</td>
                    <td>
                      @if(auth()->user()->id != $user->id)
                        <div class="btn-group btn-group-sm float-right">
                          <a href="{{ route('configuration.access.roles.edit', [$role->id]) }}" type="button"
                             class="btn btn-warning">
                            <i class="fas fa-pencil-alt"></i>
                            {{ trans('web::seat.edit') }}
                          </a>
                          <form method="post" action="{{ route('configuration.access.roles.edit.remove.group', ['role_id' => $role->id, 'group_id' => $user->group->id]) }}">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-danger">
                              <i class="fas fa-trash-alt"></i>
                              {{ trans('web::seat.remove') }}
                            </button>
                          </form>
                        </div>
                      @endif
                    </td>
                    <td>
                      <span class="badge badge-{{ $permission->title == 'global.superuser' ? 'danger' : 'info' }}">
                        {{ Str::studly($permission->title) }}
                      </span>
                    </td>
                    <td>
                      @if($permission->pivot->filters)
                        @foreach(json_decode($permission->pivot->filters) as $type => $entities)
                          @foreach($entities as $entity)
                            {!! img($type, $entity->id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                            {{ $entity->text }}
                          @endforeach
                        @endforeach
                      @endif
                    </td>
                  </tr>
                @endforeach
              @endforeach
            @endif

            </tbody>
          </table>

        </div>
        <div class="card-footer">
          <i class="text-muted float-right">{{ $user->group->roles->count() }} {{ trans_choice('web::seat.role', $user->group->roles->count()) }}</i>
        </div>
      </div>
      @endif

    </div>

    <div class="col-md-12">

      <!-- login history -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.login_history') }}</h3>
        </div>
        <div class="card-body">

          <table class="table table-hover table-condensed">
            <thead>
              <tr>
                <th>{{ trans_choice('web::seat.date', 1) }}</th>
                <th>{{ trans_choice('web::seat.source', 1) }}</th>
                <th>{{ trans_choice('web::seat.user_agent', 1) }}</th>
                <th>{{ trans_choice('web::seat.action', 1) }}</th>
              </tr>
            </thead>
            <tbody>

              @foreach($login_history as $history)

                <tr>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $history->created_at }}">
                      {{ human_diff($history->created_at) }}
                    </span>
                  </td>
                  <td>{{ $history->source }}</td>
                  <td>
                    <span data-toggle="tooltip" title="" data-original-title="{{ $history->user_agent }}">
                      {{ Str::limit($history->user_agent, 60, '...') }}
                    </span>
                  </td>
                  <td>{{ ucfirst($history->action) }}</td>
                </tr>

              @endforeach

            </tbody>
          </table>

        </div>
      </div>

    </div><!-- ./col-md-12 -->

  </div><!-- ./row -->


@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_users").select2({
      placeholder: "{{ trans('web::seat.select_group_to_assign') }}"
    });

    $('#roles').DataTable({
        'columns': [
            {'visible': false},
            {'visible': false},
            {},
            {}
        ],
    'drawCallback': function (settings) {
        var last = null;
        var api = this.api();
        var rows = api.rows({
            page:'current'
        }).nodes();

        $(api.cells({page:'current'})[0])
            .each(function (i, cell) {
                if (cell.column === 0) {
                    group = api.cell(this).data();

                    if (last !== group) {
                        $(rows).eq((i === 0) ? 0 : (i / 4))
                            .before('<tr class="bg-gray"><th class="align-middle">' + group + '</th><th>' + api.cell(cell.row, 1).data() + '</th></tr>');

                        last = group;
                    }
                }
            });

        ids_to_names(); }
    });
  </script>

@endpush
