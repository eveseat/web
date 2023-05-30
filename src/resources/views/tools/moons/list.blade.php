@extends('web::layouts.grids.12')

@section('title', trans('web::seat.moons_reporter'))
@section('page_header', trans('web::seat.tools'))
@section('page_description', trans('web::seat.moons_reporter'))

@section('content')
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">{{ trans_choice('web::moons.moon', 2) }}</h4>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @can('moon.create_moon_reports')
            @include('web::tools.moons.buttons.import')
          @endcan
        </div>
      </div>
    </div>
    <div class="card-body">
      @include('web::tools.moons.filters.moon-filter')<br>
      {!! $dataTable->table(['class' => 'table table-hover']) !!}
    </div>
    <div class="card-footer">
      <ul class="list-inline moon-stats">
        <li class="list-inline-item col-2">
          <span class="badge badge-success">0</span> R4
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-primary">0</span> R8
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-info">0</span> R16
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-warning">0</span> R32
        </li>
        <li class="list-inline-item col-2">
          <span class="badge badge-danger">0</span> R64
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
