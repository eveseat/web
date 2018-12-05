@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.settings'))
@section('page_header', trans('web::seat.settings'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.settings') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('seat.update.settings') }}" method="post"
            class="form-horizontal">
        {{ csrf_field() }}

        <div class="box-body">

          <legend>{{ trans('web::seat.admin_email') }}</legend>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="admin_contact">{{ trans('web::seat.admin_email') }}</label>
            <div class="col-md-6">
              <input id="admin_contact" name="admin_contact" type="email"
                     class="form-control input-md" value="{{ setting('admin_contact', true) }}">
              <span class="help-block">
                {{ trans('web::seat.admin_email_help') }}
              </span>
            </div>
          </div>

          <legend>{{ trans('web::seat.maintenance') }}</legend>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="cleanup">{{ trans('web::seat.cleanup_data') }}</label>
            <div class="col-md-6">
              <select id="cleanup" name="cleanup_data" class="form-control">
                <option value="yes"
                        @if(setting('cleanup_data', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('cleanup_data', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
              <span class="help-block">
                {{ trans('web::seat.cleanup_data_help') }}
              </span>
            </div>
          </div>

          <legend>{{ trans_choice('web::seat.queue_worker', 2) }}</legend>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="worker">{{ trans_choice('web::seat.worker', 2) }}</label>
            <div class="col-md-6">
              <input id="worker" name="queue_workers" type="text"
                     class="form-control input-md" value="{{ setting('queue_workers', true) }}">
              <span class="help-block">
                {{ trans('web::seat.queue_worker_help') }}
              </span>
            </div>
          </div>

          <legend>{{ trans('web::seat.registration') }}</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="registration">{{ trans('web::seat.allow_registration') }}</label>
            <div class="col-md-6">
              <select id="registration" name="registration" class="form-control">
                <option value="yes"
                        @if(setting('registration', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('registration', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
            </div>
          </div>

          <legend>{{ trans('web::seat.google_analytics') }}</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="allow_tracking">{{ trans('web::seat.allow_tracking') }}</label>
            <div class="col-md-6">
              <select id="allow_tracking" name="allow_tracking" class="form-control">
                <option value="yes"
                        @if(setting('allow_tracking', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('allow_tracking', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
              <span class="help-block">
                {{ trans('web::seat.tracking_help') }}
                <a href="https://eveseat.github.io/docs/admin_guides/understanding_tracking/">Usage Tracking</a>
              </span>
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="tracking_id">{{ trans('web::seat.tracking_id') }}</label>
            <div class="col-md-6">
              <input id="tracking_id" name="tracking_id" type="text"
                     class="form-control input-md" value="{{ setting('analytics_id', true) }}" disabled>
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <div class="form-group">
            <label class="col-md-4 control-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" type="submit" class="btn btn-primary">
                {{ trans('web::seat.update') }}
              </button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.module_versions') }}</h3>
    </div>
    <div class="panel-body">

      <dl class="">

        <dt>SeAT Api</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('api.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://img.shields.io/packagist/v/eveseat/api.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/api" target="_blank">https://github.com/eveseat/api</a>
            </li>
          </ul>
        </dd>

        <dt>SeAT Console</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('console.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://img.shields.io/packagist/v/eveseat/console.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/console" target="_blank">https://github.com/eveseat/console</a>
            </li>
          </ul>
        </dd>

        <dt>SeAT Eveapi</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('eveapi.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://img.shields.io/packagist/v/eveseat/eveapi.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/eveapi" target="_blank">https://github.com/eveseat/eveapi</a>
            </li>
          </ul>
        </dd>

        <dt>SeAT Notifications</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('notifications.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://img.shields.io/packagist/v/eveseat/notifications.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/notifications" target="_blank">https://github.com/eveseat/notifications</a>
            </li>
          </ul>
        </dd>

        <dt>SeAT Web</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('web.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://img.shields.io/packagist/v/eveseat/web.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/web" target="_blank">https://github.com/eveseat/web</a>
            </li>
          </ul>
        </dd>

        <dt>SeAT Services</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('services.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://img.shields.io/packagist/v/eveseat/services.svg?style=flat-square"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/services" target="_blank">https://github.com/eveseat/services</a>
            </li>
          </ul>
        </dd>

      </dl>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.tp_versions') }}</h3>
    </div>
    <div class="panel-body">

      <dl>

        <dt>Eve Online SDE</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>{{ setting('installed_sde', true) }}</b></li>
            <li id="live-sde-version">{{ trans('web::seat.current') }}: <img
                      src="https://img.shields.io/badge/version-loading...-blue.svg?style=flat-square"></li>
          </ul>
        </dd>

      </dl>

    </div>

  </div>

@stop

@push('javascript')
<script type="text/javascript">
  $(document).ready(function () {
    jQuery.get("{{ route('check.sde') }}", function (data) {
      var live_sde = "error";
      if (data != null) {
        live_sde = data.version;
      }
      $('#live-sde-version img').attr('src', 'https://img.shields.io/badge/version-' + live_sde + '-blue.svg?style=flat-square');
    });
  });
</script>
@endpush
