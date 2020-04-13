@extends('web::corporation.layouts.view', ['viewname' => 'assets', 'breadcrumb' => trans('web::seat.assets')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.assets'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.assets') }}</h3>
    </div>
    <div class="card-body">

      {!! $dataTable->table() !!}

    </div><!-- /.box-body -->
  </div>

  @include('web::common.assets.modals.fitting.fitting')
  @include('web::common.assets.modals.container.container')

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush
