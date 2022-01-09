@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.corporation', 1) . ' - ' . $corporation->name . (isset($breadcrumb) ? ' > ' . $breadcrumb : ''))
@section('page_header', $corporation->name)

@section('full')

  <div class="row">

    <div class="col-md-3">

      @include('web::corporation.includes.summary')

    </div>

    <div class="col-md-9">

      @yield('corporation_content')

    </div>

  </div>

@stop
