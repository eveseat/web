@extends('web::character.wallet.layouts.view', ['sub_viewname' => 'transactions', 'breadcrumb' => trans('web::seat.wallet_transactions')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.wallet_transactions'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#" data-toggle="tab" data-characters="single">{{ trans('web::seat.wallet_transactions') }}</a></li>
          <li><a href="#" data-toggle="tab" data-characters="all">{{ trans('web::seat.linked_characters') }} {{ trans('web::seat.wallet_transactions') }}</a></li>
          @if(auth()->user()->has('character.jobs'))
            <li class="pull-right">
              <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.wallet']) }}"
                 style="color: #000000">
                <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_wallet') }}"></i>
              </a>
            </li>
          @endif

        </ul>
        <div class="tab-content">

          <table class="table compact table-condensed table-hover table-responsive"
                 id="character-transactions">
            <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th></th>
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

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
      character_transactions.draw();
    });

    function allLinkedCharacters() {
      var character_ids = $("div.nav-tabs-custom > ul > li.active > a").data('characters');
      return character_ids !== 'single';
    }


    var character_transactions = $('table#character-transactions').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : {
        url : '{{ route('character.view.transactions.data', ['character_id' => $request->character_id]) }}',
        data: function (d) {
          d.all_linked_characters = allLinkedCharacters();
        }
      },
      columns     : [
        {data: 'date', name: 'date', render: human_readable},
        {data: 'is_buy', searchable: false},
        {data: 'item_view', name: 'type.typeName'},
        {data: 'quantity', name: 'quantity'},
        {data: 'unit_price', name: 'unit_price'},
        {data: 'total', name: 'unit_price'},
        {data: 'client_view', name: 'client.name'}
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
        $('[data-toggle="tooltip"]').tooltip();
      }
    });


  </script>

@include('web::includes.javascript.id-to-name')

@endpush
