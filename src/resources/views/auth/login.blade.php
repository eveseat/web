@extends('web::layouts.app-mini')

@section('title', 'Sign In')

@section('content')

<div class="login-logo">
  S<b>e</b>AT | {{ trans('web::auth.sign_in') }}
</div>

<hr>

<div class="login-box-body">
  <p class="login-box-msg">
    {{ trans('web::auth.login_welcome') }}
  </p>

  <form action="{{ route('auth.login.post') }}" class="form-horizontal" method="post">
    {{ csrf_field() }}

    <div class="box-body">
      <div class="form-group has-feedback">
        <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ trans('web::auth.username') }}">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="{{ trans('web::auth.password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group">
        <div class="col-sm-10">
          <div class="checkbox pull-left">
            <label>
              <input type="checkbox" name="remember"> {{ trans('web::auth.remember') }}
            </label>
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
      <div class="pull-left">
        <a href="#">{{ trans('web::auth.forgot') }}</a><br>
        <a href="{{ route('auth.register') }}" class="text-center">{{ trans('web::auth.register') }}</a>
      </div>
      <button type="submit" class="btn btn-primary pull-right">{{ trans('web::auth.sign_in') }}</button>
    </div>
    <!-- /.box-footer -->

  </form>

</div>
<!-- /.login-box-body -->

@stop
