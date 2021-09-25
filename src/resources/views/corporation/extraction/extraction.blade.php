@extends('web::corporation.layouts.view', ['viewname' => 'extractions', 'breadcrumb' => trans_choice('web::seat.extraction', 0)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.extraction', 0))

@section('corporation_content')

<p class="container-fluid">
<div class="btn-toolbar">
  <div class="btn-group">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target=".rate-collapse" >Toggle Ore Rate</button>
  </div>

  @can('moon.manage_moon_reports')
  <div class="btn-group pl-3">

      <button type="button" data-toggle="modal" data-target="#moon-import" class="btn btn-primary float-right" aria-label="Settings">
        <i class="fas fa-cogs"></i> Add Missing Moon Scan
      </button>

  </div>
  @endcan
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
