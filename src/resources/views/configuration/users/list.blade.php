@extends('web::layouts.grids.12')

@section('title', trans('web::seat.user_management'))
@section('page_header', trans('web::seat.user_management'))

@section('full')

  <div class="card">
    <div class="card-body">

      {!! $dataTable->table() !!}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

@endpush