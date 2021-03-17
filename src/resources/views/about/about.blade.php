@extends('web::layouts.grids.12')

@section('title', trans('web::seat.about'))
@section('page_header', trans('web::seat.about'))

@section('full')
  <div class="row">
    <div class="col-md-12">

      @include('web::about.partials.licences')

    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      @include('web::about.partials.disclaimer')

    </div>
  </div>

  <div class="row">

    <div class="col-md-6">

      @include('web::about.partials.contacts')

    </div>

    <div class="col-md-6">

      <div class="row">

        <div class="col-12">
          @include('web::about.partials.donate')
        </div>

      </div>

      <div class="row">

        <div class="col-12">
          @include('web::about.partials.security')
        </div>

      </div>

    </div>

  </div>
@stop
