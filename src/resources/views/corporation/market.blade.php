@extends('web::corporation.layouts.view', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">{{ trans('web::seat.market') }}</h3>
    </div>
    <div class="box-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporation-market">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans('web::seat.price') }}</th>
          <th>{{ trans('web::seat.total') }}</th>
          <th>{{ trans('web::seat.issuer') }}</th>
          <th>{{ trans_choice('web::seat.item', 1) }}</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  <script>

    $('table#corporation-market').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : '{{ route('corporation.view.market.data', ['corporation_id' => $request->corporation_id]) }}',
      columns     : [
        {data: 'issued', name: 'issued', render: human_readable},
        {data: 'bs', name: 'is_buy_order'},
        {data: 'vol', name: 'volume_total'},
        {data: 'price', name: 'price'},
        {data: 'price_total', name: 'price_total'},
        {data: 'issued_by', name: 'issued_by'},
        {data: 'typeName', name: 'typeName'}
      ],
      drawCallback: function () {
        $("img").unveil(100);
        $("[data-toggle=tooltip]").tooltip();
        ids_to_names();
      }
    });

  </script>

@endpush
