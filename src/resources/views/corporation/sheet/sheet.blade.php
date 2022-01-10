@extends('web::layouts.corporation', ['viewname' => 'summary', 'breadcrumb' => trans('web::seat.summary')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.summary'))

@section('corporation_content')

  <div class="row">

    <div class="col-6">
      @include('web::corporation.sheet.summary')

      @include('web::corporation.sheet.description')
    </div>
    <div class="col-md-6">
      @include('web::corporation.sheet.divisions')
    </div>

  </div>


@stop
