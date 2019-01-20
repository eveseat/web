@extends('web::corporation.wallet.layouts.view', ['sub_viewname' => 'journal', 'breadcrumb' => trans('web::seat.wallet_journal')])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          @foreach($divisions as $division)
            @if($loop->first) <li class="active"> @else <li> @endif
              <a href="" data-toggle="tab" data-division="{{$division->division}}">
                {{ $division->name }}
              </a>
            </li>
          @endforeach
        </ul>
        <div class="panel-body">

          <table class="table compact table-condensed table-hover table-responsive"
                 id="corporation-journal" data-page-length=100>
            <thead>
            <tr>
              <th>{{ trans('web::seat.date') }}</th>
              <th>{{ trans_choice('web::seat.wallet_journal', 1) }}</th>
              <th>{{ trans('web::seat.owner_1') }}</th>
              <th>{{ trans('web::seat.owner_2') }}</th>
              <th>{{ trans('web::seat.amount') }}</th>
              <th>{{ trans('web::seat.balance') }}</th>
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

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      corporation_journal_table.draw();
    });

    function getSelectedDivision() {
      return $("div.nav-tabs-custom > ul > li.active > a").data('division');
    }


    var corporation_journal_table = $('table#corporation-journal').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : {
        url : '{{ route('corporation.view.journal.data', ['corporation_id' => $request->corporation_id]) }}',
        data: function (d) {
          d.division = getSelectedDivision();
        }
      },
      columns         : [
        {data: 'date', name: 'date', render: human_readable},
        {data: 'ref_type', name: 'ref_type'},
        {data: 'first_party_id', name: 'first_party.name'},
        {data: 'second_party_id', name: 'second_party.name'},
        {data: 'amount', name: 'amount'},
        {data: 'balance', name: 'balance'}
      ],
      drawCallback: function () {
        $("[data-toggle=tooltip]").tooltip();
        $("img").unveil(100);
        ids_to_names();
      }
    });


  </script>

@endpush
