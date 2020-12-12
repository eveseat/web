@extends('web::corporation.layouts.view', ['viewname' => 'tracking', 'breadcrumb' => trans('web::seat.tracking')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card card-gray card-outline card-outline-tabs">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.tracking') }}</h3>
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
