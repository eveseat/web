@extends('web::layouts.grids.12')

@section('title', trans_choice('web::seat.character', 2))
@section('page_header', trans_choice('web::seat.character', 2))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.character', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-list" data-page-length=100>
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
          <th>{{ trans('web::seat.alliance') }}</th>
          <th>{{ trans('web::seat.last_location') }}</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

<script>

  $(function () {
    $('table#character-list').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.list.data') }}',
      columns: [
        {data: 'characterName', name: 'characterName'},
        {data: 'corporationName', name: 'corporationName'},
        {data: 'alliance', name: 'alliance'},
        {data: 'lastKnownLocation', name: 'lastKnownLocation'},
      ],
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
        });
      },
      order: [[ 0, "asc" ]]
    });
  });

</script>

@endpush
