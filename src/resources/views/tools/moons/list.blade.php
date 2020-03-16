@extends('web::layouts.grids.12')

@section('title', trans('web::seat.moons_reporter'))
@section('page_header', trans('web::seat.tools'))
@section('page_description', trans('web::seat.moons_reporter'))

@section('content')
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Moons</h4>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @if(auth()->user()->has('moon.create_moon_reports', false))
          @include('web::tools.moons.buttons.import')
          @endif
        </div>
      </div>
    </div>
    <div class="card-body">
      {!! $dataTable->table(['class' => 'table table-hover']) !!}
    </div>
    <div class="card-footer">
      <ul class="list-inline">
        <li class="list-inline-item col-2">
          <span class="badge badge-success">{{ $stats->ubiquitous }}</span> Gaz
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-primary">{{ $stats->common }}</span> R8
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-info">{{ $stats->uncommon }}</span> R16
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-warning">{{ $stats->rare }}</span> R32
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-danger">{{ $stats->exceptional }}</span> R64
        </li>
        <li class="list-inline-item">
          <span class="badge badge-default">{{ $stats->standard }}</span> ORE
        </li>
      </ul>
    </div>
  </div>

  @include('web::tools.moons.modals.components.components')
  @include('web::tools.moons.modals.import.import')
@endsection

@push('javascript')
  {!! $dataTable->scripts() !!}
  @include('web::includes.javascript.id-to-name')
@endpush
