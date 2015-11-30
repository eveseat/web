@extends('web::layouts.grids.6-6')

@section('title', 'SeAT Settings')
@section('page_header', 'SeAT Settings')

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">SeAT Settings</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('seat.update.settings') }}" method="post"
            class="form-horizontal">
        {{ csrf_field() }}

        <div class="box-body">

          <legend>Registration</legend>

          <!-- Select Basic -->
          <div class="form-group">
            <label class="col-md-4 control-label" for="registration">Allow Registration</label>
            <div class="col-md-6">
              <select id="registration" name="registration" class="form-control">
                <option value="yes"
                        @if(setting('registration', true) == "yes") selected @endif>
                  Yes
                </option>
                <option value="no"
                        @if(setting('registration', true) == "no") selected @endif>
                  No
                </option>
              </select>
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <div class="form-group">
            <label class="col-md-4 control-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" type="submit" class="btn btn-primary">Update</button>
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
      <h3 class="panel-title">SeAT Module Versions</h3>
    </div>
    <div class="panel-body">

      <dl class="">

        <dt>SeAT Api</dt>
        <dd>
          <ul>
            <li>Installed: <b>v{{ config('api.config.version') }}</b></li>
            <li>Current: <img src="https://poser.pugx.org/eveseat/api/v/stable"></li>
            <li>Repository Url: <a href="https://github.com/eveseat/api" target="_blank">https://github.com/eveseat/api</a></li>
          </ul>
        </dd>

        <dt>SeAT Console</dt>
        <dd>
          <ul>
            <li>Installed: <b>v{{ config('console.config.version') }}</b></li>
            <li>Current: <img src="https://poser.pugx.org/eveseat/console/v/stable"></li>
            <li>Repository Url: <a href="https://github.com/eveseat/console" target="_blank">https://github.com/eveseat/console</a></li>
          </ul>
        </dd>

        <dt>SeAT Eveapi</dt>
        <dd>
          <ul>
            <li>Installed: <b>v{{ config('eveapi.config.version') }}</b></li>
            <li>Current: <img src="https://poser.pugx.org/eveseat/eveapi/v/stable"></li>
            <li>Repository Url: <a href="https://github.com/eveseat/eveapi" target="_blank">https://github.com/eveseat/eveapi</a></li>
          </ul>
        </dd>

        <dt>SeAT Web</dt>
        <dd>
          <ul>
            <li>Installed: <b>v{{ config('web.config.version') }}</b></li>
            <li>Current: <img src="https://poser.pugx.org/eveseat/web/v/stable"></li>
            <li>Repository Url: <a href="https://github.com/eveseat/web" target="_blank">https://github.com/eveseat/web</a></li>
          </ul>
        </dd>

        <dt>SeAT Services</dt>
        <dd>
          <ul>
            <li>Installed: <b>v{{ config('services.config.version') }}</b></li>
            <li>Current: <img src="https://poser.pugx.org/eveseat/services/v/stable"></li>
            <li>Repository Url: <a href="https://github.com/eveseat/services" target="_blank">https://github.com/eveseat/services</a></li>
          </ul>
        </dd>

      </dl>

    </div>
  </div>

@stop

