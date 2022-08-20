@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.alliance', 1) )
@section('page_header', trans_choice('web::seat.alliance', 1))

@section('full')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.alliance', 2) }}</h3>
    </div>
    <div class="card-body">

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

@endpush
