@extends('web::layouts.grids.4-4-4')

@section('title', trans('web::seat.edit_role'))
@section('page_header', trans('web::seat.edit_role'))
@section('page_description', $role->title)

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.permission', 2) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.edit.permissions') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="role_id" value="{{ $role->id }}">

        <div class="form-group">
          <label for="permissions">{{ trans('web::seat.available_permissions') }}</label>
          <select name="permissions[]" id="available_permissions" style="width: 100%" multiple>

            @foreach(config('web.permissions') as $type => $permission)

              @if(is_array($permission))

                @foreach($permission as $category_permission)

                  <option value="{{ $type }}.{{ $category_permission }}">
                    {{ studly_case($type) }}{{ studly_case($category_permission) }}
                  </option>

                @endforeach

              @else

                <option value="{{ $permission }}">
                  {{ studly_case($permission) }}
                </option>

              @endif

            @endforeach

          </select>
        </div>

        <div class="checkbox">
          <label>
            <input type="checkbox" name="inverse">
            {{ trans('web::seat.inverse_permission') }}
          </label>
        </div>

        <button type="submit" class="btn btn-success btn-block">
          {{ trans('web::seat.grant_permissions') }}
        </button>

      </form>

      <hr>

      <table class="table table-hover table-condensed">
        <tbody>

        <tr>
          <th colspan="3" class="text-center">{{ trans('web::seat.current_permissions') }}</th>
        </tr>

        @foreach($role->permissions as $permission)

          <tr>
            <td>{{ studly_case($permission->title) }}</td>
            <td>
              @if($permission->pivot->not == 1)
                {{ trans('web::seat.inverse') }}
              @endif
            </td>
            <td>
              <a href="{{ route('configuration.access.roles.edit.remove.permission', ['role_id' => $role->id, 'permission_id' => $permission->id]) }}"
                 type="button" class="btn btn-danger btn-xs pull-right">
                {{ trans('web::seat.remove') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($role->permissions) }}</b> {{ trans_choice('web::seat.permission', count($role->permissions)) }}

      {{-- determine if this role has the superuser role --}}
      @if($role->permissions->where('title', 'superuser')->first())

        {{-- ensure the role is not inversed --}}
        @if($role->permissions->where('title', 'superuser')->first()->pivot->not == 0)

          <span class="label label-danger pull-right" data-toggle="tooltip"
                title="{{ trans('web::seat.permission_inherit') }}">
          {{ trans('web::seat.has_superuser') }}
          </span>

        @endif

      @endif

    </div>
  </div>

@stop

@section('center')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.affiliation', 2) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.edit.affiliations') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="role_id" value="{{ $role->id }}">

        <div class="form-group">
          <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
          <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>

            <option value="0">All Corporations</option>
            @foreach($all_corporations as $corporation)
              <option value="{{ $corporation->corporation_id }}">
                {{ $corporation->name }}
              </option>
            @endforeach

          </select>
        </div>

        <div class="form-group">
          <label for="characters">{{ trans('web::seat.available_characters') }}</label>
          <select name="characters[]" id="available_characters" style="width: 100%" multiple>

            <option value="0">All Characters</option>
            @foreach($all_characters as $character)
              <option value="{{ $character->character_id }}">
                {{ $character->name }}
              </option>
            @endforeach

          </select>

          <div class="checkbox">
            <label>
              <input type="checkbox" name="inverse">
              {{ trans('web::seat.inverse_affiliation') }}
            </label>
          </div>

        </div>

        <button type="submit" class="btn btn-success btn-block">
          {{ trans('web::seat.add_affiliations') }}
        </button>

      </form>

      <hr>

      <table class="table table-hover table-condensed">
        <tbody>

        <tr>
          <th colspan="4" class="text-center">{{ trans('web::seat.current_affiliations') }}</th>
        </tr>

        @foreach($role->affiliations as $affiliation)

          <tr>
            <td>
              @if($affiliation->affiliation === 0)

                {{ trans('web::seat.all') }}
                @if($affiliation->type == 'corp')
                  {{ trans_choice('web::seat.corporation', 2) }}
                @else
                  {{ trans_choice('web::seat.character', 2) }}
                @endif

              @else

                {!! img('auto', $affiliation->affiliation, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span class="id-to-name" data-id="{{$affiliation->affiliation}}">{{ trans('web::seat.unknown') }}</span>

              @endif
            </td>
            <td>{{ ucfirst($affiliation->type) }}</td>
            <td>
              @if($affiliation->pivot->not == 1)
                {{ trans('web::seat.inverse') }}
              @endif
            </td>
            <td>
              <a href="{{ route('configuration.access.roles.edit.remove.affiliation', ['role_id' => $role->id, 'user_id' => $affiliation->id]) }}"
                 type="button" class="btn btn-danger btn-xs pull-right">
                {{ trans('web::seat.remove') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($role->affiliations) }}</b> {{ trans_choice('web::seat.affiliation', count($role->affiliations)) }}
    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.group', 2) }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('configuration.access.roles.edit.groups') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="role_id" value="{{ $role->id }}">

        <div class="form-group">
          <label for="groups">{{ trans_choice('web::seat.available_groups',2) }}</label>
          <select name="groups[]" id="available_users" style="width: 100%" multiple>

            @foreach($all_groups as $group)

              @if(!in_array($group->id, $role_groups))
                <option value="{{ $group->id }}">
                  {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
                </option>
              @endif

            @endforeach

          </select>
        </div>

        <button type="submit" class="btn btn-success btn-block">{{ trans_choice('web::seat.add_group', 2) }}</button>

      </form>

      <hr>

      <table class="table table-hover table-condensed">
        <tbody>

        <tr>
          <th colspan="2" class="text-center">{{ trans_choice('web::seat.current_groups',2) }}</th>
        </tr>

        @foreach($role->groups as $group)

          <tr>
            <td>
              {{ $group->users->map(function($user) { return $user->name; })->implode(', ') }}
            </td>
            <td>
              <a href="{{ route('configuration.access.roles.edit.remove.group', ['role_id' => $role->id, 'user_id' => $group->id]) }}"
                 type="button" class="btn btn-danger btn-xs pull-right">
                {{ trans('web::seat.remove') }}
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
        <b>{{ count($role->groups) }}</b>
        {{ trans_choice('web::seat.group', count($role->groups)) }}
    </div>
  </div>

@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')

  <script>
    $("#available_permissions," +
        "#available_users," +
        "#available_characters," +
        "#available_corporations").select2({
      placeholder: "{{ trans('web::seat.select_item_add') }}"
    });
  </script>

@endpush
