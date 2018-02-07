@extends('web::character.layouts.view', ['viewname' => 'wallet'])

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::character.wallet.includes.menu')

    </div>

    <div class="col-md-12">

      @yield('wallet_content')

    </div>
  </div>

@stop
