@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.edit_user'))
@section('page_header', trans('web::seat.edit_user'))
@section('page_description', $user->name)

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.edit_user') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.users.update') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="box-body">

          <div class="form-group">
            <label for="username">{{ trans_choice('web::seat.username', 1) }}</label>
            <input type="text" name="username" class="form-control" id="username" value="{{ $user->name }}" disabled>
          </div>

          <div class="form-group">
            <label for="email">{{ trans_choice('web::seat.email', 1) }}</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">

          @if(auth()->user()->id != $user->id)
            <a href="{{ route('configuration.users.edit.account_status', ['user_id' => $user->id]) }}"
               class="btn btn-{{ $user->active ? 'warning' : 'success' }} pull-left">
              @if($user->active)
                {{ trans('web::seat.deactivate_user') }}
              @else
                {{ trans('web::seat.activate_user') }}
              @endif
            </a>
          @endif
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('web::seat.edit') }}
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- account re-assignment -->
  @if($user->name != 'admin')
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.reassign_user') }}</h3>
    </div>
    <div class="panel-body">

      <div>
        <dl>
          <dt>Current User Group</dt>
          <dd>
            <ul class="list-unstyled">
              @if($user->group)
                @foreach($user->group->users as $group_user)
                  <li>
                    {!! img('character', $group_user->id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $group_user->name }}
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

        <div class="form-group">
          <label for="available_users">{{ trans_choice('web::seat.available_groups', 2) }}</label>
          <select name="group_id" id="available_users" style="width: 100%">

            @foreach($groups as $group)

              <option value="{{ $group->id }}">
                {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
              </option>

            @endforeach

          </select>
        </div>

        <div class="box-footer">

          <button type="submit" class="btn btn-primary pull-right">
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.role_summary') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-hover table-condensed">
            <tbody>
            <tr>
              <th>{{ trans_choice('web::seat.role_name', 1) }}</th>
              <th>{{ trans_choice('web::seat.permission', 2) }}</th>
              <th>{{ trans_choice('web::seat.affiliation', 2) }}</th>
              <th></th>
            </tr>

            @if($user->group)
              @foreach($user->group->roles as $role)

                <tr>
                  <td>{{ $role->title }}</td>
                  <td>
                    @foreach($role->permissions as $permission)
                      <span
                          class="label label-{{ $permission->title == 'superuser' ? 'danger' : 'info' }}">{{ studly_case($permission->title) }}</span>
                    @endforeach
                  </td>
                  <td>
                    @foreach($role->affiliations as $affiliation)
                      <span class="label label-primary">{{ $affiliation->affiliation }} ({{ $affiliation->type }})</span>
                    @endforeach
                  </td>
                  <td>
                    @if(auth()->user()->id != $user->id)
                      <a href="{{ route('configuration.access.roles.edit', ['id' => $role->id]) }}" type="button"
                         class="btn btn-warning btn-xs">
                        {{ trans('web::seat.edit') }}
                      </a>
                      <a href="{{ route('configuration.access.roles.edit.remove.group', ['role_id' => $role->id, 'user_id' => $user->group->id]) }}"
                         type="button" class="btn btn-danger btn-xs">
                        {{ trans('web::seat.remove') }}
                      </a>
                    @endif
                  </td>

                </tr>

              @endforeach
            @endif

            </tbody>
          </table>

        </div>
        <div class="panel-footer">
          <b>{{ count(optional($user->group)->roles) }}</b> {{ trans_choice('web::seat.role', count(optional($user->group)->roles)) }}
        </div>
      </div>
      @endif

    </div>

    <div class="col-md-12">

      <!-- login history -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.login_history') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-hover table-condensed">
            <tbody>
            <tr>
              <th>{{ trans_choice('web::seat.date', 1) }}</th>
              <th>{{ trans_choice('web::seat.source', 1) }}</th>
              <th>{{ trans_choice('web::seat.user_agent', 1) }}</th>
              <th>{{ trans_choice('web::seat.action', 1) }}</th>
              <th></th>
            </tr>

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
                    {{ str_limit($history->user_agent, 60, '...') }}
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
  </script>

@endpush
