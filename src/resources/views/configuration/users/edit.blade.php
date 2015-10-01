@extends('web::layouts.grids.4-8')

@section('title', trans('web::access.edit_user'))
@section('page_header', trans('web::access.edit_user'))
@section('page_description', $user->name)

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::access.edit_user') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form">
        <div class="box-body">

          <div class="form-group">
            <label for="username">{{ ucfirst(trans_choice('web::general.username', 1)) }}</label>
            <input type="text" name="username" class="form-control" id="username" value="{{ $user->name }}">
          </div>

          <div class="form-group">
            <label for="email">{{ ucfirst(trans_choice('web::general.email', 1)) }}</label>
            <input type="email" class="form-control" id="exampleInputEmail1" value="{{ $user->email }}">
          </div>

          <div class="form-group">
            <label for="password">{{ ucfirst(trans_choice('web::general.password', 1)) }}</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
          </div>

          <div class="form-group">
            <label for="password_confirm">{{ ucwords(trans_choice('web::general.password_confirm', 1)) }}</label>
            <input type="password" name="password_again" class="form-control" id="password_confirm" placeholder="Password">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <a href="{{ route('configuration.users.edit.account_status', ['user_id' => $user->id]) }}" class="btn btn-{{ $user->active ? 'warning' : 'success' }} pull-left">
            @if($user->active)
              {{ trans('web::access.deactivate_user') }}
            @else
              {{ trans('web::access.activate_user') }}
            @endif
          </a>
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('web::access.update_user') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop
@section('right')

  <div class="row">

    <div class="col-md-12">

      <!-- role summary -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::access.role_summary') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-hover table-condensed">
            <tbody>
            <tr>
              <th>{{ ucfirst(trans_choice('web::access.role_name', 1)) }}</th>
              <th>{{ ucfirst(trans_choice('web::general.permission', 2)) }}</th>
              <th>{{ ucfirst(trans_choice('web::general.affiliation', 2)) }}</th>
              <th></th>
            </tr>

            @foreach($user->roles as $role)

              <tr>
                <td>{{ $role->title }}</td>
                <td>
                  @foreach($role->permissions as $permission)
                      <span class="label label-{{ $permission->title == 'superuser' ? 'danger' : 'info' }}">{{ studly_case($permission->title) }}</span>
                  @endforeach
                </td>
                <td>
                  @foreach($role->affiliations as $affiliation)
                    <span class="label label-primary">{{ $affiliation->affiliation }} ({{ $affiliation->type }})</span>
                  @endforeach
                </td>
                <td>
                    <a href="{{ route('configuration.access.roles.edit', ['id' => $role->id]) }}" type="button" class="btn btn-warning btn-xs">
                      {{ ucfirst(trans('web::general.edit')) }}
                    </a>
                    <a href="{{ route('configuration.access.roles.edit.remove.user', ['role_id' => $role->id, 'user_id' => $user->id]) }}" type="button" class="btn btn-danger btn-xs">
                      {{ ucfirst(trans('web::general.remove')) }}
                    </a>
                </td>

              </tr>

            @endforeach

            </tbody>
          </table>

        </div>
        <div class="panel-footer">
          <b>{{ count($user->roles) }}</b> {{ trans_choice('web::general.role', count($user->roles)) }}
        </div>
      </div>

    </div>

    <div class="col-md-12">

      <!-- login history -->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::access.login_history') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table table-hover table-condensed">
            <tbody>
            <tr>
              <th>{{ ucfirst(trans_choice('web::general.date', 1)) }}</th>
              <th>{{ ucfirst(trans_choice('web::general.source', 1)) }}</th>
              <th>{{ ucfirst(trans_choice('web::access.user_agent', 1)) }}</th>
              <th>{{ ucfirst(trans_choice('web::general.action', 1)) }}</th>
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
