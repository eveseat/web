<footer class="main-footer">

  <!-- To the right -->
  <div class="float-right d-none d-sm d-sm-inline">
    <i class="fas fa-server" data-toggle="tooltip" title="{{ gethostname() }}"></i>

    <i class="@if(optional($esi_status)->status == "ok") fas fa-sync-alt fa-spin text-green @else fas fa-exclamation-triangle text-danger @endif"
       data-toggle="tooltip"
       title="{{ ucfirst(optional($esi_status)->status) }}/{{ optional($esi_status)->request_time }}ms - {{ human_diff(optional($esi_status)->created_at) }}"></i>
    |

    @if($is_rate_limited)
      <i class="fas fa-exclamation text-warning" data-toggle="tooltip"
         title="Exception threshold reached. TTL: {{ $rate_limit_ttl }}s"></i> |
    @endif

    <b>{{ trans('web::seat.render_in') }}</b> {{ number_format((microtime(true) - LARAVEL_START), 3) }}s |
    @php
      $ccp_sde = setting('installed_ccp_sde', true);
      $sde_version = $ccp_sde
        ? sprintf('Fuzzwork %s + CCP %s', setting('installed_sde', true), $ccp_sde)
        : setting('installed_sde', true);
    @endphp
    <b>{{ trans('web::seat.sde_version') }}</b> {{ $sde_version }}
    @if($ccp_sde)
      @can('global.superuser')
        @php
          // Only flagged when this SeAT version installs a newer build, so there is something to run.
          // CCP publishes a build most days, which the Settings page reports instead.
          $ccp_sde_pinned = method_exists(\Seat\Eveapi\Database\Seeders\CcpSdeSeeder::class, 'getVersion')
            ? (new \Seat\Eveapi\Database\Seeders\CcpSdeSeeder)->getVersion()
            : null;
          $ccp_sde_outdated = $ccp_sde_pinned && (int) strtok((string) $ccp_sde, ' ') < (int) $ccp_sde_pinned;
        @endphp
        @if($ccp_sde_outdated)
          <a href="{{ route('seatcore::seat.settings.view') }}" class="badge badge-danger" data-toggle="tooltip"
             title="{{ trans('web::seat.ccp_sde_hint_outdated', ['build' => $ccp_sde_pinned]) }}">{{ trans('web::seat.ccp_sde_status_outdated') }}</a>
        @endif
      @endcan
    @endif
    |
    @if(file_exists(storage_path('version')))
      <b>{{ trans('web::seat.docker_version') }}</b> {{ file_get_contents(storage_path('version')) }}
    @else
      <b>{{ trans('web::seat.web_version') }}</b> {{ Composer\InstalledVersions::getPrettyVersion('eveseat/web') ?? trans('web::seat.unknown') }}
    @endif
  </div>

  <!-- Default to the left -->
  <strong>{{ trans('web::seat.copyright') }} &copy; {{ date('Y') }} | <a href="https://github.com/eveseat/seat" target="_blank">SeAT</a></strong>
</footer>
