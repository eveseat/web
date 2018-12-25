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
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs pull-right">
      <li>
        <a href="#plugin-packages" data-toggle="tab" aria-expanded="false">Plugins</a>
      </li>
      <li class="active">
        <a href="#core-packages" data-toggle="tab" aria-expanded="true">Core</a>
      </li>
      <li class="pull-left header">
        <i class="fa fa-code-fork"></i>
        {{ trans('web::seat.module_versions') }}
      </li>
    </ul>
    <div class="tab-content">
      <div id="core-packages" class="tab-pane active">
        <dl class="">

          @foreach($packages->core as $package)
            <dt>{{ call_user_func([$package, 'getName']) }}</dt>
            <dd>
              <ul>
                <li>{{ trans('web::seat.installed') }}: <b>v{{ call_user_func([$package, 'getVersion']) }}</b></li>
                <li>{{ trans('web::seat.current') }}: <img src="{{ call_user_func([$package, 'getVersionBadge']) }}" /></li>
                <li>{{ trans('web::seat.url') }}: <a href="{{ call_user_func([$package, 'getPackageRepositoryUrl']) }}" target="_blank">{{ call_user_func([$package, 'getPackageRepositoryUrl']) }}</a> </li>
              </ul>
            </dd>
          @endforeach

        </dl>
      </div>
      <div id="plugin-packages" class="tab-pane">
        <dl class="">

          @foreach($packages->plugins as $package)
            <dt>{{ call_user_func([$package, 'getName']) }}</dt>
            <dd>
              <ul>
                <li>{{ trans('web::seat.installed') }}: <b>v{{ call_user_func([$package, 'getVersion']) }}</b></li>
                <li>{{ trans('web::seat.current') }}: <img src="{{ call_user_func([$package, 'getVersionBadge']) }}" /></li>
                <li>{{ trans('web::seat.url') }}: <a href="{{ call_user_func([$package, 'getPackageRepositoryUrl']) }}" target="_blank">{{ call_user_func([$package, 'getPackageRepositoryUrl']) }}</a> </li>
              </ul>
            </dd>
          @endforeach

        </dl>
      </div>
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
