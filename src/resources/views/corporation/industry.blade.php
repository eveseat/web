@extends('web::corporation.layouts.view', ['viewname' => 'industry'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.industry'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.industry'))

@inject('request', 'Illuminate\Http\Request')

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.industry') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="corporation-industry">
        <thead>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.installer') }}</th>
          <th>{{ trans('web::seat.system') }}</th>
          <th>{{ trans('web::seat.activity') }}</th>
          <th>{{ trans_choice('web::seat.run', 2) }}</th>
          <th>{{ trans('web::seat.blueprint') }}</th>
          <th>{{ trans('web::seat.product') }}</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

<script>

  $(function () {
    $('table#corporation-industry').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('corporation.view.industry.data', ['corporation_id' => $request->corporation_id]) }}',
      columns         : [
        {data: 'startDate', name: 'startDate', render: human_readable},
        {data: 'installerName', name: 'installerName'},
        {data: 'solarSystemName', name: 'solarSystemName'},
        {data: 'activityName', name: 'activityName'},
        {data: 'runs', name: 'runs'},
        {data: 'blueprintTypeName', name: 'blueprintTypeName'},
        {data: 'productTypeName', name: 'productTypeName'},
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
