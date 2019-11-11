@extends('web::corporation.layouts.view', ['viewname' => 'extractions', 'breadcrumb' => trans_choice('web::seat.extraction', 0)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.extraction', 0))

@section('corporation_content')
@foreach ($extractions->chunk(3) as $row)
  <div class="row">
  @foreach($row as $extraction)
    @include('web::corporation.extraction.partials.card')
  @endforeach
  </div>
@endforeach
@include('web::tools.moons.modals.import.import')
@stop
