@extends('web::corporation.layouts.view', ['viewname' => 'contracts', 'breadcrumb' => trans('web::seat.contracts')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('web::seat.contracts') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.contracts', 'label' => trans('web::seat.update_contracts')])
        </div>
      </div>
    </div>
    <div class="card-body">

      @include('web::common.contracts.buttons.filters')

      {{ $dataTable->table() }}

    </div>

  </div>

  @include('web::common.contracts.modals.details.details')

@stop

@push('javascript')
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

  {!! $dataTable->scripts() !!}

  @include('web::includes.javascript.id-to-name')

  <script>
    $('#contract-detail').on('show.bs.modal', function (e) {
        var body = $(e.target).find('.modal-body');
        body.html('Loading...');

        $.ajax($(e.relatedTarget).data('url'))
            .done(function (data) {
                body.html(data);
                ids_to_names();
                $("[data-toggle=tooltip]").tooltip();
            });
    });
  </script>

@endpush
