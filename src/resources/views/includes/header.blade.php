<!-- Header Navbar -->
<header class="navbar navbar-expand-md navbar-light sticky-top d-lg-flex d-print-none">
  <div class="container-fluid">
    <!-- sidebar-toggle-button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#section-menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- ./sidebar-toggle-button -->
    <div class="navbar-nav flex-row order-md-last">
      <!-- TODO : load character/corporation/alliance active menu entries into yield main-menu -->
      <!-- job-queue -->
      @can('global.queue_manager')
      <div class="nav-item d-md-flex me-3">
        <a href="{{ route('horizon.index') }}" class="nav-link px-0" tabindex="-1" aria-label="Show dashboard" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('web::seat.queued') }}">
          <i class="fas fa-hourglass-half fa-lg"></i>
          <span class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-success" id="queue_count">0</span>
        </a>
      </div>
      <div class="nav-item d-md-flex me-3">
        <a href="{{ route('horizon.index') }}" class="nav-link px-0" tabindex="-1" aria-label="Show dashboard" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('web::seat.error') }}">
          <i class="fas fa-exclamation fa-lg"></i>
          <span class="position-absolute top-5 start-100 translate-middle badge rounded-pill bg-danger" id="error_count">0</span>
        </a>
      </div>
      @endcan
      <!-- ./job-queue -->
      <!-- impersonation -->
      @if(session('impersonation_origin', false))
      <div class="nav-item d-md-flex me-3">
        <a href="{{ route('seatcore::configuration.users.impersonate.stop') }}" class="nav-link px-0" tabindex="-1" aria-label="{{ trans('web::seat.stop_impersonation') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ trans('web::seat.stop_impersonation') }}">
          <i class="fas fa-user-secret fa-lg"></i>
        </a>
      </div>
      @endif
      <!-- ./impersonation -->
      <!-- user-card -->
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          @include('web::partials.user-icon', ['user' => auth()->user()])
          <div class="d-xl-block ps-2">
            <div>{{ auth()->user()->name }}</div>
            <div class="mt-1 small text-muted">{{ auth()->user()->characters->count() }} characters</div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          @if(auth()->user()->name != 'admin')
          <!-- character-roster -->
          @foreach(auth()->user()->characters as $character)
          <a href="{{ route('seatcore::character.view.default', ['character' => $character->character_id]) }}" class="dropdown-item">
            {!! img('characters', 'portrait', $character->character_id, 32, ['class' => 'avatar avatar-sm me-2'], false) !!}
            {{ $character->name }}
          </a>
          @endforeach
          <!-- ./character-roster -->
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#characterSwitchModal">
            <span class="nav-link-icon">
              <i class="fas fa-user-friends"></i>
            </span>
            <span class="nav-link-title">{{ trans('web::seat.switch_character') }}</span>
          </a>
          <a href="{{ route('seatcore::auth.eve') }}" class="dropdown-item">
            <span class="nav-link-icon">
              <i class="fas fa-link"></i>
            </span>
            <span class="nav-link-title">{{ trans('web::seat.link_character') }}</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('seatcore::profile.view') }}" class="dropdown-item">
            <span class="nav-link-icon">
              <i class="fas fa-user-cog"></i>
            </span>
            <span class="nav-link-title">Profile</span>
          </a>
          @endif
          <a href="{{ route('seatcore::auth.logout') }}" class="dropdown-item">
            <span class="nav-link-icon">
              <i class="fas fa-sign-out-alt"></i>
            </span>
            <span class="nav-link-title">{{ trans('web::seat.sign_out') }}</span>
          </a>
        </div>
      </div>
      <!-- ./user-card -->
    </div>
    <div class="collapse navbar-collapse" id="section-menu">
      <!-- search-form -->
      <form action="{{ route('seatcore::support.search') }}" method="get" class="me-4">
        <div class="input-icon">
          <span class="input-icon-addon">
            <i class="fas fa-search"></i>
          </span>
          <input type="text" class="form-control" placeholder="{{ trans('web::seat.search') }}..." aria-label="Search in website" />
        </div>
      </form>
      <!-- /.search-form -->
      <!-- section-menu -->
      @include('web::includes.menu.section.wrapper')
      <!-- /.section-menu -->
    </div>
  </div>
</header>