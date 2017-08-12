@extends('web::character.layouts.view', ['viewname' => 'industry'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.industry'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.industry'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.industry') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-industry">
        <thead>
        <tr>
          <th>{{ trans('web::seat.start') }}</th>
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
    var table = $('table#character-industry').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('character.view.industry.data', ['character_id' => $request->character_id]) }}',
      dom             : '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-12"<"character-industry_filters">>><"row"<"col-sm-12"rt>><"row"<"col-sm-5"i><"col-sm-7"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      columns         : [
        {data: 'startDate', name: 'startDate', render: human_readable},
        {data: 'installerName', name: 'installerName'},
        {data: 'solarSystemName', name: 'solarSystemName'},
        {data: 'activityName', name: 'activityName'},
        {data: 'runs', name: 'runs'},
        {data: 'blueprintTypeName', name: 'blueprintTypeName'},
        {data: 'productTypeName', name: 'productTypeName'},
        {data: 'status', name: 'status', visible: false}
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      }
    });

    // initial filter
    table.column(7)
      .search('[[:<:]]1[[:>:]]', true, false) // strict lookup
      .draw();

    var filterGroup = $('<div class="btn-group btn-group-justified" role="group">');

    var filterRunning = $('<button class="btn btn-primary disabled">Running</button>');
    filterRunning.click(function(){
      $(this).addClass('disabled');
      filterPaused.removeClass('disabled');
      filterCompleted.removeClass('disabled');
      filterCancelled.removeClass('disabled');
      filterHistory.removeClass('disabled');

      table.column(7)
          .search('[[:<:]]1[[:>:]]', true, false) // strict lookup
          .draw();
    });

    var filterPaused = $('<button class="btn btn-warning">Paused</button>');
    filterPaused.click(function(){
      filterRunning.removeClass('disabled');
      $(this).addClass('disabled');
      filterCompleted.removeClass('disabled');
      filterCancelled.removeClass('disabled');
      filterHistory.removeClass('disabled');

      table.column(7)
          .search('[[:<:]]2[[:>:]]', true, false) // strict lookup
          .draw();
    });

    var filterCompleted = $('<button class="btn btn-success">Completed</button>');
    filterCompleted.click(function(){
      filterRunning.removeClass('disabled');
      filterPaused.removeClass('disabled');
      $(this).addClass('disabled');
      filterCancelled.removeClass('disabled');
      filterHistory.removeClass('disabled');

      table.column(7)
          .search('[[:<:]]3[[:>:]]', true, false) // strict lookup
          .draw();
    });

    var filterCancelled = $('<button class="btn btn-danger">Cancelled</button></div>');
    filterCancelled.click(function(){
      filterRunning.removeClass('disabled');
      filterPaused.removeClass('disabled');
      filterCompleted.removeClass('disabled');
      $(this).addClass('disabled');
      filterHistory.removeClass('disabled');

      table.column(7)
          .search('[[:<:]]102[[:>:]]', true, false) // strict lookup
          .draw();
    });

    var filterHistory = $('<button class="btn btn-default">History</button></div>');
    filterHistory.click(function(){
      filterRunning.removeClass('disabled');
      filterPaused.removeClass('disabled');
      filterCompleted.removeClass('disabled');
      filterCancelled.removeClass('disabled');
      $(this).addClass('disabled');

      table.column(7)
          .search('[[:<:]]101[[:>:]]|[[:<:]]103[[:>:]]', true, false) // strict lookup
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
