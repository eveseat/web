@extends('web::layouts.corporation', ['viewname' => 'contracts', 'breadcrumb' => trans('web::seat.contracts')])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contracts'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="col-auto me-5">
                <h3 class="card-title">{{ trans('web::seat.contracts') }}</h3>
            </div>
            <div class="ms-auto">
                @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.contracts', 'label' => trans('web::seat.update_contracts')])
            </div>
        </div>

        @include('web::common.contracts.buttons.filters')

        {{ $dataTable->table(['class' => 'table card-table table-vcenter table-hover table-striped text-nowrap']) }}

    </div>

    @include('web::common.contracts.modals.details.details')

@stop

@push('javascript')
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
                $("[data-bs-toggle=tooltip]").tooltip();
            });
    });
  </script>

@endpush
