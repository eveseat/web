@extends('web::character.layouts.view', ['viewname' => 'contacts', 'breadcrumb' => trans('web::seat.contacts')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contacts'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contacts') }}</h3>
    </div>
    <div class="panel-body">
      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
