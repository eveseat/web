@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.corporation', 2) )
@section('page_header', trans_choice('web::seat.corporation', 2))
@section('page_description', trans('web::seat.list'))

@section('full')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.corporation', 2) }}</h3>
    </div>
    {{ $dataTable->table(['class' => 'table card-table table-vcenter text-nowrap']) }}
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

@endpush
