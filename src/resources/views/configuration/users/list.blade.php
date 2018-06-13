@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans_choice('web::seat.group', count($groups)) }}
      </h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.main_character') }}</th>
          <th>{{ trans_choice('web::seat.role', 2) }}</th>
          <th>{{ trans_choice('web::seat.group', 2) }}</th>
        </tr>
        </thead>

        <tbody>

        @foreach($groups as $group)

          <tr>
            <td>
              {!! img('character', optional($group->main_character)->character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ optional($group->main_character)->name }}
            </td>
            <td>
              @if(count($group->roles) > 0)
                <ul class="list-unstyled">
                  @foreach($group->roles as $role)
                    <li>{{ $role->title }}</li>
                  @endforeach
                </ul>
              @else
                No Roles
              @endif
            </td>
            <td>

              <table class="table compact table-condensed table-hover table-responsive">
                <thead>
                <tr>
                  <th>{{ trans_choice('web::seat.name', 1) }}</th>
                  <th>{{ trans('web::seat.status') }}</th>
                  <th>{{ trans('web::seat.token') }}</th>
                  <th>{{ trans('web::seat.last_login') }}</th>
                  <th>{{ trans('web::seat.from') }}</th>
                  <th></th>
                </tr>
                </thead>

                <tbody>
                @foreach($group->users as $user)
                  <tr>
                    <td>
                      {!! img('character', $user->id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                      {{ $user->name }}
                    </td>
                    <td>
                      @if($user->active)
                        <span class="label label-success">{{ trans('web::seat.enabled') }}</span>
                      @else
                        <span class="label label-danger">{{ trans('web::seat.disabled') }}</span>
                      @endif
                    </td>
                    <td data-order="{{ $user->refresh_token ? 1 : 0 }}">
                      @if($user->refresh_token)
                        <i class="fa fa-check text-success"></i>
                      @else
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                      @endif
                    </td>
                    <td data-order="{{ $user->last_login }}">
                      <span data-toggle="tooltip" title="" data-original-title="{{ $user->last_login }}">
                        {{ human_diff($user->last_login) }}
                      </span>
                    </td>
                    <td>{{ $user->last_login_source }}</td>
                    <td>
                      <div class="btn-group">
                        <a href="{{ route('configuration.users.edit', ['user_id' => $user->id]) }}" type="button"
                           class="btn btn-warning btn-xs">
                          {{ trans('web::seat.edit') }}
                        </a>
                      </div>

                      @if(auth()->user()->id != $user->id)
                        <div class="btn-group">
                          <a href="{{ route('configuration.users.delete', ['user_id' => $user->id]) }}" type="button"
                             class="btn btn-danger btn-xs confirmlink">
                            {{ trans('web::seat.delete') }}
                          </a>
                          <a href="{{ route('configuration.users.impersonate', ['user_id' => $user->id]) }}" type="button"
                             class="btn btn-success btn-xs">
                            {{ trans('web::seat.impersonate') }}
                          </a>
                        </div>
                      @else
                        <em class="text-danger">(This is you!)</em>
                      @endif
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>

            </td>
          </tr>

        @endforeach
        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($groups) }}</b> {{ trans_choice('web::seat.group', count($groups)) }}
    </div>

  </div>

@stop
