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

  $(function () {
    $('table#corporations').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('support.search.corporations.data') }}',
      columns         : [
        {data: 'name', name: 'name'},
        {data: 'ceo_id', name: 'ceo_id'},
        {data: 'alliance_id', name: 'alliance_id'},
        {data: 'tax_rate', name: 'tax_rate'},
        {data: 'member_count', name: 'member_count'}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
          ids_to_names();
        });
      },
      'search'        : {
        'search': '{{ $query }}'
      }
    });
  });

</script>

@endpush
