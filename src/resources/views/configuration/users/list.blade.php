@extends('web::layouts.grids.3-9')

@section('title', 'User Management')
@section('page_header', 'User Management')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::access.quick_add_user') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form">
        <div class="box-body">

          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">{{ trans('web::access.add_new_user') }}</button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">
      Current Users
    </h3>
  </div>
  <div class="panel-body">

  <table class="table table-hover table-condensed">
    <tbody>
      <tr>
        <th>{{ ucfirst(trans_choice('web::general.name', 1)) }}</th>
        <th>{{ ucfirst(trans('web::general.email')) }}</th>
        <th>{{ ucfirst(trans_choice('web::general.status', 1)) }}</th>
        <th>{{ ucwords(trans('web::access.last_login')) }}</th>
        <th>{{ ucfirst(trans('web::general.from')) }}</th>
        <th>{{ ucfirst(trans_choice('web::general.key', 2)) }}</th>
        <th>{{ ucfirst(trans_choice('web::general.role', 2)) }}</th>
        <th></th>
      </tr>

      @foreach($users as $user)

        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            <span class="label label-{{ $user->active == 1 ? 'success' : 'warning' }}">
              {{ $user->active == 1 ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <span data-toggle="tooltip" title="" data-original-title="{{ $user->last_login }}">
              {{ human_diff($user->last_login) }}
            </span>
          </td>
          <td>{{ $user->last_login_source }}</td>
          <td>{{ count($user->keys) }}</td>
          <td>{{ count($user->roles) }}</td>
          <td>
            <div class="btn-group">
              <a href="{{ route('configuration.users.edit', ['user_id' => $user->id]) }}" type="button" class="btn btn-warning btn-xs">
                {{ ucfirst(trans('web::general.edit')) }}
              </a>
              <a href="{{ route('configuration.users.delete', ['user_id' => $user->id]) }}" type="button" class="btn btn-danger btn-xs confirmlink">
                {{ ucfirst(trans('web::general.delete')) }}
              </a>
            </div>
              <a href="{{ route('configuration.users.impersonate', ['user_id' => $user->id]) }}" type="button" class="btn btn-success btn-xs">{{ ucfirst(trans('web::access.impersonate')) }}</a>
          </td>
        </tr>

      @endforeach

    </tbody>
  </table>

  </div>
  <div class="panel-footer">
    <b>{{ count($users) }}</b> {{ trans_choice('web::general.user', count($users)) }}
  </div>

</div>

@stop
