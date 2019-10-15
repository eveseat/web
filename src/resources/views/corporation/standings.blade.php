@extends('web::corporation.layouts.view', ['viewname' => 'standings', 'breadcrumb' => trans_choice('web::seat.standings', 0)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.standings', 0))

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.standings', 0) }}</h3>
    </div>
    <div class="card-body">

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush