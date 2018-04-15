<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Characters Skills</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="character-skills">
      <thead>
      <tr>
        <th>{{ trans_choice('web::seat.name', 1) }}</th>
        <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
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

  $(function () {
    $('table#character-skills').DataTable({
      processing      : true,
      serverSide      : true,
      ajax            : '{{ route('support.search.skills.data') }}',
      columns         : [
        {data: 'name',                name: 'character_infos.name'},
        {data: 'corporation_id',      name: 'character_infos.corporation_id'},
        {data: 'groupName',           name: 'invGroups.groupName'},
        {data: 'typeName',            name: 'invTypes.typeName'},
        {data: 'trained_skill_level', name: 'character_skills.trained_skill_level'}
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
      },
      order           : [[0, "asc"]]
    });
  });

</script>

@endpush
