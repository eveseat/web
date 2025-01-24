@extends('web::layouts.app-mini')

@section('title', trans('web::seat.sign_in'))

@section('content')

  <div class="text-center mb-4">
    <img class="login-logo" src="{{ asset('web/img/seat.svg') }}" alt="SeAT Logo" />
    <br>
    <a href="{{ config('app.url') }}" class="navbar-brand navbar-brand-autodark">
      <span class="display-4 seat-font">S<b>e</b>AT</span>
    </a>

  </div>

  <hr>

  <div class="login-box-body">
    <p class="login-box-msg">
      {!! $signin_message !!}
    </p>

    <!-- SSO Button! -->
    <!-- <div class="box-body text-center">
      <a href="{{ route('seatcore::auth.eve') }}">
        <img src="{{ asset('web/img/evesso.png') }}">
      </a>
    </div> -->
    <!-- /.box-footer -->

  </div>
  <!-- /.login-box-body -->

@stop
