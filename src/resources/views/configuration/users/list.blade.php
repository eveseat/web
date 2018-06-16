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
          <th>{{ trans('web::seat.email') }}</th>
          <th>{{ trans_choice('web::seat.character', 2) }}</th>
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
            <td>{{ $group->email }}</td>
            <td>

              <ul class="list-unstyled">
                @foreach($group->users as $user)
                  <li>
                    <!-- token status -->
                    @if($user->refresh_token)
                      <span data-toggle="tooltip" title="{{ trans('web::seat.valid_token') }}">
                        <i class="fa fa-check text-success"></i>
                      </span>
                    @else
                      <span data-toggle="tooltip" title="{{ trans('web::seat.invalid_token') }}">
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                      </span>
                    @endif
                    |
                    <!-- actions -->
                    <a href="{{ route('configuration.users.edit', ['user_id' => $user->id]) }}"
                       data-toggle="tooltip" title="{{ trans('web::seat.edit') }}">
                      <i style="color: #333;" class="fa fa-pencil"></i>
                    </a>

                    @if(auth()->user()->id != $user->id)
                      <a href="{{ route('configuration.users.delete', ['user_id' => $user->id]) }}"
                         data-toggle="tooltip" title="{{ trans('web::seat.delete') }}"
                         class="confirmlink">
                        <i style="color: #333;" class="fa fa-times"></i>
                      </a>
                      <a href="{{ route('configuration.users.impersonate', ['user_id' => $user->id]) }}"
                         data-toggle="tooltip" title="{{ trans('web::seat.impersonate') }}">
                        <i style="color: #333;" class="fa fa-user-secret"></i>
                      </a>
                    @else
                      <em class="text-danger">(This is you!)</em>
                    @endif
                    |
                    <!-- user information -->
                    {!! img('character', $user->id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $user->name }}
                    (last logged in {{ human_diff($user->last_login) }} from {{ $user->last_login_source }})

                  </li>
                @endforeach
              </ul>

            </td>
          </tr>

        @endforeach
        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      <b>{{ count($groups) }}</b> {{ trans_choice('web::seat.group', count($groups)) }} |
      <b>{{ $groups->map(function($r) { return $r->users->count(); })->sum() }}</b> {{ trans_choice('web::seat.user', 2) }}
    </div>

  </div>

@stop