@extends('web::layouts.app-mini')

@section('title', trans('web::seat.mfa'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.mfa') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      {{ trans('web::seat.mfa_welcome') }}
    </p>

    <form action="{{ route('auth.login.mfa.check') }}" class="form-horizontal" method="post">
      {{ csrf_field() }}

      <div class="box-body">
        <div class="form-group has-feedback">
          <input type="text" name="confirm_code" class="form-control" placeholder="{{ trans('web::seat.v_code') }}">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right">
          {{ trans('web::seat.verify') }}
        </button>
      </div>
      <!-- /.box-footer -->

    </form>

  </div>
  <!-- /.login-box-body -->

@stop
