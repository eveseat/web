@extends('web::layouts.grids.12')

{{-- #TODO Refactor this in 3.1, please. --}}

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

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans('web::seat.main_character') }}</th>
            <th>{{ trans_choice('web::seat.role', 2) }}</th>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans('web::seat.status') }}</th>
            <th>{{ trans('web::seat.token') }}</th>
            <th>{{ trans('web::seat.last_login') }}</th>
            <th>{{ trans('web::seat.from') }}</th>
            <th></th>
          </tr>
        </thead>

        <tbody>

        @foreach($groups->sortBy(function($group) { return strtolower(optional($group->main_character)->name); }) as $group)

          <tr>
            <td rowspan="{{ $group->users->count() }}" style="border-top-width: 2px">
              {!! img('character', optional($group->main_character)->character_id, 64, ['class' => 'img-circle eve-icon small-icon', 'alt' => optional($group->main_character)->name]) !!}
              {{ optional($group->main_character)->name }}
            </td>
            <td rowspan="{{ $group->users->count() }}" style="border-top-width: 2px">
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
            {{-- get first user attached to the group in order to end initial group seeding --}}
            <td style="border-top-width: 2px">
              {!! img('character', $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->id, 64, ['class' => 'img-circle eve-icon small-icon', 'alt' => $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->name]) !!}
              {{ $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->name }}
            </td>
            <td style="border-top-width: 2px">
              @if($group->users->sortBy(function($user) { return strtolower($user->name); })->first()->active)
                <span class="label label-success">{{ trans('web::seat.enabled') }}</span>
              @else
                <span class="label label-danger">{{ trans('web::seat.disabled') }}</span>
              @endif
            </td>
            <td style="border-top-width: 2px">
              @if($group->users->sortBy(function($user) { return strtolower($user->name); })->first()->refresh_token)
                <i class="fa fa-check text-success"></i>
              @else
                <i class="fa fa-exclamation-triangle text-danger"></i>
              @endif
            </td>
            <td style="border-top-width: 2px">
              <span data-toggle="tooltip" title="" data-original-title="{{ $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->last_login }}">
                {{ human_diff($group->users->sortBy(function($user) { return strtolower($user->name); })->first()->last_login) }}
              </span>
            </td>
            <td style="border-top-width: 2px">{{ $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->last_login_source }}</td>
            <td style="border-top-width: 2px">
              <div class="btn-group">
                <a href="{{ route('configuration.users.edit', ['user_id' => $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->id]) }}"
                   class="btn btn-warning btn-xs">
                  <i class="fa fa-pencil"></i>
                  {{ trans('web::seat.edit') }}
                </a>
                @if(auth()->user()->id != $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->id)
                <a href="{{ route('configuration.users.impersonate', ['user_id' => $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->id]) }}"
                   class="btn btn-default btn-xs">
                  <i class="fa fa-user-secret"></i>
                  {{ trans('web::seat.impersonate') }}
                </a>
                <a href="{{ route('configuration.users.delete', ['user_id' => $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->id]) }}"
                   class="btn btn-danger btn-xs confirmlink">
                  <i class="fa fa-trash"></i>
                  {{ trans('web::seat.delete') }}
                </a>
                @endif
              </div>
              @if(auth()->user()->id == $group->users->sortBy(function($user) { return strtolower($user->name); })->first()->id)
                <em class="text-danger">(This is you!)</em>
              @endif
            </td>
          </tr>

          {{-- get remaining users attached to the group in order to complete complexe row building --}}
          @foreach($group->users->sortBy(function($user) { return strtolower($user->name); })->slice(1) as $user)
          <tr>
            <td>
              {!! img('character', $user->id, 64, ['class' => 'img-circle eve-icon small-icon', 'alt' => $user->name]) !!}
              {{ $user->name }}
            </td>
            <td>
              @if($user->active)
                <span class="label label-success">{{ trans('web::seat.enabled') }}</span>
              @else
                <span class="label label-danger">{{ trans('web::seat.disabled') }}</span>
              @endif
            </td>
            <td>
              @if($user->refresh_token)
                <i class="fa fa-check text-success"></i>
              @else
                <i class="fa fa-exclamation-triangle text-danger"></i>
              @endif
            </td>
            <td>
                      <span data-toggle="tooltip" title="" data-original-title="{{ $user->last_login }}">
                        {{ human_diff($user->last_login) }}
                      </span>
            </td>
            <td>{{ $user->last_login_source }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('configuration.users.edit', ['user_id' => $user->id]) }}"
                   class="btn btn-warning btn-xs">
                  <i class="fa fa-pencil"></i>
                  {{ trans('web::seat.edit') }}
                </a>
                @if(auth()->user()->id != $user->id)
                <a href="{{ route('configuration.users.impersonate', ['user_id' => $user->id]) }}"
                   class="btn btn-default btn-xs">
                  <i class="fa fa-user-secret"></i>
                  {{ trans('web::seat.impersonate') }}
                </a>
                <a href="{{ route('configuration.users.delete', ['user_id' => $user->id]) }}"
                   class="btn btn-danger btn-xs confirmlink">
                  <i class="fa fa-trash"></i>
                  {{ trans('web::seat.delete') }}
                </a>
                @endif
              </div>
              @if(auth()->user()->id == $user->id)
                <em class="text-danger">(This is you!)</em>
              @endif
            </td>
          </tr>
          @endforeach

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($groups) }}</b> {{ trans_choice('web::seat.group', count($groups)) }}
    </div>

  </div>

@stop
