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

          <legend>{{ trans('web::seat.single_signon') }}</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="allow_sso">{{ trans('web::seat.allow_sso') }}</label>
            <div class="col-md-6">
              <select id="allow_sso" name="allow_sso" class="form-control">
                <option value="yes"
                        @if(setting('allow_sso', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('allow_sso', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
              @if($warn_sso)
                <span class="help-block">
                  <span class="text text-danger">
                    {{ trans('web::seat.admin_warn_sso') }}
                  </span>
                </span>
              @endif
            </div>
          </div>

          <legend>{{ trans('web::seat.min_access_mask') }}</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="force_min_mask">{{ trans('web::seat.force_min_mask') }}</label>
            <div class="col-md-6">
              <select id="min_access_mask" name="force_min_mask" class="form-control">
                <option value="yes"
                        @if(setting('force_min_mask', true) == "yes") selected @endif>
                  {{ trans('web::seat.yes') }}
                </option>
                <option value="no"
                        @if(setting('force_min_mask', true) == "no") selected @endif>
                  {{ trans('web::seat.no') }}
                </option>
              </select>
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="min_access_mask">{{ trans('web::seat.min_access_mask') }}</label>
            <div class="col-md-6">
              <input id="min_access_mask" name="min_access_mask" type="text"
                     class="form-control input-md" value="{{ setting('min_access_mask', true) }}">
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
            <li>{{ trans('web::seat.current') }}: <img src="https://poser.pugx.org/eveseat/api/v/stable"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/api" target="_blank">https://github.com/eveseat/api</a></li>
          </ul>
        </dd>

        <dt>SeAT Console</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('console.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://poser.pugx.org/eveseat/console/v/stable"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/console" target="_blank">https://github.com/eveseat/console</a></li>
          </ul>
        </dd>

        <dt>SeAT Eveapi</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('eveapi.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://poser.pugx.org/eveseat/eveapi/v/stable"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/eveapi" target="_blank">https://github.com/eveseat/eveapi</a></li>
          </ul>
        </dd>

        <dt>SeAT Notifications</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('notifications.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://poser.pugx.org/eveseat/notifications/v/stable"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/notifications" target="_blank">https://github.com/eveseat/notifications</a></li>
          </ul>
        </dd>

        <dt>SeAT Web</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('web.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://poser.pugx.org/eveseat/web/v/stable"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/web" target="_blank">https://github.com/eveseat/web</a></li>
          </ul>
        </dd>

        <dt>SeAT Services</dt>
        <dd>
          <ul>
            <li>{{ trans('web::seat.installed') }}: <b>v{{ config('services.config.version') }}</b></li>
            <li>{{ trans('web::seat.current') }}: <img src="https://poser.pugx.org/eveseat/services/v/stable"></li>
            <li>{{ trans('web::seat.url') }}: <a href="https://github.com/eveseat/services" target="_blank">https://github.com/eveseat/services</a></li>
          </ul>
        </dd>

      </dl>

    </div>
  </div>

@stop
