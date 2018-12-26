<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Mail Results</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="mail">
      <thead>
      <tr>
        <th>{{ trans('web::seat.date') }}</th>
        <th>{{ trans('web::seat.from') }}</th>
        <th>{{ trans_choice('web::seat.title', 1) }}</th>
        <th>{{ trans('content') }}</th>
        <th>{{ trans('web::seat.to') }}</th>
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

<script type="text/javascript">

  $(function () {
    $('table#mail').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('support.search.mail.data') }}',
      columns         : [
        {data: 'timestamp', name: 'mail_headers.timestamp', render: human_readable},
        {data: 'from',      name: 'mail_headers.from', searchable: false},
        {data: 'subject',   name: 'mail_headers.subject'},
        {data: 'body',      name: 'body.body'},
        {data: 'tocounts',  name: 'tocounts', searchable: false},
        {data: 'read',      name: 'read', searchable: false}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
      'fnDrawCallback': function () {
        $(document).ready(function () {
          $('img').unveil(100);
          ids_to_names();
        });
      },
      'search' : {
        'search': '{!! addslashes(htmlspecialchars($query, ENT_NOQUOTES)) !!}'
      },
      order : [[0, "asc"]]
    });
  });

</script>

@endpush
