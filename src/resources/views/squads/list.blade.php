@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 0))

@section('full')
  <div class="card card-default">
    <div class="card-body">
      {!! $dataTable->table() !!}
    </div>
  </div>
@endsection

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
