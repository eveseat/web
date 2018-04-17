<header class="main-header">

  <!-- Logo -->
  <a href="{{ route('home') }}" class="logo">
    <span class="logo-mini"><b>S</b>T</span>
    <span class="logo-lg">S<b>e</b>AT</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">

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
            <img src="//image.eveonline.com/Character/{{ setting('main_character_id') }}_128.jpg"
                 class="user-image" alt="User Image">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ $user->name }}</span>
          </a>
          @include('web::includes.character-selection')
        </li>

      </ul>
    </div>
  </nav>
</header>
