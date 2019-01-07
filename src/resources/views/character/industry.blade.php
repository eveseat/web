@extends('web::character.layouts.view', ['viewname' => 'industry', 'breadcrumb' => trans('web::seat.industry')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.industry'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.industry') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.industry']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_industry') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-industry">
        <thead>
        <tr>
          <th>{{ trans('web::seat.start') }}</th>
          <th>{{ trans('web::seat.end') }}</th>
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
      var table = $('table#character-industry').DataTable({
        processing      : true,
        serverSide      : true,
        ajax            : '{{ route('character.view.industry.data', ['character_id' => $request->character_id]) }}',
        dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-12"<"character-industry_filters">>><"row"<"col-sm-12"rt>><"row"<"col-sm-5"i><"col-sm-7"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
        columns         : [
          {data: 'start_date', name: 'a.start_date', render: human_readable},
          {data: 'end_date', name: 'a.end_date', render: human_readable},
          {data: 'facilityName', name: 'a.facility_id'},
          {data: 'activityName', name: 'ramActivities.activityName'},
          {data: 'runs', name: 'a.runs'},
          {data: 'blueprintTypeName', name: 'blueprintType.TypeName'},
          {data: 'productTypeName', name: 'productType.typeName'},
          {data: 'status', name: 'a.status', visible: false}
        ],
        'fnDrawCallback': function () {
          $(document).ready(function () {
            $('img').unveil(100);
          });
        }
      });

      // initial filter
      table.column(7)
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

        table.column(7)
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

        table.column(7)
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

        table.column(7)
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

        table.column(7)
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

        table.column(7)
            .search('[[:<:]]delivered[[:>:]]|[[:<:]]reverted[[:>:]]', true, false) // strict lookup
            .draw();
      });

      // build filter toolbar
      filterGroup.append($('<div class="btn-group" role="group">').append(filterRunning));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterPaused));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterCompleted));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterCancelled));
      filterGroup.append($('<div class="btn-group" role="group">').append(filterHistory));

      $('div.character-industry_filters').append(filterGroup);
    });

  </script>

@endpush
