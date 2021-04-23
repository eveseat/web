<!-- Header Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-gray">

  <!-- Sidebar toggle button-->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a href="#" class="nav-link" data-widget="pushmenu">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- search form -->
  <form action="{{ route('support.search') }}" method="get" class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input type="text" name="q" class="form-control form-control-navbar" placeholder="{{ trans('web::seat.search') }}...">
      <div class="input-group-append">
        <button type="submit" id="search-btn" class="btn btn-navbar">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>
  <!-- /.search form -->

  <!-- Navbar Right Menu -->
  <ul class="navbar-nav ml-auto">

    <!-- Impersonation information -->
    @if(session('impersonation_origin', false))

      <li class="nav-item dropdown">
        <a href="{{ route('configuration.users.impersonate.stop') }}"
           class="nav-link" data-widget="dropdown" data-placement="bottom"
           title="{{ trans('web::seat.stop_impersonation') }}">
          <i class="fa fa-user-secret"></i>
        </a>
      </li>

  @endif

  <!-- Queue information -->
  @can('global.queue_manager')
    <li class="nav-item dropdown">
      <a href="{{ route('horizon.index') }}" class="nav-link" data-widget="dropdown" data-placement="bottom"
         title="{{ trans('web::seat.queued') }}" target="_blank">
        <i class="fas fa-truck"></i>
        <span class="badge badge-success navbar-badge" id="queue_count">0</span>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a href="{{ route('horizon.index') }}" class="nav-link" data-widget="dropdown" data-placement="bottom"
         title="{{ trans('web::seat.error') }}" target="_blank">
        <i class="fas fa-exclamation"></i>
        <span class="badge badge-danger navbar-badge" id="error_count">0</span>
      </a>
    </li>
  @endcan

    <!-- User Account Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-cogs"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        @if(auth()->user()->name != 'admin')
          <a href="{{ route('profile.view') }}" class="dropdown-item">
            <i class="fas fa-id-card"></i> {{ trans('web::seat.profile') }}
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#characterSwitchModal">
            <i class="fas fa-exchange-alt"></i> {{ trans('web::seat.switch_character') }}
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('auth.eve') }}" class="dropdown-item">
            <i class="fas fa-link"></i> {{ trans('web::seat.link_character') }}
          </a>
          <div class="dropdown-divider"></div>
        @endif
        <form action="{{ route('auth.logout') }}" method="post">
          {{ csrf_field() }}
          <button type="submit" class="btn btn-link dropdown-item">
            <i class="fas fa-sign-out-alt"></i>
            {{ trans('web::seat.sign_out') }}
          </button>
        </form>
      </div>
    </li>

  </ul>
</nav>

<!-- Character switching modal -->
@if(auth()->user()->name != 'admin')
<div class="modal fade off" id="characterSwitchModal" tabindex="-1" role="dialog"
   aria-labelledby="characterSwitchModalLabel">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="characterSwitchModalLabel">{{ trans('web::seat.switch_character') }}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

      <table class="table datatable compact table-condensed table-hover table-striped">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.user', auth()->user()->characters->count()) }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        @foreach(auth()->user()->characters as $character)

          <tr>
            <td>
              {!! img('characters', 'portrait', $character->character_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!}
              {{ $character->name }}
            </td>
            <td>
              <form method="post" action="{{ route('profile.change-character') }}">
                {!! csrf_field() !!}
                <input type="hidden" name="character_id" value="{{ $character->character_id }}" />
                <button type="submit" class="btn btn-sm btn-link">{{ trans('web::seat.use_as_main_character') }}</button>
              </form>
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
