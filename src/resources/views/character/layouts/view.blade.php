@extends('web::layouts.grids.3-9')

@section('title', ucfirst(trans_choice('web::character.character', 2)))
@section('page_header', ucfirst(trans_choice('web::character.character', 2)))

@section('left')

  @include('web::character.includes.summary')

@stop

@section('right')

  @yield('character_content')

@stop
