@extends('web::character.wallet.layouts.view', ['sub_viewname' => 'transactions', 'breadcrumb' => trans('web::seat.wallet_transactions')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">
            {{ trans('web::seat.wallet_transactions') }}
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.wallet']) }}" class="pull-right">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_wallet') }}"></i>
            </a>
          </h3>
        </div>
        <div class="panel-body">
          {{ $dataTable->table() }}
        </div>
      </div>

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

@endpush
