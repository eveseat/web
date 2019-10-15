@extends('web::corporation.wallet.layouts.view', ['sub_viewname' => 'journal', 'breadcrumb' => trans('web::seat.wallet_journal')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.wallet_journal') }}</h3>
        </div>
        <div class="card-body">
          {{ $dataTable->table() }}
        </div>
      </div>

    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
