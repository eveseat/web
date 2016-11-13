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
    $('table#character-industry').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.view.industry.data', ['character_id' => $request->character_id]) }}',
      columns: [
        {data: 'startDate', name: 'startDate', render: human_readable},
        {data: 'installerName', name: 'installerName'},
        {data: 'solarSystemName', name: 'solarSystemName'},
        {data: 'activityName', name: 'activityName'},
        {data: 'runs', name: 'runs'},
        {data: 'blueprintTypeName', name: 'blueprintTypeName'},
        {data: 'productTypeName', name: 'productTypeName'},
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      }
    });
  });

</script>

@endpush
