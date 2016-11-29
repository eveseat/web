<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Characters Results</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="characters">
      <thead>
      <tr>
        <th>{{ trans_choice('web::seat.name', 1) }}</th>
        <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
        <th>{{ trans('web::seat.ship_type') }}</th>
        <th>{{ trans('web::seat.last_location') }}</th>
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

<script>

  $(function () {
    $('table#characters').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('support.search.characters.data') }}',
      columns         : [
        {data: 'characterName', name: 'characterName'},
        {data: 'corporationName', name: 'corporationName'},
        {data: 'shipTypeName', name: 'shipTypeName'},
        {data: 'lastKnownLocation', name: 'lastKnownLocation'},
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      },
      'search'        : {
        'search': '{{ $query }}'
      },
      order           : [[0, "asc"]]
    });
  });

</script>

@endpush
