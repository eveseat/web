@extends('web::layouts.app-mini')

@section('title', trans('web::seat.sign_in'))

@section('content')

<div class="login-logo">
  S<b>e</b>AT | {{ trans('web::seat.sign_in') }}
</div>

<hr>

<div class="login-box-body">
  <p class="login-box-msg">
    {{ trans('web::seat.login_welcome') }}
  </p>

  <form action="{{ route('auth.login.post') }}" class="form-horizontal" method="post">
    {{ csrf_field() }}

    <div class="box-body">
      <div class="form-group has-feedback">
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ trans('web::seat.username') }}">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="{{ trans('web::seat.password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group">
        <div class="col-sm-10">
          <div class="checkbox pull-left">
            <label>
              <input type="checkbox" name="remember"> {{ trans('web::seat.remember_me') }}
            </label>
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
      <div class="pull-left">
        <a href="{{ route('password.email') }}">{{ trans('web::seat.forgot') }}</a><br>
        <a href="{{ route('auth.register') }}" class="text-center">{{ trans('web::seat.register') }}</a>
      </div>
      <button type="submit" class="btn btn-primary pull-right">{{ trans('web::seat.sign_in') }}</button>
    </div>
    <!-- /.box-footer -->

    <!-- SSO Button! -->
    @if(setting('allow_sso', true) === 'yes')
      <div class="box-footer text-center">
        <a href="{{ route('auth.eve') }}">
          <img src="{{ asset('web/img/evesso.png') }}">
        </a>
      </div>
      <!-- /.box-footer -->
    @endif

  </form>

</div>
<!-- /.login-box-body -->

@stop
