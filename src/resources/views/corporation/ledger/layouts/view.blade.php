@extends('web::layouts.corporation', ['viewname' => 'ledger'])

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::corporation.ledger.includes.menu')

    </div>

    <div class="col-md-12">

      @yield('ledger_content')

    </div>
  </div>

@stop
