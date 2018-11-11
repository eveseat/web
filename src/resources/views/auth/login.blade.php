@extends('web::layouts.app-mini')

@section('title', trans('web::seat.sign_in'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.sign_in') }}
  </div>

  <hr>

  @if($allow_registration)
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
  @else
    <div class="callout callout-info">
      <h4>{{ trans('web::seat.disabled') }}</h4>

      <p>{{ trans('web::seat.no_register') }}</p>
    </div>
  @endif

@stop
