@extends('web::layouts.app-mini')

@section('title', 'Password Reset')

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::passwords.reset_topic') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      {{ trans('web::passwords.reset_welcome') }}
    </p>

    <form action="{{ route('password.email.post') }}" class="form-horizontal" method="post">
      {{ csrf_field() }}

      <div class="box-body">
        <div class="form-group has-feedback">
          <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ trans('web::auth.email') }}">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <div class="pull-left">
          <a href="{{ route('auth.login') }}" class="text-center">{{ trans('web::passwords.remember') }}</a>
        </div>
        <button type="submit" class="btn btn-warning pull-right">{{ trans('web::passwords.reset_topic') }}</button>
      </div>
      <!-- /.box-footer -->

    </form>

  </div>
  <!-- /.login-box-body -->

@stop
