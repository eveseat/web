@extends('web::layouts.grids.4-4-4')

@section('title', trans('web::access.edit_role'))
@section('page_header', trans('web::access.edit_role'))
@section('page_description', $role->title)

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::general.permission', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.edit.permissions') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="role_id" value="{{ $role->id }}">

        <div class="form-group">
          <label for="permissions">{{ trans('web::access.available_permissions') }}</label>
          <select name="permissions[]" id="available_permissions" style="width: 100%" multiple>

            @foreach(config('web.permissions') as $permission)

              @if(!in_array($permission, $role_permissions))
                <option value="{{ $permission }}">
                  {{ $permission }}
                </option>
              @endif

            @endforeach

          </select>
        </div>

        <button type="submit" class="btn btn-success btn-block">
          {{ trans('web::access.grant_permissions') }}
        </button>

      </form>

      <hr>

      <table class="table table-hover table-condensed">
        <tbody>

        <tr>
          <th colspan="2" class="text-center">{{ trans('web::access.current_permissions') }}</th>
        </tr>

        @foreach($role->permissions as $permission)

          <tr>
            <td>{{ $permission->title }}</td>
            <td>
              <a href="{{ route('configuration.access.roles.edit.remove.permission', ['role_id' => $role->id, 'permission_id' => $permission->id]) }}" type="button" class="btn btn-danger btn-xs pull-right">
                {{ ucfirst(trans('web::general.remove')) }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($role->permissions) }}</b> {{ trans_choice('web::general.permission', count($role->permissions)) }}

      @if(in_array('superuser', $role_permissions))
        <span class="label label-danger pull-right" data-toggle="tooltip" title="{{ trans('web::access.permission_inherit') }}">
          {{ trans('web::access.has_superuser') }}
        </span>
      @endif

    </div>
  </div>

@stop

@section('center')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::general.affiliation', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.edit.affiliations') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="role_id" value="{{ $role->id }}">

        <div class="form-group">
          <label for="corporations">{{ trans('web::access.available_corporations') }}</label>
          <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>

            @foreach($all_corporations as $corporation)
              <option value="{{ $corporation->corporationID }}">
                {{ $corporation->corporationName }}
              </option>
            @endforeach

          </select>
        </div>

        <div class="form-group">
          <label for="characters">{{ trans('web::access.available_characters') }}</label>
          <select name="characters[]" id="available_characters" style="width: 100%" multiple>

            @foreach($all_characters as $character)
              <option value="{{ $character->characterID }}">
                {{ $character->characterName }}
              </option>
            @endforeach

          </select>
        </div>

        <button type="submit" class="btn btn-success btn-block">
          {{ trans('web::access.add_affiliations') }}
        </button>

      </form>

      <hr>

      <table class="table table-hover table-condensed">
        <tbody>

        <tr>
          <th colspan="2" class="text-center">{{ trans('web::access.current_affiliations') }}</th>
        </tr>

        @foreach($role->affiliations as $affiliation)

          <tr>
            <td>{{ $affiliation->affiliation }}</td>
            <td>{{ $affiliation->type }}</td>
            <td>
              <a href="{{ route('configuration.access.roles.edit.remove.affiliation', ['role_id' => $role->id, 'user_id' => $affiliation->id]) }}" type="button" class="btn btn-danger btn-xs pull-right">
                {{ ucfirst(trans('web::general.remove')) }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($role->affiliations) }}</b> {{ trans_choice('web::general.affiliation', count($role->affiliations)) }}
    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ ucfirst(trans_choice('web::general.user', 2)) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.edit.users') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="role_id" value="{{ $role->id }}">

        <div class="form-group">
          <label for="users">{{ trans('web::access.available_users') }}</label>
          <select name="users[]" id="available_users" style="width: 100%" multiple>

            @foreach($all_users as $user)

              @if(!in_array($user, $role_users))
                <option value="{{ $user }}">{{ $user }}</option>
              @endif

            @endforeach

          </select>
        </div>

        <button type="submit" class="btn btn-success btn-block">{{ trans('web::access.add_users') }}</button>

      </form>

      <hr>

      <table class="table table-hover table-condensed">
        <tbody>

        <tr>
          <th colspan="2" class="text-center">{{ trans('web::access.current_users') }}</th>
        </tr>

        @foreach($role->users as $user)

          <tr>
            <td>{{ $user->name }}</td>
            <td>
              <a href="{{ route('configuration.access.roles.edit.remove.user', ['role_id' => $role->id, 'user_id' => $user->id]) }}" type="button" class="btn btn-danger btn-xs pull-right">
                {{ ucfirst(trans('web::general.remove')) }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($role->users) }}</b> {{ trans_choice('web::general.user', count($role->users)) }}
    </div>
  </div>

@stop

@section('javascript')

  <script>
    $("#available_permissions," +
      "#available_users," +
      "#available_characters," +
      "#available_corporations").select2({
       placeholder: "{{ trans('web::access.select_item_add') }}"
    });
  </script>

@stop
