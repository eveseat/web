@extends('web::layouts.grids.6-6')

@section('title', trans('web::seat.mfa_setup'))
@section('page_header', trans('web::seat.mfa_setup'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">1. {{ trans('web::seat.scan_qr') }}</h3>
    </div>
    <div class="panel-body">

      <p>
        {{ trans('web::seat.scan_qr_help1') }}<br>
        {{ trans('web::seat.scan_qr_help2') }}
        <img src="{!! $qr_code_url !!}" class="text-center center-block" alt="">
      </p>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.supported_apps') }}</h3>
    </div>
    <div class="panel-body">

      <ul>
        <li class="list-header list-unstyled">{{ trans('web::seat.preferred_apps') }}</li>
        <li><a href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8">Google Authenticator for
                                                                                           iOS</a></li>
        <li><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google
                                                                                                           Authenticator
                                                                                                           for
                                                                                                           Android</a>
        </li>
        <li>
          <a href="http://apps.microsoft.com/windows/en-us/app/google-authenticator/7ea6de74-dddb-47df-92cb-40afac4d38bb">Google
                                                                                                                          Authenticator
                                                                                                                          (port)
                                                                                                                          on
                                                                                                                          Windows
                                                                                                                          app
                                                                                                                          store</a>
        </li>
        <li class="list-header list-unstyled">{{ trans('web::seat.other_apps') }}</li>
        <li><a href="https://www.authy.com/">Authy for iOS, Android, Chrome, OS X</a></li>
        <li><a href="https://fedorahosted.org/freeotp/">FreeOTP for iOS, Android and Peeble</a></li>
      </ul>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">2. {{ trans('web::seat.confirm_code') }}</h3>
    </div>
    <div class="panel-body">

      <p>
        {{ trans('web::seat.confirm_help') }}
      </p>

      <form role="form" action="{{ route('profile.mfa.setup') }}" method="post"
            class="form-horizontal">
        {{ csrf_field() }}

        <div class="box-body">

          <!-- Text input-->
          <div class="form-group">
            <label class="col-md-4 control-label" for="confirm_code">{{ trans('web::seat.code') }}</label>
            <div class="col-md-6">
              <input id="confirm_code" name="confirm_code" type="text"
                     placeholder="Confirmation Code" class="form-control input-md">
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <div class="form-group">
            <label class="col-md-4 control-label" for="submit"></label>
            <div class="col-md-4">
              <button id="submit" type="submit" class="btn btn-primary">{{ trans('web::seat.confirm_setup') }}</button>
            </div>
          </div>
        </div>
      </form>

    </div>
  </div>

@stop
