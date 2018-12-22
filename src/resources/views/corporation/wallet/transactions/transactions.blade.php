@extends('web::corporation.wallet.layouts.view', ['sub_viewname' => 'transactions', 'breadcrumb' => trans('web::seat.wallet_transactions')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.wallet_transactions') }}</h3>
        </div>
        <div class="panel-body">

          <table class="table compact table-condensed table-hover table-responsive"
                 id="corporation-transactions" data-page-length=100>
            <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans_choice('web::seat.type', 1) }}</th>
              <th>{{ trans('web::seat.qty') }}</th>
              <th>{{ trans('web::seat.price') }}</th>
              <th>{{ trans('web::seat.total') }}</th>
              <th>{{ trans('web::seat.client') }}</th>
            </tr>
            </thead>
          </table>

        </div>
      </div>

    </div>
  </div>

@stop

@push('javascript')

<script type="text/javascript">

  $(function () {
    $('table#corporation-transactions').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('corporation.view.transactions.data', ['corporation_id' => $request->corporation_id]) }}',
      columns         : [
        {data: 'date',       name: 'date', render: human_readable},
        {data: 'is_buy',     name: 'is_buy'},
        {data: 'quantity',   name: 'quantity'},
        {data: 'unit_price', name: 'unit_price'},
        {data: 'total',      name: 'unit_price'},
        {data: 'client_id',  name: 'client_id'}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
          ids_to_names();
        });
      }
    });
  });

</script>

@endpush
