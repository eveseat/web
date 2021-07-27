@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.alliance', 1) . ' - ' . $alliance->name . (isset($breadcrumb) ? ' > ' . $breadcrumb : ''))

@section('full')

  <div class="row">

    <div class="col-md-12">

      @include('web::alliance.includes.menu')

    </div>

  </div>

  <div class="row">

    <div class="col-md-3">

      @include('web::alliance.includes.summary')

    </div>

    <div class="col-md-9">

      @yield('alliance_content')

    </div>

  </div>

@stop
