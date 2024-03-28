<div class="card">
  <div class="card-header">
    <h3 class="card-title">Characters Skills</h3>
  </div>
  <div class="card-body">
    <table class="table table-condensed table-striped table-hover" id="character-skills">
      <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
          <th>{{ trans_choice('web::seat.alliance', 1) }}</th>
          <th>{{ trans_choice('web::seat.group', 1) }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.level') }}</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

@push('javascript')

  <script type="text/javascript">
    $('table#character-skills').DataTable({
      processing  : true,
      serverSide  : true,
      ajax        : '{{ route('seatcore::support.search.skills.data') }}',
      columns     : [
        {data: 'character.name', name: 'character.name', 'searchable': false},
        {data: 'character.affiliation.corporation.name', name: 'character.affiliation.corporation.name', 'searchable': false},
        {data: 'character.affiliation.alliance.name', name: 'character.affiliation.alliance.name', 'searchable': false},
        {data: 'type.group.groupName', name: 'type.group.groupName'},
        {data: 'type.typeName', name: 'type.typeName'},
        {data: 'trained_skill_level', name: 'trained_skill_level'}
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
