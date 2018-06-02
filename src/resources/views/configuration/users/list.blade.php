@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans_choice('web::seat.current_groups', count($groups)) }}
      </h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.main_character') }}</th>
          <th>{{ trans('web::seat.status') }}</th>
          <th>{{ trans('web::seat.token') }}</th>
          <th>{{ trans('web::seat.email') }}</th>
          <th>{{ trans('web::seat.last_login') }}</th>
          <th>{{ trans('web::seat.from') }}</th>
          <th>{{ trans_choice('web::seat.role', 2) }}</th>
          <th></th>
        </tr>
        </thead>

        <tbody>

        @foreach($groups as $user_group)
          <tr>
            @if(in_array('admin',$user_group->users->map(function ($user){ return $user->name;})->toArray()))
              @foreach($user_group->users as $user)
                <td>{{  $user->name }}</td>
                <td>
                  @if($user->active)
                    <span class="label label-success">
                    {{ trans('web::seat.enabled') }}
                  </span>
                  @else
                    <span class="label label-danger">
                    {{ trans('web::seat.disabled') }}
                  </span>
                  @endif
                </td>
                <td data-order="{{ $user->refresh_token ? 1 : 0 }}">
                  @if($user->refresh_token)
                    <i class="fa fa-check text-success"></i>
                  @else
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                  @endif
                </td>
                <td>{{ $user->email }}</td>
                <td data-order="{{ $user->last_login }}">
              <span data-toggle="tooltip" title="" data-original-title="{{ $user->last_login }}">
                {{ human_diff($user->last_login) }}
              </span>
                </td>
                <td>{{ $user->last_login_source }}</td>
                <td>{{ count($user->group->roles) }}</td>
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
        @continue
        @endif

        <td>{{ $user_group->main_character->name }}</td>
        <td>
          @if($user_group->isActive())
            <span class="label label-success">
                    {{ trans('web::seat.enabled') }}
                  </span>
          @else
            <span class="label label-danger">
                    {{ trans('web::seat.disabled') }}
                  </span>
          @endif
        </td>
        <td data-order="{{$user_group->mainCharacterUser->refresh_token ? 1 : 0 }}">
          <ul class="list-unstyled">
            @foreach($user_group->users as $user)
              <li>
                {{$user->name . " "}}
                @if($user->refresh_token)
                  <i class="fa fa-check text-success"></i>
                @else
                  <i class="fa fa-exclamation-triangle text-danger"></i>
                @endif
              </li>
            @endforeach
          </ul>
        </td>
        <td>{{ $user_group->email }}</td>
        <td data-order="{{ $user_group->users->sortByDesc('last_login')->first()->last_login }}">
              <span data-toggle="tooltip" title=""
                    data-original-title="{{ $user_group->users->sortByDesc('last_login')->first()->last_login }}">
                {{ human_diff($user_group->users->sortByDesc('last_login')->first()->last_login) }}
                ({{$user_group->users->sortByDesc('last_login')->first()->name}})
              </span>
        </td>
        <td>{{ $user_group->users->sortByDesc('last_login')->first()->last_login_source }}</td>
        <td>{{ count($user_group->roles) }}</td>
        <td>
          <div class="btn-group">
            <a href="{{ route('configuration.users.edit', ['user_id' => $user_group->mainCharacterUser->id]) }}"
               type="button"
               class="btn btn-warning btn-xs">
              {{ trans('web::seat.edit') }}
            </a>
          </div>

          @if(auth()->user()->id != $user_group->mainCharacterUser->id)
            <div class="btn-group">
              <a href="{{ route('configuration.users.delete', ['user_id' => $user_group->mainCharacterUser->id]) }}"
                 type="button"
                 class="btn btn-danger btn-xs confirmlink">
                {{ trans('web::seat.delete') }}
              </a>
              <a href="{{ route('configuration.users.impersonate', ['user_id' => $user_group->mainCharacterUser->id]) }}"
                 type="button"
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

    </div>
    <div class="panel-footer">
      <b>{{ count($groups) }}</b> {{ trans_choice('web::seat.user_group', count($groups)) }}
    </div>

  </div>

@stop