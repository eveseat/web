@extends('web::layouts.app-mini')

@section('title', trans('web::seat.email_verify'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.email_verify') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      {{ trans('web::seat.mfa_welcome') }}
    </p>

    <form action="{{ route('auth.eve.email.set') }}" class="form-horizontal" method="post">
      {{ csrf_field() }}

      <div class="box-body">
        <div class="form-group has-feedback">
          <input type="text" name="new_email" class="form-control" placeholder="{{ trans('web::seat.new_email') }}">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="text" name="new_email_confirmation" class="form-control" placeholder="{{ trans('web::seat.confirm_new_email') }}">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
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
