@extends('web::layouts.corporation', ['viewname' => 'transactions', 'breadcrumb' => trans('web::seat.wallet_transactions')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ trans('web::seat.wallet_transactions') }}</h3>
          <div class="card-tools">
            <div class="input-group input-group-sm">
              @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.transactions', 'label' => trans('web::seat.update_transactions')])
            </div>
          </div>
        </div>
        <div class="card-body">
          {{ $dataTable->table() }}
        </div>
      </div>

    </div>
  </div>

@stop

@push('javascript')
  <script src="{{ asset('web/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('web/js/buttons.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

  {!! $dataTable->scripts() !!}
@endpush
