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

        @foreach($groups->sortBy(function($item, $key) { return strtolower(optional($item->main_character)->name); }) as $group)

          <tr>
            <td>
              {!! img('character', optional($group->main_character)->character_id, 64, ['class' => 'img-circle eve-icon medium-icon']) !!}
              <span>{{ optional($group->main_character)->name }}</span>
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

              <ul class="list-group">
                @foreach($group->users->sortBy(function($item) { return strtolower($item->name); }) as $user)

                  <li class="list-group-item">

                    <div class="container-fluid">
                      <div class="row">
                        <!-- token status -->
                        @if($user->refresh_token)
                          <button data-toggle="tooltip" title="{{ trans('web::seat.valid_token') }}"
                                  class="btn btn-xs btn-link">
                            <i class="fa fa-check text-success"></i>
                          </button>
                        @else
                          <button data-toggle="tooltip" title="{{ trans('web::seat.invalid_token') }}"
                                  class="btn btn-xs btn-link">
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                          </button>
                        @endif

                      <!-- user information -->
                        {!! img('character', $user->id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                        {{ $user->name }}
                        (last logged in {{ human_diff($user->last_login) }} from {{ $user->last_login_source }})
                        <!-- actions -->
                        <div class="btn-group btn-group-xs pull-right" role="group">

                          @if(auth()->user()->id != $user->id)
                            <a href="{{ route('configuration.users.impersonate', ['user_id' => $user->id]) }}"
                               title="{{ trans('web::seat.impersonate') }}" class="btn btn-default">
                              <i class="fa fa-user-secret"></i> {{ trans('web::seat.impersonate') }}
                            </a>

                          @else
                            <a class="btn disabled btn-link">
                              <i class="fa fa-user"></i> <em class="text-danger">(This is you!)</em>
                            </a>
                          @endif

                          <a href="{{ route('configuration.users.edit', ['user_id' => $user->id]) }}"
                             title="{{ trans('web::seat.edit') }}" class="btn btn-warning">
                            <i class="fa fa-pencil"></i> {{ trans('web::seat.edit') }}
                          </a>

                          @if(auth()->user()->id != $user->id)
                            <a href="{{ route('configuration.users.delete', ['user_id' => $user->id]) }}"
                               title="{{ trans('web::seat.delete') }}"
                               class="confirmlink btn btn-danger">
                              <i class="fa fa-times"></i> {{ trans('web::seat.delete') }}
                            </a>
                          @else
                            <a href="{{ route('configuration.users.delete', ['user_id' => $user->id]) }}"
                               title="{{ trans('web::seat.delete') }}"
                               class="confirmlink disabled btn-danger btn">
                              <i class="fa fa-times"></i> {{ trans('web::seat.delete') }}
                            </a>
                          @endif

                        </div>
                      </div>

                    </div>

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