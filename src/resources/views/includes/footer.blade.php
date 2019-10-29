<footer class="main-footer">

  <!-- To the right -->
  <div class="float-right d-none d-sm d-sm-inline">
    <i class="fa fa-server" data-toggle="tooltip" title="{{ gethostname() }}"></i>

    <i class="fa @if(optional($esi_status)->status == "ok") fa-refresh fa-spin @else fa-exclamation-triangle @endif"
       data-toggle="tooltip"
       title="{{ ucfirst(optional($esi_status)->status) }}/{{ optional($esi_status)->request_time }}ms - {{ human_diff(optional($esi_status)->created_at) }}"></i>
    |

    @if($is_rate_limited)
      <i class="fa fa-exclamation" data-toggle="tooltip"
         title="Exception threshold reached. TTL: {{ $rate_limit_ttl }}s"></i> |
    @endif

    <b>{{ trans('web::seat.render_in') }}</b> {{ number_format((microtime(true) - LARAVEL_START), 3) }}s |
    <b>{{ trans('web::seat.sde_version') }}</b> {{ setting('installed_sde', true) }} |
    <b>{{ trans('web::seat.web_version') }}</b> {{ config('web.config.version') }}
  </div>

  <!-- Default to the left -->
  <strong>{{ trans('web::seat.copyright') }} &copy; {{ date('Y') }} | <a href="https://github.com/eveseat/seat">SeAT</a></strong>
</footer>
