@extends('web::layouts.corporation', ['viewname' => 'journal', 'breadcrumb' => trans('web::seat.wallet_journal')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header d-flex align-items-center">
          <div class="col-auto me-5">
            <h3 class="card-title">{{ trans('web::seat.wallet_journal') }}</h3>
          </div>
          <div class="ms-auto">
            @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.journals', 'label' => trans('web::seat.update_journals')])
          </div>
        </div>

        {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}
      </div>

    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}
@endpush
