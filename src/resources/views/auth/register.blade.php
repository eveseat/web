@extends('web::layouts.app-mini')

@section('title', trans('web::seat.new_account'))

@section('content')

<div class="login-logo">
  S<b>e</b>AT | {{ trans('web::seat.new_account') }}
</div>

<hr>

<div class="login-box-body">
  <p class="login-box-msg">
    {{ trans('web::seat.register_welcome') }}
    <b>{{ trans('web::seat.register_eve_warn') }}</b>
  </p>

  <form action="{{ route('auth.register.post') }}" class="form-horizontal" method="post">
    {{ csrf_field() }}

    <div class="box-body">
      <div class="form-group has-feedback">
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ trans('web::seat.username') }}">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ trans('web::seat.email') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="{{ trans('web::seat.password') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('web::seat.password_again') }}">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
      <div class="pull-left">
        <a href="{{ route('auth.login') }}" class="text-center">{{ trans('web::seat.existing_account') }}</a>
      </div>
      <button type="submit" class="btn btn-success pull-right">{{ trans('web::seat.register_account') }}</button>
    </div>
    <!-- /.box-footer -->

  </form>

</div>
<!-- /.login-box-body -->

@stop
