<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Characters Assets</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="character-assets">
      <thead>
      <tr>
        <th>{{ trans('web::seat.character_name') }}</th>
        <th>{{ trans_choice('web::seat.name', 1) }}</th>
        <th>{{ trans('web::seat.location') }}</th>
        <th>{{ trans_choice('web::seat.group', 1) }}</th>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans('web::seat.quantity') }}</th>
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

  <script>

    $('table#character-assets').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : '{{ route('support.search.assets.data') }}',
      columns     : [
        {data: 'characterName', name: 'character_infos.name'},
        {data: 'name', name: 'character_assets.name'},
        {data: 'location', name: 'location', searchable: false},
        {data: 'groupName', name: 'invGroups.groupName'},
        {data: 'typeName', name: 'invTypes.typeName'},
        {data: 'quantity', name: 'quantity', searchable: false}
      ],
      drawCallback: function () {
        $('img').unveil(100);
      },
      'search'    : {
        'search': '{{ $query }}'
      },
      order       : [[0, "asc"]]
    });

  </script>

@endpush
