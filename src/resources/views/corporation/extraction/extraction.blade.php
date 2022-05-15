@extends('web::corporation.layouts.view', ['viewname' => 'extractions', 'breadcrumb' => trans_choice('web::seat.extraction', 0)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.extraction', 0))

@section('corporation_content')

<p class="container-fluid">
<div class="btn-toolbar">
  @can('moon.manage_moon_reports')
  <div class="btn-group pr-3">
      <button type="button" data-toggle="modal" data-target="#moon-import" class="btn btn-success float-right" aria-label="Settings" title="Add a moon scan, used to populate ore information">
        <i class="fas fa-plus-square pr-1"></i>Add Scan
      </button>
  </div>
  @endcan


  <div class="btn-group">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target=".rate-collapse" title="Show/Hide the Moon Ore and Volume">
      <i class="fas fa-eye pr-1"></i>Toggle Ore
    </button>
  </div>
</div>
</p>

@foreach ($extractions->sortBy('chunk_arrival_time')->chunk(3) as $row)
  <div class="row">
  @foreach($row as $column)
    @include('web::corporation.extraction.partials.card')
  @endforeach
  </div>
@endforeach
@include('web::tools.moons.modals.import.import')
@stop
