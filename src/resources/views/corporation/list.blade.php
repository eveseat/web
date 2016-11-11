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
          <th>{{ trans('web::seat.member_limit') }}</th>
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
      processing: true,
      serverSide: true,
      ajax: '{{ route('corporation.list.data') }}',
      columns: [
        {data: 'corporationName', name: 'corporationName'},
        {data: 'ceoName', name: 'ceoName'},
        {data: 'allianceName', name: 'allianceName'},
        {data: 'taxRate', name: 'taxRate'},
        {data: 'memberCount', name: 'memberCount'},
      ],
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
        });
      }
    });
  });

</script>
@endpush
