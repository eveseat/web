@extends('web::layouts.app-mini')

@section('title', trans('web::seat.sign_in'))

@section('content')

  <div class="text-center mb-4">
    <img src="{{ asset('web/img/seat.svg') }}" alt="SeAT Logo" />
    <a href="{{ config('app.url') }}" class="navbar-brand navbar-brand-autodark">
      <span class="display-5 seat-font">S<b>e</b>AT | {{ trans('web::seat.sign_in') }}</span>
    </a>
  </div>

  <div class="card card-md">
    <div class="card-body">
      <h2 class="card-title text-center mb-4">{{ trans('web::seat.login_welcome') }}</h2>
      <div class="form-footer">
        <!-- SSO Button! -->
        <a href="{{ route('seatcore::auth.eve') }}" class="d-flex justify-content-center">
          <img src="{{ asset('web/img/evesso.png') }}" alt="LOG IN with EVE Online" />
        </a>
      </div>
      <!-- /.form-footer -->
    </div>
    <!-- ./card -->

  </div>
  <!-- /.text-center -->

@stop
