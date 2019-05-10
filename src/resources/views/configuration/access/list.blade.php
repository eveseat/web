@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.access_mangement'))
@section('page_header', trans('web::seat.access_mangement'))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.quick_add_role') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" method="post" action="{{ route('configuration.access.roles.store') }}">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group row">
            <label for="role-title" class="col-form-label col-md-4">{{ trans('web::seat.role_name') }}</label>
            <div class="col-md-8">
              <input type="text" name="title" class="form-control" id="role-title" placeholder="Enter role name">
            </div>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-success float-right">
            <i class="fas fa-plus-square"></i>
            {{ trans('web::seat.add_new_role') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.available_roles') }}</h3>
    </div>
    <div class="card-body">

      <table class="table table-hover table-striped table-condensed">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 2) }}</th>
            <th>{{ trans_choice('web::seat.group', 2) }}</th>
            <th>{{ trans_choice('web::seat.permission', 2) }}</th>
            <th>{{ trans_choice('web::seat.affiliation', 2) }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

          @foreach($roles as $role)

            <tr>
              <td>{{ $role->title }}</td>
              <td>{{ count($role->groups) }}</td>
              <td>{{ count($role->permissions) }}</td>
              <td>{{ count($role->affiliations) }}</td>
              <td>
                <form method="post" action="{{ route('configuration.access.roles.delete', ['id' => $role->id]) }}">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <div class="btn-group btn-group-sm float-right">
                    <a href="{{ route('configuration.access.roles.edit', ['id' => $role->id]) }}" type="button"
                       class="btn btn-warning">
                      <i class="fas fa-pencil-alt"></i>
                      {{ trans('web::seat.edit') }}
                    </a>
                    <button type="submit" class="btn btn-danger">
                      <i class="fas fa-trash-alt"></i>
                      {{ trans('web::seat.delete') }}
                    </button>
                  </div>
                </form>
              </td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>
    <div class="card-footer">
      <i class="float-right text-muted">{{ count($roles) }} {{ trans_choice('web::seat.role', count($roles)) }}</i>
    </div>
  </div>

@stop


