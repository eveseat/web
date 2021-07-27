@extends('web::alliance.layouts.view', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.alliance', 1) . ' ' . trans('web::seat.tracking'))

@section('alliance_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.tracking') }}</h3>
      @if ($alliance->members->count() > $alliance->corporations->count())
      <div class="card-tools">
        <div class="input-group input-group-sm">
        <span class="text-warning">
                  <i class="fas fa-exclamation-triangle" data-toggle="tooltip" title="" 
                  data-original-title="SeAT is missing {{ $alliance->members->count() - $alliance->corporations->count() }} member corporations. These will not appear in the table below."></i>
                </span>
        </div>
      </div>
      @endif
    </div>
    <div class="card-body">

      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
