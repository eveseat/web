<footer class="footer d-print-none">
  <div class="container-fluid">
    <div class="row text-center align-items-center flex-row-reverse">
      <div class="col-lg-auto ms-lg-auto">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            <a href="https://docs.eveseat.net" class="link-secondary">Documentation</a>
          </li>
          <li class="list-inline-item d-none d-xxl-inline-block">
            <a href="https://github.com/eveseat/seat/blob/master/LICENSE" class="link-secondary">License</a>
          </li>
          <li class="list-inline-item">
            <a href="https://github.com/eveseat/" class="link-secondary">
              <i class="fab fa-github"></i>
              Source code
            </a>
          </li>
        </ul>
      </div>
      <div class="col-12 col-lg-auto mt-3 mt-lg-0">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            {{ trans('web::seat.copyright') }} &copy; {{ date('Y') }} <a href="https://github.com/eveseat/seat" class="link-secondary" target="_blank">SeAT</a>
          </li>
          <li class="list-inline-item d-none d-lg-inline-block">
            <b>{{ trans('web::seat.render_in') }}</b> {{ number_format((microtime(true) - LARAVEL_START), 3) }}s
          </li>
          <li class="list-inline-item d-none d-lg-inline-block">
            <b>{{ trans('web::seat.sde_version') }}</b> {{ setting('installed_sde', true) }}
          </li>
          <li class="list-inline-item">
            @if(file_exists(storage_path('version')))
              <b>{{ trans('web::seat.docker_version') }}</b> {{ file_get_contents(storage_path('version')) }}
            @else
              <b>{{ trans('web::seat.web_version') }}</b> {{ Composer\InstalledVersions::getPrettyVersion('eveseat/web') ?? trans('web::seat.unknown') }}
            @endif
          </li>
          <li class="list-inline-item d-none d-lg-inline-block">
            <i class="fas fa-server" data-bs-toggle="tooltip" title="{{ gethostname() }}"></i>
            <i class="@if(optional($esi_status)->status == "ok") fas fa-sync-alt fa-spin text-green @else fas fa-exclamation-triangle text-danger @endif"
               data-bs-toggle="tooltip"
               title="{{ ucfirst(optional($esi_status)->status) }}/{{ optional($esi_status)->request_time }}ms - {{ human_diff(optional($esi_status)->created_at) }}"></i>
            @if($is_rate_limited)
              <i class="fas fa-exclamation text-warning" data-bs-toggle="tooltip"
                 title="Exception threshold reached. TTL: {{ $rate_limit_ttl }}s"></i> |
            @endif
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>
