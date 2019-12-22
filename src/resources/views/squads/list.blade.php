@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 0))

@section('full')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">List</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @if(auth()->user()->hasSuperUser())
            <a class="btn btn-sm btn-light" href="{{ route('squads.create') }}">
              <i class="fas fa-plus"></i>
            </a>
          @endif
        </div>
      </div>
    </div>
    <div class="card-body">
      {!! $dataTable->table() !!}
    </div>
  </div>
@endsection

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
