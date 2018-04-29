@extends('web::corporation.wallet.layouts.view', ['sub_viewname' => 'journal'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('wallet_content')

  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('web::seat.wallet_journal') }}</h3>
        </div>
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

  $(function () {
    $('table#corporation-journal').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('corporation.view.journal.data', ['corporation_id' => $request->corporation_id]) }}',
      columns         : [
        {data: 'date', name: 'date', render: human_readable},
        {data: 'ref_type', name: 'ref_type'},
        {data: 'first_party_id', name: 'first_party_id'},
        {data: 'second_party_id', name: 'second_party_id'},
        {data: 'amount', name: 'amount'},
        {data: 'balance', name: 'balance'}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("[data-toggle=tooltip]").tooltip();
          $("img").unveil(100);
          ids_to_names();
        });
      }
    });
  });

</script>

@endpush
