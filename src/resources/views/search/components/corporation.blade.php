<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Corporations Results</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="corporations">
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
      ajax        : '{{ route('support.search.corporations.data') }}',
      columns     : [
        {data: 'name', name: 'name'},
        {data: 'ceo_id', name: 'ceo_id', searchable: false},
        {data: 'alliance_id', name: 'alliance_id', searchable: false},
        {data: 'tax_rate', name: 'tax_rate', searchable: false},
        {data: 'member_count', name: 'member_count', searchable: false}
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
