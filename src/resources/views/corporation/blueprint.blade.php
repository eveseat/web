@extends('web::corporation.layouts.view', ['viewname' => 'blueprint', 'breadcrumb' => trans('web::seat.blueprint')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.blueprint'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.blueprint') }}
      </h3>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <div class="btn-group d-flex">
          <button type="button" id="dt-filters-bpo" class="btn btn-primary active">Original</button>
          <button type="button" id="dt-filters-bpc" class="btn btn-info active">Copy</button>
        </div>
      </div>

      {{ $dataTable->table() }}
    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

  <script>
      $(document).ready(function() {
          $('#dt-filters-bpc, #dt-filters-bpo').on('click', function () {
              $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
              window.LaravelDataTables['dataTableBuilder'].ajax.reload();
          });
      });
  </script>
@endpush