@extends('web::corporation.layouts.view', ['viewname' => 'market'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.market'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.market') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporation-market">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans('web::seat.price') }}</th>
          <th>{{ trans('web::seat.total') }}</th>
          <th>{{ trans_choice('web::seat.item', 1) }}</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

  <script>

    $(function () {
      $('table#corporation-market').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('corporation.view.market.data', ['corporation_id' => $request->corporation_id]) }}',
        columns         : [
          {data: 'issued', name: 'issued', render: human_readable},
          {data: 'bs', name: 'bid'},
          {data: 'vol', name: 'volEntered'},
          {data: 'price', name: 'price'},
          {data: 'total', name: 'price'},
          {data: 'typeName', name: 'typeName'}
        ],
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        "fnDrawCallback": function () {
          $(document).ready(function () {
            $("img").unveil(100);
          });
        }
      });
    });

  </script>

@endpush
