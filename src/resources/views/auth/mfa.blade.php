@extends('web::layouts.app-mini')

@section('title', 'Multifactor Authentication')

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | Multifactor Auth
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      Please enter an Authentication Code to proceed.
    </p>

    <form action="{{ route('auth.login.mfa.check') }}" class="form-horizontal" method="post">
      {{ csrf_field() }}

      <div class="box-body">
        <div class="form-group has-feedback">
          <input type="text" name="confirm_code" class="form-control" placeholder="Verification Code">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right">Verify</button>
      </div>
      <!-- /.box-footer -->

    </form>

  </div>
  <!-- /.login-box-body -->

@stop
