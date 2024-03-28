<div class="card">
  <div class="card-header">
    <h3 class="card-title">Corporations Results</h3>
  </div>
  <div class="card-body">
    <table class="table table-condensed table-striped table-hover" id="corporations">
      <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.ceo', 1) }}</th>
          <th>{{ trans_choice('web::seat.alliance', 1) }}</th>
          <th>{{ trans_choice('web::seat.tax_rate', 1) }}</th>
          <th>{{ trans_choice('web::seat.member_count', 1) }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('javascript')

  <script>
    $('table#corporations').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : '{{ route('seatcore::support.search.corporations.data') }}',
      columns     : [
        {data: 'name', name: 'name'},
        {data: 'ceo.name', name: 'ceo.name'},
        {data: 'alliance.name', name: 'alliance.name'},
        {data: 'tax_rate', name: 'tax_rate'},
        {data: 'member_count', name: 'member_count'}
      ],
      drawCallback: function () {
        $('img').unveil(100);
        ids_to_names();
      },
      'search'    : {
        'search': '{{ $query }}'
      }
    });
  </script>

@endpush
