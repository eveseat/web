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

    <!-- SSO Button! -->
    <div class="box-body text-center">
      <a href="{{ route('auth.eve') }}">
        <img src="{{ asset('web/img/evesso.png') }}">
      </a>
    </div>
    <!-- /.box-footer -->

  </div>
  <!-- /.login-box-body -->

@stop
