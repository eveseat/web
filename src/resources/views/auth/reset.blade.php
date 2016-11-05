@extends('web::layouts.app-mini')

@section('title', trans('web::seat.reset_topic'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.reset_topic') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      {{ trans('web::seat.reset_token_welcome') }}
    </p>

    <form action="{{ route('password.reset.post') }}" class="form-horizontal" method="post">
    {{ csrf_field() }}

    <!-- The reset token from the Email -->
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="box-body">
        <div class="form-group has-feedback">
          <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                 placeholder="{{ trans('web::seat.email') }}">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" name="password" class="form-control" placeholder="{{ trans('web::seat.password') }}">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" name="password_confirmation" class="form-control"
                 placeholder="{{ trans('web::seat.password_again') }}">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <div class="pull-left">
          <a href="{{ route('auth.login') }}" class="text-center">{{ trans('web::seat.remember') }}</a>
        </div>
        <button type="submit" class="btn btn-warning pull-right">{{ trans('web::seat.reset_topic') }}</button>
      </div>
      <!-- /.box-footer -->

    </form>

  </div>
  <!-- /.login-box-body -->

@stop
