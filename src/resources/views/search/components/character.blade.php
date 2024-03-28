<div class="card">
  <div class="card-header">
    <h3 class="card-title">Characters Results</h3>
  </div>
  <div class="card-body">
    <table class="table table-condensed table-striped table-hover" id="characters">
      <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
          <th>{{ trans_choice('web::seat.alliance', 1) }}</th>
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
      ajax        : '{{ route('seatcore::support.search.characters.data') }}',
      columns     : [
        {data: 'name', name: 'name'},
        {data: 'affiliation.corporation.name', name: 'affiliation.corporation.name'},
        {data: 'affiliation.alliance.name', name: 'affiliation.alliance.name'}
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
