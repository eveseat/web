@extends('web::layouts.app-mini')

@section('title', trans('web::seat.email_verify'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT | {{ trans('web::seat.email_verify') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      This account has not yet verified its email address. Please check your
      inbox and click the "Activate Your Account" link.
    </p>

    <p>
    <form role="form" action="{{ route('auth.logout') }}" method="post" class="form-inline">
      {{ csrf_field() }}
      <button type="submit" class="btn btn-default">
        {{ trans('web::seat.sign_out') }}
      </button>

      <a href="{{ route('home') }}" class="btn btn-primary pull-right">Home</a>
    </form>
    </p>
  </div>

@stop
