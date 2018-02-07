@extends('web::character.wallet.layouts.view', ['sub_viewname' => 'transactions'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))

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
                 id="character-transactions">
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

<script>

  $(function () {
    $('table#character-transactions').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('character.view.transactions.data', ['character_id' => $request->character_id]) }}',
      columns         : [
        {data: 'date', name: 'date', render: human_readable},
        {data: 'is_buy', name: 'is_buy'},
        {data: 'quantity', name: 'quantity'},
        {data: 'price', name: 'price'},
        {data: 'total', name: 'price'},
        {data: 'client', name: 'client'}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      }
    });
  });

</script>

@endpush
