@extends('web::corporation.layouts.view', ['viewname' => 'starbases'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.starbase', 2))

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">
      @include('web::corporation.starbase.summary')
    </div>
  </div> <!-- ./row -->

  @foreach($starbases as $starbase)

    <div class="row">
      <div class="col-md-12">
        @include('web::corporation.starbase.detail')
      </div>
    </div>

  @endforeach

@stop

@section('javascript')

  @include('web::includes.javascript.id-to-name')

@stop
