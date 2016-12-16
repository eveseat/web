<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Api Key Results</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="api-keys">
      <thead>
      <tr>
        <th>{{ trans_choice('web::seat.key_id', 1) }}</th>
        <th>{{ trans('web::seat.enabled') }}</th>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans('web::seat.expiry') }}</th>
        <th>{{ trans_choice('web::seat.character', 1) }}</th>
        <th></th>
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

<script>

  $(function () {
    $('table#api-keys').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('support.search.keys.data') }}',
      columns         : [
        {data: 'key_id', name: 'key_id'},
        {
          data: 'enabled', name: 'enabled', render: function (data) {
          if (data == 1) return 'Yes';
          if (data == 0) return 'No';
        }
        },
        {data: 'info.type', name: 'info.type'},
        {data: 'info.expires', name: 'info.expires'},
        {data: 'characters', name: 'characters', orderable: false},
        {data: 'actions', name: 'actions', orderable: false},
      ],
      "fnDrawCallback": function () {
        $(document).ready(function () {
          $("img").unveil(100);
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
