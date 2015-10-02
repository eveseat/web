@extends('web::layouts.grids.4-8')

@section('title', trans('web::access.access_mangement'))
@section('page_header', trans('web::access.access_mangement'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::access.quick_add_role') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.add') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="exampleInputEmail1">{{ trans('web::access.role_name') }}</label>
            <input type="text" name="title" class="form-control" id="title" placeholder="Enter role name">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">{{ trans('web::access.add_new_role') }}</button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::access.available_roles') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-hover table-condensed">
        <tbody>
        <tr>
          <th>{{ ucfirst(trans_choice('web::general.name', 2)) }}</th>
          <th>{{ ucfirst(trans_choice('web::general.user', 2)) }}</th>
          <th>{{ ucfirst(trans_choice('web::general.permission', 2)) }}</th>
          <th>{{ ucfirst(trans_choice('web::general.affiliation', 2)) }}</th>
          <th></th>
        </tr>

        @foreach($roles as $role)

          <tr>
            <td>{{ $role->title }}</td>
            <td>{{ count($role->users) }}</td>
            <td>{{ count($role->permissions) }}</td>
            <td>{{ count($role->affiliations) }}</td>
            <td>
              <a href="{{ route('configuration.access.roles.edit', ['id' => $role->id]) }}" type="button" class="btn btn-primary btn-xs">
                {{ ucfirst(trans('web::general.edit')) }}
              </a>
              <a href="{{ route('configuration.access.roles.delete', ['id' => $role->id]) }}" type="button" class="btn btn-danger btn-xs">
                {{ ucfirst(trans('web::general.delete')) }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($roles) }}</b> {{ ucfirst(trans_choice('web::general.role', count($roles))) }}
    </div>
  </div>

@stop


