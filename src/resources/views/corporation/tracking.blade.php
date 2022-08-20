@extends('web::corporation.layouts.view', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card card-gray card-outline card-outline-tabs">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking') }}</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.members_tracking', 'label' => trans('web::seat.update_members_tracking')])
        </div>
      </div>
    </div>
    <div class="card-body">

      <div class="mb-3">
        <div class="btn-group d-flex">
          <button type="button"  data-filter-field="type" data-filter-value="valid" class="btn btn-success dt-filters active">
            <i class="fas fa-check-circle"></i>
            {{ trans_choice('web::seat.valid_token', 2) }}
          </button>
          <button type="button" data-filter-field="type" data-filter-value="invalid" class="btn btn-danger dt-filters active">
            <i class="fas fa-times-circle"></i>
            {{ trans_choice('web::seat.invalid_token', 2) }}
          </button>
        </div>
      </div>

      {{ $dataTable->table() }}

    </div>
  </div>

@stop

@push('javascript')
  <script src="{{ asset('web/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('web/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

  {!! $dataTable->scripts() !!}

  <script>
    $(document).ready(function () {
      $('.dt-filters')
        .on('click', function () {
          $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
            window.LaravelDataTables['dataTableBuilder'].ajax.reload();
          });
    });
  </script>

@endpush
