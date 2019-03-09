@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 1) . (isset($character_name) ? ' - ' . $character_name : '') . (isset($breadcrumb) ? ' > ' . $breadcrumb : ''))

@section('full')

  <div class="row">

    <div class="col-md-12">

      @include('web::character.includes.menu')

    </div>

  </div>

  <div class="row">

    <div class="col-md-3">

      @include('web::character.includes.summary')

    </div>

    <div class="col-md-9">

      @yield('character_content')

    </div>

  </div>


@stop
