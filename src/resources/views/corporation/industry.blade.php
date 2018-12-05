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
          <th>{{ trans('web::seat.start') }}</th>
          <th>{{ trans('web::seat.end') }}</th>
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

  <script type="application/javascript">

    $(function () {
      var table = $('table#corporation-industry').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('corporation.view.industry.data', ['corporation_id' => $request->corporation_id]) }}',
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-12"<"corporation-industry_filters">>><"row"<"col-sm-12"rt>><"row"<"col-sm-5"i><"col-sm-7"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        columns         : [
          {data: 'start_date', name: 'a.start_date', render: human_readable},
          {data: 'end_date', name: 'a.end_date', render: human_readable},
          {data: 'installer_id', name: 'a.installer_id'},
          {data: 'facilityName', name: 'a.facility_id'},
          {data: 'activityName', name: 'ramActivities.activityName'},
          {data: 'runs', name: 'a.runs'},
          {data: 'blueprintTypeName', name: 'blueprintType.typeName'},
          {data: 'productTypeName', name: 'productType.typeName'},
          {data: 'status', name: 'a.status', visible: false}
        ],
        'fnDrawCallback': function () {
          $(document).ready(function () {
            ids_to_names();
            $('img').unveil(100);
          });
        }
      });

      // initial filter
      table.column(8)
          .search('[[:<:]]active[[:>:]]', true, false) // strict lookup
          .draw();

      var filterGroup = $('<div class="btn-group btn-group-justified" role="group">');

      var filterRunning = $('<button class="btn btn-primary disabled">Running</button>');
      filterRunning.on('click', function () {
        $(this).addClass('disabled');
        filterPaused.removeClass('disabled');
        filterCompleted.removeClass('disabled');
        filterCancelled.removeClass('disabled');
        filterHistory.removeClass('disabled');

        table.column(8)
            .search('[[:<:]]active[[:>:]]', true, false) // strict lookup
            .draw();
      });

      var filterPaused = $('<button class="btn btn-warning">Paused</button>');
      filterPaused.on('click', function () {
        filterRunning.removeClass('disabled');
        $(this).addClass('disabled');
        filterCompleted.removeClass('disabled');
        filterCancelled.removeClass('disabled');
        filterHistory.removeClass('disabled');

        table.column(8)
            .search('[[:<:]]paused[[:>:]]', true, false) // strict lookup
            .draw();
      });

      var filterCompleted = $('<button class="btn btn-success">Completed</button>');
      filterCompleted.on('click', function () {
        filterRunning.removeClass('disabled');
        filterPaused.removeClass('disabled');
        $(this).addClass('disabled');
        filterCancelled.removeClass('disabled');
        filterHistory.removeClass('disabled');

        table.column(8)
            .search('[[:<:]]ready[[:>:]]', true, false) // strict lookup
            .draw();
      });

      var filterCancelled = $('<button class="btn btn-danger">Cancelled</button></div>');
      filterCancelled.on('click', function () {
        filterRunning.removeClass('disabled');
        filterPaused.removeClass('disabled');
        filterCompleted.removeClass('disabled');
        $(this).addClass('disabled');
        filterHistory.removeClass('disabled');

        table.column(8)
            .search('[[:<:]]cancelled[[:>:]]', true, false) // strict lookup
            .draw();
      });

      var filterHistory = $('<button class="btn btn-default">History</button></div>');
      filterHistory.on('click', function () {
        filterRunning.removeClass('disabled');
        filterPaused.removeClass('disabled');
        filterCompleted.removeClass('disabled');
        filterCancelled.removeClass('disabled');
        $(this).addClass('disabled');

        table.column(8)
            .search('[[:<:]]delivered[[:>:]]|[[:<:]]reverted[[:>:]]', true, false) // strict lookup
            .draw();
      });

      // build filter toolbar
      filterGroup.append($('<div class="btn-group" role="group">').append(filterRunning));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterPaused));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterCompleted));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterCancelled));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterHistory));

      $('div.corporation-industry_filters').append(filterGroup);
    });

  </script>


@endpush
