@extends('web::layouts.app')

@section('content')

  <div class="row">
    <div class="col-md-4">

      @yield('left')

    </div>
    <div class="col-md-4">

      @yield('center')

    </div>
    <div class="col-md-4">

      @yield('right')

    </div>
  </div>

@stop
