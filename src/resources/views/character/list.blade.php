@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 2))
@section('page_header', trans_choice('web::seat.character', 2))

@section('full')



  <div class="col-md-12">

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">{{ trans_choice('web::seat.character', 2) }}</h3>
      </div>
      <div class="panel-body">
        {{ $dataTable->table() }}
      </div>
    </div>

  </div>

@stop

@push('javascript')

  {{ $dataTable->scripts() }}

  @include('web::includes.javascript.id-to-name')

@endpush
