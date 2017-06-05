@extends('web::corporation.layouts.view', ['viewname' => 'journal'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.wallet_journal'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

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

<script>

  $(function () {
    $('table#corporation-journal').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('corporation.view.journal.data', ['corporation_id' => $request->corporation_id]) }}',
      columns         : [
        {data: 'date', name: 'date', render: human_readable},
        {data: 'refTypeName', name: 'refTypeName'},
        {data: 'ownerName1', name: 'ownerName1'},
        {data: 'ownerName2', name: 'ownerName2'},
        {data: 'amount', name: 'amount'},
        {data: 'balance', name: 'balance'},
      ],
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
        });
      },
      order: [[0, 'desc']]
    });
  });

</script>

@endpush
