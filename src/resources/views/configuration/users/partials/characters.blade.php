<ul class="list-group no-margin">
  @foreach($row->users->sortBy(function($item) { return strtolower($item->name); }) as $user)
    <li class="list-group-item no-border bg-none">
      <div class="media">
        <div class="media-left media-middle hidden-xs hidden-sm">
          <!-- user avatar -->
          {!! img('character', $user->id, 64, ['class' => 'img-circle eve-icon medium-icon media-object', 'alt' => $user->name], false) !!}
        </div>
        <div class="media-body">
          <!-- user information -->
          <div class="col-xs-12 col-sm-6 col-md-6">
            <ul class="list-unstyled">
              <li>
                <span class="users-list-name">
                  <!-- token status -->
                  @if($user->refresh_token)
                    <i class="fa fa-check text-success" data-toggle="tooltip" data-placement="top" title="{{ trans('web::seat.valid_token') }}"></i>
                  @else
                    <i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('web::seat.invalid_token') }}"></i>
                  @endif
                  {{ $user->name }}
                </span>
              </li>
              <li>(last logged in {{ human_diff($user->last_login) }} from {{ $user->last_login_source }})</li>
              <li class="hidden-sm hidden-md hidden-lg">
                <!-- actions -->
                <div class="btn-group btn-group-justified btn-group-sm" role="group">

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
              </li>
            </ul>
          </div>
          <!-- actions -->
          <div class="hidden-xs col-sm-6 col-md-6">
            <div class="btn-group-vertical btn-group-xs pull-right" role="group">

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
      </div>
    </li>
  @endforeach
</ul>