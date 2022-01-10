@extends('web::layouts.alliance', ['viewname' => 'summary', 'breadcrumb' => trans('web::seat.summary')])

@section('page_description', trans_choice('web::seat.alliance', 1) . ' ' . trans('web::seat.summary'))

@section('alliance_content')

  <div class="row">

    <div class="col-md-6">
      @include('web::alliance.sheet.summary')
    </div>

  </div>

@stop
