@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.corporation', 1) )
@section('page_header', trans_choice('web::seat.corporation', 1))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.corporation', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporations-table">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans('web::seat.ceo') }}</th>
          <th>{{ trans('web::seat.alliance') }}</th>
          <th>{{ trans('web::seat.tax_rate') }}</th>
          <th>{{ trans('web::seat.member_count') }}</th>
          <th></th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')
  <script>

    $(function () {
      $('table#corporations-table').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('corporation.list.data') }}',
        columns         : [
          {data: 'name', name: 'name'},
          {data: 'ceo_id', name: 'ceo_id'},
          {data: 'alliance_id', name: 'alliance_id'},
          {data: 'tax_rate', name: 'tax_rate'},
          {data: 'member_count', name: 'member_count'},
          {data: 'actions', name: 'actions'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("img").unveil(100);
            ids_to_names();
          });
        }
      });
    });

  </script>

  @include('web::includes.javascript.id-to-name')

@endpush
