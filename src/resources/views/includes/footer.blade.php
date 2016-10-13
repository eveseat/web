<footer class="main-footer">

  <!-- To the right -->
  <div class="pull-right hidden-xs">
    <b>{{ trans('web::seat.render_in') }}</b> {{ number((microtime(true) - LARAVEL_START), 3) }}ms |
    <b>{{ trans('web::seat.sde_version') }}</b> {{ setting('installed_sde', true) }}
    <b>{{ trans('web::seat.web_version') }}</b> {{ config('web.config.version') }}
  </div>

  <!-- Default to the left -->
  <strong>{{ trans('web::seat.copyright') }} &copy; {{ date('Y') }} | <a href="https://github.com/eveseat/seat">SeAT</a></strong>
</footer>
