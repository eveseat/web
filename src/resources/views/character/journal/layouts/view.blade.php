@extends('web::character.layouts.view', ['viewname' => 'journal'])

@section('character_content')

  <div class="row">
    <div class="col-md-12">

      @include('web::character.journal.includes.menu')

    </div>

    <div class="col-md-12">

      @yield('journal_content')

    </div>
  </div>

@stop
