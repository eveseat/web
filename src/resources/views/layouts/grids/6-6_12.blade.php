@extends('web::layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6">

      @yield('left')

    </div>
    <div class="col-md-6">

      @yield('right')

    </div>
  </div>

  <div class="row">
    <div class="col-md-12 gy-3">

      @yield('bottom')

    </div>
  </div>
</div>
@stop
