@extends('web::character.layouts.view', ['viewname' => 'intel'])

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::character.intel.includes.menu')

    </div>

    <div class="col-md-12">

      @yield('intel_content')

    </div>
  </div>

@stop
