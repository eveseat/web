@extends('web::layouts.app-mini')

@section('title', trans('web::seat.unauthorized_request'))

@section('content')

  <div class="login-logo">
    <a href="#">S<b>e</b>AT</a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">
      <h5 class="text-center">
        <i class="fas fa-exclamation-triangle"></i>
        {{ trans('web::seat.unauthorized_request') }}
      </h5>

      <hr/>

      <p class="card-text login-box-msg">
        {{ trans('web::seat.unauthorized_request_logged') }}
      </p>

      <a href="{{ route('seatcore::home') }}" class="btn btn-block btn-primary">
        {{ trans('web::seat.home') }}
      </a>

    </div>
  </div>

@stop
