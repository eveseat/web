@extends('web::layouts.grids.12')

@section('title', trans('web::seat.about'))
@section('page_header', trans('web::seat.configuration'))
@section('page_description', trans('web::seat.about'))

@section('full')
  <div class="row row-cards">
    <div class="col-md-12">

      @include('web::about.partials.licences')

    </div>

    <div class="col-md-12">

      @include('web::about.partials.disclaimer')

    </div>

    <div class="col-md-6">

      @include('web::about.partials.contacts')

    </div>

    <div class="col-md-6">

      <div class="row row-cards">

        <div class="col-12">
          @include('web::about.partials.donate')
        </div>

        <div class="col-12">
          @include('web::about.partials.security')
        </div>

      </div>

    </div>

  </div>
@stop
