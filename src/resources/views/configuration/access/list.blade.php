@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.access_mangement'))
@section('page_header', trans('web::seat.access_mangement'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.quick_add_role') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.add') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="exampleInputEmail1">{{ trans('web::seat.role_name') }}</label>
            <input type="text" name="title" class="form-control" id="title" placeholder="Enter role name">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">{{ trans('web::seat.add_new_role') }}</button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.available_roles') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-hover table-condensed">
        <tbody>
        <tr>
          <th>{{ trans_choice('web::seat.name', 2) }}</th>
          <th>{{ trans_choice('web::seat.group', 2) }}</th>
          <th>{{ trans_choice('web::seat.permission', 2) }}</th>
          <th>{{ trans_choice('web::seat.affiliation', 2) }}</th>
          <th></th>
        </tr>

        @foreach($roles as $role)

          <tr>
            <td>{{ $role->title }}</td>
            <td>{{ count($role->groups) }}</td>
            <td>{{ count($role->permissions) }}</td>
            <td>{{ count($role->affiliations) }}</td>
            <td>
              <a href="{{ route('configuration.access.roles.edit', ['id' => $role->id]) }}" type="button"
                 class="btn btn-primary btn-xs">
                {{ trans('web::seat.edit') }}
              </a>
              <a href="{{ route('configuration.access.roles.delete', ['id' => $role->id]) }}" type="button"
                 class="btn btn-danger btn-xs">
                {{ trans('web::seat.delete') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($roles) }}</b> {{ trans_choice('web::seat.role', count($roles)) }}
    </div>
  </div>

@stop


