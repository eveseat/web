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
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

  <script>

    $('table#characters').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : '{{ route('support.search.characters.data') }}',
      columns     : [
        {data: 'name', name: 'name'},
        {data: 'corporation_id', name: 'corporation_id', searchable: false}
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
      },
      'search'    : {
        'search': '{{ $query }}'
      },
      order       : [[0, "asc"]]
    });

  </script>

@endpush
