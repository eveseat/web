<header class="main-header">

  <!-- Logo -->
  <a href="{{ route('home') }}" class="logo">
    <span class="logo-mini"><b>S</b>T</span>
    <span class="logo-lg">S<b>e</b>AT</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">

    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- Queue information -->
        <li class="dropdown">
          <a href="{{ route('queue.status') }}" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom"
             title="Queued">
            <i class="fa fa-truck"></i>
            <span class="label label-success" id="queue_count">0</span>
          </a>
        </li>
        <li class="dropdown">
          <a href="{{ route('queue.status') }}" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom"
             title="Working">
            <i class="fa fa-exchange"></i>
            <span class="label label-warning" id="working_count">0</span>
          </a>
        </li>
        <li class="dropdown">
          <a href="{{ route('queue.status') }}" class="dropdown-toggle" data-toggle="tooltip" data-placement="bottom"
             title="Error">
            <i class="fa fa-exclamation"></i>
            <span class="label label-danger" id="error_count">0</span>
          </a>
        </li>

        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <img src="http://image.eveonline.com/Character/{{ setting('main_character_id') }}_128.jpg"
                 class="user-image" alt="User Image">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ $user->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <img src="http://image.eveonline.com/Character/{{ setting('main_character_id') }}_256.jpg"
                   class="img-circle" alt="User Image">

              <p>
                {{ $user->name }}
                <small>Joined: {{ human_diff($user->created_at) }} </small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ route('profile.view') }}" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <a href="{{ route('auth.logout') }}" class="btn btn-default btn-flat">Sign out</a>
              </div>
            </li>
          </ul>
        </li>

      </ul>
    </div>
  </nav>
</header>
