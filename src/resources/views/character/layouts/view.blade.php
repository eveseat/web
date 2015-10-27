@extends('web::layouts.grids.3-9')

@section('left')

  @include('web::character.includes.summary')

@stop

@section('right')

    @include('web::character.includes.menu')

    @yield('character_content')

@stop
