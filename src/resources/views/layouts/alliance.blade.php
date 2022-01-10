@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.alliance', 1) . ' - ' . $alliance->name . (isset($breadcrumb) ? ' > ' . $breadcrumb : ''))
@section('page_header', $alliance->name)

@section('full')

  <div class="row">

    <div class="col-md-3 col-xxl-2">

      @include('web::alliance.includes.sidecard')

    </div>

    <div class="col-md-9 col-xxl-10">

      @yield('alliance_content')

    </div>

  </div>

@stop
