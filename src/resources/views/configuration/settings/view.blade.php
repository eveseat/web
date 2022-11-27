@extends('web::layouts.grids.6-6_12')

@section('title', trans('web::seat.settings'))
@section('page_header', trans('web::seat.configuration'))
@section('page_description', trans('web::seat.settings'))

@section('left')
    <div class="row row-deck row-cards">
        <div class="col-12">
            @include('web::configuration.settings.partials.config')
        </div>
    </div>
@stop

@section('right')
    <div class="row row-deck row-cards">
        <div class="col-12">
            @include('web::configuration.settings.partials.packages')
        </div>
    </div>
@stop

@section('bottom')
    <div class="row row-deck row-cards">
        <div class="col-12">
            @include('web::configuration.settings.partials.links')
        </div>
    </div>
@stop
