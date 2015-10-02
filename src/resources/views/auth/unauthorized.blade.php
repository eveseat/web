@extends('web::layouts.app-mini')

@section('title', trans('web::access.unauthorized_request'))

@section('content')

  <div class="login-logo">
    S<b>e</b>AT <br>
    <hr>
    <i class="fa fa-exclamation-triangle"></i>
    {{ trans('web::access.unauthorized_request') }}
  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">

      {{ trans('web::access.unauthorized_request_logged') }}
    </p>

    <a href="#" class="btn btn-primary btn-block" onclick="window.history.back()">
      {{ ucfirst(trans('web::general.back')) }}
    </a>

  </div>

@stop
