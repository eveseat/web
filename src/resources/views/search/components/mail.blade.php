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
        <th>{{ trans('web::seat.to') }}</th>
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

<script>

  $(function () {
    $('table#mail').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('support.search.mail.data') }}',
      columns         : [
        {data: 'sentDate', name: 'sentDate', render: human_readable},
        {data: 'senderName', name: 'senderName'},
        {data: 'title', name: 'title'},
        {data: 'tocounts', name: 'tocounts', searchable: false},
        {data: 'read', name: 'read', searchable: false},
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
