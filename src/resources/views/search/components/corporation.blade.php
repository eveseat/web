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
        {data: 'corporationName', name: 'corporationName'},
        {data: 'ceoName', name: 'ceoName'},
        {data: 'allianceName', name: 'allianceName'},
        {data: 'taxRate', name: 'taxRate'},
        {data: 'memberCount', name: 'memberCount'},
      ],
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
        });
      },
      'search'        : {
        'search': '{{ $query }}'
      }
    });
  });

</script>

@endpush
