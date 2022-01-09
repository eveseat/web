@extends('web::layouts.corporation', ['viewname' => 'wallet'])

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::corporation.wallet.includes.menu')

    </div>

    <div class="col-md-12">

      @yield('wallet_content')

    </div>
  </div>

@stop
