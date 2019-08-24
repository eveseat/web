@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.corporation', 1) )
@section('page_header', trans_choice('web::seat.corporation', 1))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.corporation', 2) }}</h3>
    </div>
    <div class="panel-body">

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

@endpush
