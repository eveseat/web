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

      @include('web::common.blueprints.buttons.filters')

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