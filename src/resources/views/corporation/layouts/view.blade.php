@extends('web::layouts.grids.12')

@section('full')

  <div class="row">

    <div class="col-md-12">

      @include('web::corporation.includes.menu')

    </div>

  </div>

  <div class="row">

    <div class="col-md-3">

      @include('web::corporation.includes.summary')

    </div>

    <div class="col-md-9">

      @yield('corporation_content')

    </div>

  </div>


@stop
