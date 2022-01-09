@extends('web::layouts.corporation', ['viewname' => 'security'])

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::corporation.security.includes.menu')

    </div>

    <div class="col-md-12">

      @yield('security_content')

    </div>
  </div>

@stop
