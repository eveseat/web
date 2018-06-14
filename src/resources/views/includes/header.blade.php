<header class="main-header">

  <!-- Logo -->
  <a href="{{ route('home') }}" class="logo">
    <span class="logo-mini"><b>S</b>T</span>
    <span class="logo-lg">S<b>e</b>AT</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top">

    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">{{ trans('web::seat.toggle_navigation') }}</span>
    </a>

    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- Impersonation information -->
        @if(session('impersonation_origin', false))

          <li class="dropdown">
            <a href="{{ route('configuration.users.impersonate.stop') }}"
               class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom"
               title="{{ trans('web::seat.stop_impersonation') }}">
              <i class="fa fa-user-secret"></i>
            </a>
          </li>

      @endif

      <!-- Queue information -->
        <li class="dropdown">
          <a href="{{ auth()->user()->has('queue_manager', false) ? route('horizon.index') : '#queue_count' }}"
             class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom"
             title="{{ trans('web::seat.queued') }}">
            <i class="fa fa-truck"></i>
            <span class="label label-success" id="queue_count">0</span>
          </a>
        </li>
        <li class="dropdown">
          <a href="{{ auth()->user()->has('queue_manager', false) ? route('horizon.index') : '#error_count' }}"
             class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom"
             title="{{ trans('web::seat.error') }}">
            <i class="fa fa-exclamation"></i>
            <span class="label label-danger" id="error_count">0</span>
          </a>
        </li>

        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <img src="//image.eveonline.com/Character/{{ $user->character_id }}_128.jpg"
                 class="user-image" alt="User Image">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ $user->name }}</span>
          </a>

          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <img src="//image.eveonline.com/Character/{{ $user->id }}_256.jpg"
                   class="img-circle" alt="User Image">
              <p>
                {{ $user->name }}
                <small>{{ trans('web::seat.joined') }}: {{ human_diff($user->created_at) }}</small>
                @if(auth()->user()->name != 'admin')
                <small>{{ count(auth()->user()->associatedCharacterIds()) }}
                  {{ trans_choice('web::seat.characters_in_group', count(auth()->user()->associatedCharacterIds())) }}</small>
                @endif
              </p>
            </li>

            @if(auth()->user()->name != 'admin')
            <li class="user-body">
              <div class="row">
                <div class="col-xs-6 text-center">
                  <a class="btn btn-default btn-flat" type="button" data-toggle="modal" data-target="#characterSwitchModal">
                    {{ trans('web::seat.switch_character') }}</a>
                </div>
                <div class="col-xs-6 text-center">
                  <a class="btn btn-default btn-flat" href="{{ route('auth.eve') }}">{{ trans('web::seat.link_character') }}</a>
                </div>
              </div>
            </li>
            @endif

            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ route('profile.view') }}"
                   class="btn btn-default btn-flat">{{ trans('web::seat.profile') }}</a>
              </div>
              <div class="pull-right">
                <form action="{{ route('auth.logout') }}" method="post">
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-default btn-flat">
                    {{ trans('web::seat.sign_out') }}
                  </button>
                </form>
              </div>
            </li>
          </ul>

        </li>

      </ul>
    </div>
  </nav>
</header>

<!-- Character switching modal -->
@if(auth()->user()->name != 'admin')
<div class="modal fade off" id="characterSwitchModal" tabindex="-1" role="dialog"
     aria-labelledby="characterSwitchModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="characterSwitchModalLabel">{{ trans('web::seat.switch_character') }}</h4>
      </div>
      <div class="modal-body">

        <table class="table datatable compact table-condensed table-hover table-responsive">
          <thead>
          <tr>
            <th>{{ trans_choice('web::seat.user', count(auth()->user()->group->users)) }}</th>
            <th></th>
          </tr>
          </thead>
          <tbody>

          @foreach(auth()->user()->group->users as $user)

            <tr>
              <td>
                {!! img('character', $user->character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                {{ $user->name }}
              </td>
              <td>
                <a href="{{ route('profile.change-character', ['character_id' => $user->character_id]) }}">
                  {{ trans('web::seat.switch_character') }}
                </a>
              </td>
            </tr>

          @endforeach

          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>
@endif